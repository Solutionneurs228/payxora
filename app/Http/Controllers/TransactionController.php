<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Services\EscrowService;
use App\Services\PaymentService;
use App\Http\Requests\CreateTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected EscrowService $escrowService;
    protected PaymentService $paymentService;

    public function __construct(EscrowService $escrowService, PaymentService $paymentService)
    {
        $this->escrowService = $escrowService;
        $this->paymentService = $paymentService;
    }

    /**
     * Affiche la liste des transactions de l'utilisateur.
     */
    public function index()
    {
        $user = Auth::user();

        $transactions = Transaction::where('seller_id', $user->id)
            ->orWhere('buyer_id', $user->id)
            ->with(['seller', 'buyer', 'payment'])
            ->latest()
            ->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Affiche le formulaire de creation.
     */
    public function create()
    {
        return view('transactions.create');
    }

    /**
     * Cree une nouvelle transaction (brouillon).
     */
    public function store(CreateTransactionRequest $request)
    {
        $transaction = $this->escrowService->create($request->validated());

        ActivityLog::log('transaction_created', $transaction, Auth::user());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction creee en brouillon. Publiez-la pour partager le lien avec l\'acheteur.');
    }

    /**
     * Affiche une transaction.
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        $transaction->load(['seller', 'buyer', 'payment', 'escrow', 'dispute', 'logs']);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Affiche le formulaire d'edition.
     */
    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        if (!$transaction->isDraft()) {
            return back()->with('error', 'Seules les transactions en brouillon peuvent etre modifiees.');
        }

        return view('transactions.edit', compact('transaction'));
    }

    /**
     * Met a jour une transaction (brouillon uniquement).
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        if (!$transaction->isDraft()) {
            return back()->with('error', 'Seules les transactions en brouillon peuvent etre modifiees.');
        }

        $transaction->update($request->validated());

        // Recalculer commission si montant change
        if ($request->has('amount')) {
            $transaction->commission_amount = $transaction->amount * config('payxora.commission_rate', 3.0) / 100;
            $transaction->net_amount = $transaction->amount - $transaction->commission_amount;
            $transaction->save();
        }

        ActivityLog::log('transaction_updated', $transaction, Auth::user());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction mise a jour.');
    }

    /**
     * Supprime une transaction (brouillon ou annulee uniquement).
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        if (!$transaction->isDraft() && !$transaction->isCancelled()) {
            return back()->with('error', 'Seules les transactions en brouillon ou annulees peuvent etre supprimees.');
        }

        $transaction->delete();

        ActivityLog::log('transaction_deleted', $transaction, Auth::user());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction supprimee.');
    }

    /**
     * Publie une transaction (passe de DRAFT a PENDING_PAYMENT).
     */
    public function publish(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        if (!$transaction->isDraft()) {
            return back()->with('error', 'Cette transaction ne peut pas etre publiee.');
        }

        $success = $this->escrowService->publish($transaction);

        if (!$success) {
            return back()->with('error', 'Impossible de publier la transaction.');
        }

        ActivityLog::log('transaction_published', $transaction, Auth::user());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction publiee ! Partagez ce lien avec l\'acheteur : ' . route('transactions.show', $transaction));
    }

    /**
     * Attribue l'acheteur et redirige vers le paiement.
     */
    public function pay(Transaction $transaction)
    {
        $this->authorize('pay', $transaction);

        if ($transaction->buyer_id && $transaction->buyer_id !== Auth::id()) {
            abort(403, 'Cette transaction est deja reservee a un autre acheteur.');
        }

        if (!$transaction->isPendingPayment()) {
            return back()->with('error', 'Cette transaction ne peut plus etre payee.');
        }

        // Attribuer l'acheteur
        if (!$transaction->buyer_id) {
            $transaction->update(['buyer_id' => Auth::id()]);
        }

        return redirect()->route('payment.show', $transaction);
    }

    /**
     * Confirme la reception (acheteur) et libere les fonds.
     */
    public function complete(Transaction $transaction)
    {
        $this->authorize('complete', $transaction);

        if ($transaction->buyer_id !== Auth::id()) {
            abort(403, 'Seul l\'acheteur peut confirmer la reception.');
        }

        if (!$transaction->isDelivered()) {
            return back()->with('error', 'La livraison doit etre confirmee avant de finaliser.');
        }

        $success = $this->escrowService->complete($transaction);

        if (!$success) {
            return back()->with('error', 'Impossible de finaliser la transaction.');
        }

        // Notifier vendeur
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'transaction_completed',
            'title' => 'Paiement libere',
            'message' => "Le paiement pour {$transaction->product_name} a ete libere.",
            'link' => route('transactions.show', $transaction),
        ]);

        // Email via Brevo
        \App\Services\BrevoService::sendPaymentReleased($transaction->seller, $transaction);

        ActivityLog::log('delivery_confirmed', $transaction, Auth::user());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Reception confirmee. Le vendeur a ete paye.');
    }

    /**
     * Marque comme expedie (vendeur).
     */
    public function ship(Request $request, Transaction $transaction)
    {
        $this->authorize('ship', $transaction);

        if ($transaction->seller_id !== Auth::id()) {
            abort(403, 'Seul le vendeur peut marquer comme expedie.');
        }

        $trackingNumber = $request->input('tracking_number');

        $success = $this->escrowService->ship($transaction, $trackingNumber);

        if (!$success) {
            return back()->with('error', 'Impossible de marquer comme expedie.');
        }

        // Notifier acheteur
        if ($transaction->buyer_id) {
            Notification::create([
                'user_id' => $transaction->buyer_id,
                'type' => 'transaction_shipped',
                'title' => 'Commande expediee',
                'message' => "Votre commande {$transaction->product_name} a ete expediee.",
                'link' => route('transactions.show', $transaction),
            ]);
        }

        ActivityLog::log('transaction_shipped', $transaction, Auth::user());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Commande marquee comme expediee.');
    }

    /**
     * Marque comme livre (vendeur ou admin).
     */
    public function deliver(Transaction $transaction)
    {
        $this->authorize('deliver', $transaction);

        if ($transaction->seller_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $success = $this->escrowService->deliver($transaction);

        if (!$success) {
            return back()->with('error', 'Impossible de marquer comme livre.');
        }

        // Notifier acheteur
        if ($transaction->buyer_id) {
            Notification::create([
                'user_id' => $transaction->buyer_id,
                'type' => 'transaction_delivered',
                'title' => 'Commande livree',
                'message' => "Votre commande {$transaction->product_name} est livree. Confirmez la reception dans les 48h.",
                'link' => route('transactions.show', $transaction),
            ]);
        }

        ActivityLog::log('transaction_delivered', $transaction, Auth::user());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Commande marquee comme livree. L\'acheteur a 48h pour confirmer.');
    }

    /**
     * Annule une transaction.
     */
    public function cancel(Transaction $transaction)
    {
        $this->authorize('cancel', $transaction);

        if (!$transaction->canBeCancelled()) {
            return back()->with('error', 'Cette transaction ne peut plus etre annulee.');
        }

        $success = $this->escrowService->cancel($transaction);

        if (!$success) {
            return back()->with('error', 'Impossible d\'annuler la transaction.');
        }

        ActivityLog::log('transaction_cancelled', $transaction, Auth::user());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction annulee.');
    }
}
