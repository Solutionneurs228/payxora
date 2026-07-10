<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Enums\TransactionStatus;
use App\Models\EscrowAccount;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = Transaction::where('seller_id', $user->id)
            ->orWhere('buyer_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'product_description' => ['nullable', 'string', 'max:1000'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'seller_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $transaction = Transaction::create([
            'title' => $validated['product_name'],
            'description' => $validated['product_description'] ?? null,
            'amount' => $validated['amount'],
            'seller_id' => Auth::id(),
            'status' => TransactionStatus::DRAFT,
        ]);

        ActivityLog::log('transaction_created', $transaction, Auth::user());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction creee. Partagez ce lien avec l\'acheteur : ' . route('transactions.public', $transaction->reference));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['seller', 'buyer', 'payment', 'escrow', 'dispute', 'logs']);
        $this->authorizeAccess($transaction);
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

        $transaction->update(['buyer_id' => Auth::id()]);

        return redirect()->route('payment.show', $transaction);
    }

    public function confirmDelivery(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if ($transaction->status !== TransactionStatus::DELIVERED) {
            return back()->with('error', 'La livraison n\'est pas confirmee.');
        }

        if ($transaction->buyer_id !== Auth::id()) {
            abort(403, 'Seul l\'acheteur peut confirmer la reception.');
        }

        $transaction->update([
            'status' => TransactionStatus::COMPLETED,
            'completed_at' => now(),
        ]);

        // Liberer fonds escrow
        if ($transaction->escrow) {
            $transaction->escrow->update([
                'status' => 'released',
                'released_at' => now(),
            ]);
        }

        // Notifier vendeur
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'transaction_completed',
            'title' => 'Paiement libere',
            'message' => "Le paiement pour {$transaction->title} a ete libere.",
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

        $transaction->update([
            'status' => TransactionStatus::CANCELLED,
            'cancelled_at' => now(),
        ]);

        ActivityLog::log('transaction_cancelled', $transaction, Auth::user());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction annulee.');
    }

    /**
     * Affiche la transaction via lien public (sans auth)
     */
public function showPublic(string $reference)
{
    $transaction = Transaction::where('reference', $reference)
        ->whereIn('status', ['draft', 'pending_payment'])  // ← Strings directement
        ->firstOrFail();

    return view('transactions.public', compact('transaction'));
}

    /**
     * L'acheteur reclame la transaction
     */
    public function claim(Request $request, string $reference)
    {
        $transaction = Transaction::where('reference', $reference)
            ->whereNull('buyer_id')
            ->whereIn('status', [TransactionStatus::DRAFT, TransactionStatus::PENDING_PAYMENT])
            ->firstOrFail();

        if ($transaction->seller_id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas acheter votre propre produit.');
        }

        if (!Auth::user()->isKycVerified()) {
            return redirect()->route('kyc.create')
                ->with('info', 'Veuillez completer votre KYC avant d\'acheter.');
        }

        $transaction->update([
            'buyer_id' => Auth::id(),
            'status' => TransactionStatus::PENDING_PAYMENT,
            'published_at' => now(),
        ]);

        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'buyer_claimed',
            'title' => 'Acheteur trouve !',
            'message' => Auth::user()->name . ' souhaite acheter ' . $transaction->title,
            'link' => route('transactions.show', $transaction),
        ]);

        \App\Services\BrevoService::sendBuyerFound($transaction->seller, $transaction, Auth::user());

        return redirect()->route('payment.show', $transaction)
            ->with('success', 'Transaction reclamee ! Procedez au paiement.');
    }

    private function authorizeAccess(Transaction $transaction)
    {
        $user = Auth::user();
        if ($transaction->seller_id !== $user->id && $transaction->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }
    }


    /**
     * Marquer la transaction comme expédiée par le vendeur
     */
    public function ship(Request $request, Transaction $transaction)
    {
        // Vérifier que l'utilisateur est le vendeur
        if ($transaction->seller_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas le vendeur de cette transaction.');
        }

        // Vérifier que le paiement a été effectué
        if ($transaction->status !== TransactionStatus::FUNDED) {
            return back()->with('error', 'Le paiement n\'a pas encore été effectué.');
        }

        $validated = $request->validate([
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'shipping_method' => ['required', 'in:pickup,delivery,in_person'],
            'shipping_notes'  => ['nullable', 'string', 'max:500'],
        ]);

        // Mettre à jour la transaction
        $transaction->update([
            'status' => TransactionStatus::SHIPPED,
            'shipped_at' => now(),
            'tracking_number' => $validated['tracking_number'] ?? null,
            'shipping_method' => $validated['shipping_method'],
            'shipping_notes'  => $validated['shipping_notes'] ?? null,
        ]);

        // Notifier l'acheteur
        Notification::create([
            'user_id' => $transaction->buyer_id,
            'type' => 'item_shipped',
            'title' => 'Article expédié !',
            'message' => "Votre achat '{$transaction->title}' a été expédié.",
            'link' => route('transactions.show', $transaction),
        ]);

        // Envoyer email à l'acheteur
        \App\Services\BrevoService::sendItemShipped($transaction->buyer, $transaction);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Article marqué comme expédié ! L\'acheteur a été notifié.');
    }

    /**
     * L'acheteur confirme la réception
     */
    public function receive(Request $request, Transaction $transaction)
    {
        if ($transaction->buyer_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->status !== TransactionStatus::SHIPPED) {
            return back()->with('error', 'L\'article n\'a pas encore été expédié.');
        }

        $transaction->update([
            'status' => TransactionStatus::DELIVERED,
            'received_at' => now(),
        ]);

        // Libérer les fonds du escrow au vendeur
        $escrow = EscrowAccount::where('transaction_id', $transaction->id)->first();
        if ($escrow) {
            $escrow->update([
                'status' => 'released',
                'released_at' => now(),
            ]);
        }

        // Notifier le vendeur
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'funds_released',
            'title' => 'Fonds libérés !',
            'message' => "Les fonds de '{$transaction->title}' ont été libérés. Vous pouvez les retirer.",
            'link' => route('transactions.show', $transaction),
        ]);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Réception confirmée ! Les fonds ont été libérés au vendeur.');
    }

}
