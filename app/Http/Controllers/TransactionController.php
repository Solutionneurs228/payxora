<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct(
        private EscrowService $escrowService
    ) {}

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

    public function create()
    {
        return view('transactions.create');
    }

    public function store(CreateTransactionRequest $request)
    {
        $transaction = $this->escrowService->create($request->validated());

        // Auto-publish si pas brouillon (comportement actuel)
        $this->escrowService->publish($transaction);

        ActivityLog::log('transaction_created', $transaction, Auth::user());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction creee. Partagez ce lien avec l'acheteur.');
    }

    public function show(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);
        $transaction->load(['seller', 'buyer', 'payment', 'escrow', 'dispute', 'logs']);

        return view('transactions.show', compact('transaction'));
    }

    public function pay(Transaction $transaction)
    {
        if ($transaction->buyer_id && $transaction->buyer_id !== Auth::id()) {
            abort(403);
        }

        if (!$transaction->isPendingPayment()) {
            return back()->with('error', 'Cette transaction ne peut plus etre payee.');
        }

        // Attribuer l'acheteur et passer au paiement
        $transaction->update(['buyer_id' => Auth::id()]);

        return redirect()->route('payment.show', $transaction);
    }

    public function confirmDelivery(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if (!$transaction->isDelivered()) {
            return back()->with('error', 'La livraison n'est pas confirmee.');
        }

        if ($transaction->buyer_id !== Auth::id()) {
            abort(403, 'Seul l'acheteur peut confirmer la reception.');
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

    public function cancel(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if (!$transaction->canBeCancelled()) {
            return back()->with('error', 'Cette transaction ne peut plus etre annulee.');
        }

        $success = $this->escrowService->cancel($transaction);

        if (!$success) {
            return back()->with('error', 'Impossible d'annuler la transaction.');
        }

        ActivityLog::log('transaction_cancelled', $transaction, Auth::user());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction annulee.');
    }

    /**
     * Marquer comme expedie (vendeur)
     */
    public function ship(Request $request, Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

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
     * Marquer comme livre (vendeur ou systeme)
     */
    public function markDelivered(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

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
            ->with('success', 'Commande marquee comme livree. L'acheteur a 48h pour confirmer.');
    }

    private function authorizeAccess(Transaction $transaction): void
    {
        $user = Auth::user();
        if ($transaction->seller_id !== $user->id
            && $transaction->buyer_id !== $user->id
            && !$user->isAdmin()) {
            abort(403);
        }
    }
}
