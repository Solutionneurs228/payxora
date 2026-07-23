<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\EscrowAccount;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\Dispute;
use App\Enums\TransactionStatus;
use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Liste des transactions de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();

        $transactions = Transaction::where('seller_id', $user->id)
            ->orWhere('buyer_id', $user->id)
            ->with(['seller', 'buyer'])
            ->latest()
            ->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('transactions.create');
    }

    /**
     * Créer une nouvelle transaction
     */
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
            'product_name' => $validated['product_name'],
            'product_description' => $validated['product_description'] ?? null,
            'amount' => $validated['amount'],
            'shipping_address' => $validated['shipping_address'],
            'seller_notes' => $validated['seller_notes'] ?? null,
            'reference' => 'PAYX-' . Str::upper(Str::random(8)),
            'seller_id' => Auth::id(),
            'status' => TransactionStatus::PENDING_PAYMENT->value,
            'commission_amount' => $validated['amount'] * 0.03,
            'net_amount' => $validated['amount'] * 0.97,
        ]);

        ActivityLog::log('transaction_created', $transaction, Auth::user());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction créée. Partagez ce lien avec l\'acheteur.');
    }

    /**
     * Afficher une transaction
     */
    public function show(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);
        $transaction->load(['seller', 'buyer', 'escrow', 'dispute']);
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if ($transaction->seller_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->status !== TransactionStatus::PENDING_PAYMENT->value) {
            return back()->with('error', 'Impossible de modifier une transaction en cours.');
        }

        return view('transactions.edit', compact('transaction'));
    }

    /**
     * Mettre à jour une transaction
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if ($transaction->seller_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->status !== TransactionStatus::PENDING_PAYMENT->value) {
            return back()->with('error', 'Impossible de modifier une transaction en cours.');
        }

        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'product_description' => ['nullable', 'string', 'max:1000'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'seller_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $transaction->update([
            ...$validated,
            'commission_amount' => $validated['amount'] * 0.03,
            'net_amount' => $validated['amount'] * 0.97,
        ]);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction mise à jour.');
    }

    /**
     * Supprimer une transaction
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if ($transaction->seller_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->status !== TransactionStatus::PENDING_PAYMENT->value) {
            return back()->with('error', 'Impossible de supprimer une transaction en cours.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction supprimée.');
    }

    /**
     * SUPPRIMÉ : publish() — pas besoin de is_published
     * Une transaction créée est immédiatement active.
     */

    /**
     * Page de paiement
     */
    public function pay(Transaction $transaction)
    {
        if ($transaction->buyer_id && $transaction->buyer_id !== Auth::id()) {
            abort(403);
        }

        if (!$transaction->isPending()) {
            return back()->with('error', 'Cette transaction ne peut plus être payée.');
        }

        $transaction->update(['buyer_id' => Auth::id()]);

        return redirect()->route('payment.show', $transaction);
    }

    /**
     * Afficher une transaction publique par référence
     * SUPPRIMÉ : ->where('is_published', true)
     */
    public function showPublic(string $reference)
    {
        $transaction = Transaction::where('reference', $reference)
            ->with('seller')
            ->firstOrFail();

        return view('transactions.public', compact('transaction'));
    }

    /**
     * Un acheteur réclame une transaction publique
     */
    public function claim(Request $request, string $reference)
    {
        $user = Auth::user();
        $transaction = Transaction::where('reference', $reference)->firstOrFail();

        if ($transaction->buyer_id) {
            return back()->with('error', 'Cette transaction a déjà un acheteur.');
        }

        $transaction->update([
            'buyer_id' => $user->id,
            'status' => TransactionStatus::PENDING_PAYMENT->value,
        ]);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Vous êtes maintenant l\'acheteur de cette transaction.');
    }

    /**
     * Le vendeur marque la commande comme expédiée
     */
    public function ship(Request $request, Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if ($transaction->seller_id !== Auth::id()) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        if ($transaction->status !== TransactionStatus::FUNDED->value) {
            return back()->with('error', 'Cette transaction n\'est pas encore payée.');
        }

        try {
            $transaction->update([
                'status' => TransactionStatus::SHIPPED->value,
                'shipped_at' => now(),
            ]);

            Notification::create([
                'user_id' => $transaction->buyer_id,
                'type' => 'transaction_shipped',
                'title' => 'Commande expédiée',
                'message' => 'Votre commande "' . $transaction->product_name . '" a été expédiée par le vendeur.',
                'link' => route('transactions.show', $transaction),
                'read' => false,
            ]);

            if ($transaction->buyer) {
                BrevoService::sendTransactionNotification($transaction->buyer, $transaction, 'shipped');
            }

            ActivityLog::log('transaction_shipped', $transaction, Auth::user());

            Log::info('Transaction shipped', [
                'transaction_id' => $transaction->id,
                'seller_id' => Auth::id(),
            ]);

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Commande marquée comme expédiée. L\'acheteur a été notifié.');

        } catch (\Exception $e) {
            Log::error('Erreur expédition', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Une erreur est survenue lors de l\'expédition.');
        }
    }

    /**
     * Marque la commande comme livrée
     */
    public function deliver(Request $request, Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if ($transaction->seller_id !== Auth::id()) {
            abort(403, 'Seul le vendeur peut marquer la commande comme livrée.');
        }

        if ($transaction->status !== TransactionStatus::SHIPPED->value) {
            return back()->with('error', 'Cette transaction n\'est pas encore expédiée.');
        }

        try {
            $transaction->update([
                'status' => TransactionStatus::DELIVERED->value,
                'delivered_at' => now(),
            ]);

            Notification::create([
                'user_id' => $transaction->buyer_id,
                'type' => 'transaction_delivered',
                'title' => 'Commande livrée',
                'message' => 'Votre commande "' . $transaction->product_name . '" est livrée. Veuillez confirmer la réception pour libérer le paiement au vendeur.',
                'link' => route('transactions.show', $transaction),
                'read' => false,
            ]);

            ActivityLog::log('transaction_delivered', $transaction, Auth::user());

            Log::info('Transaction delivered', [
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Commande marquée comme livrée. En attente de confirmation de l\'acheteur.');

        } catch (\Exception $e) {
            Log::error('Erreur livraison', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * L'acheteur confirme avoir reçu → libère le paiement → COMPLETED
     */
    public function receive(Request $request, Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if ($transaction->buyer_id !== Auth::id()) {
            abort(403, 'Seul l\'acheteur peut confirmer la réception.');
        }

        if (!in_array($transaction->status, [
            TransactionStatus::DELIVERED->value,
            TransactionStatus::SHIPPED->value,
        ])) {
            return back()->with('error', 'Cette transaction n\'est pas encore expédiée ou livrée.');
        }

        try {
            DB::transaction(function () use ($transaction) {
                $transaction->update([
                    'status' => TransactionStatus::COMPLETED->value,
                    'completed_at' => now(),
                ]);

                if ($transaction->escrow) {
                    $transaction->escrow->update([
                        'status' => 'released',
                        'released_at' => now(),
                    ]);
                }

                Notification::create([
                    'user_id' => $transaction->seller_id,
                    'type' => 'transaction_completed',
                    'title' => 'Paiement libéré',
                    'message' => "Le paiement pour {$transaction->product_name} a été libéré.",
                    'link' => route('transactions.show', $transaction),
                    'read' => false,
                ]);

                BrevoService::sendPaymentReleased($transaction->seller, $transaction);

                ActivityLog::log('delivery_confirmed', $transaction, Auth::user());
            });

            Log::info('Transaction completed - payment released', [
                'transaction_id' => $transaction->id,
                'buyer_id' => Auth::id(),
                'seller_id' => $transaction->seller_id,
                'amount' => $transaction->amount,
                'net_amount' => $transaction->net_amount,
            ]);

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Réception confirmée. Le vendeur a été payé.');

        } catch (\Exception $e) {
            Log::error('Erreur libération paiement', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Annuler une transaction
     */
    public function cancel(Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if (!$transaction->canBeCancelled()) {
            return back()->with('error', 'Cette transaction ne peut plus être annulée.');
        }

        $transaction->update([
            'status' => TransactionStatus::CANCELLED->value,
            'cancelled_at' => now(),
        ]);

        if ($transaction->escrow && in_array($transaction->escrow->status, ['funded', 'held'])) {
            $transaction->escrow->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);
        }

        ActivityLog::log('transaction_cancelled', $transaction, Auth::user());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction annulée.');
    }

    /**
     * Compléter une transaction (admin ou auto)
     */
    public function complete(Request $request, Transaction $transaction)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        if (!in_array($transaction->status, [
            TransactionStatus::DELIVERED->value,
            TransactionStatus::SHIPPED->value,
        ])) {
            return back()->with('error', 'La transaction doit être expédiée ou livrée avant d\'être complétée.');
        }

        return $this->receive($request, $transaction);
    }

    /**
     * Ouvrir un litige sur une transaction
     */
    public function openDispute(Request $request, Transaction $transaction)
    {
        $this->authorizeAccess($transaction);

        if (!in_array($transaction->status, [
            TransactionStatus::FUNDED->value,
            TransactionStatus::SHIPPED->value,
            TransactionStatus::DELIVERED->value,
        ])) {
            return back()->with('error', 'Impossible d\'ouvrir un litige sur cette transaction.');
        }

        if ($transaction->dispute()->where('status', 'open')->exists()) {
            return back()->with('error', 'Un litige est déjà ouvert pour cette transaction.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string', 'max:2000'],
        ]);

        try {
            $dispute = Dispute::create([
                'transaction_id' => $transaction->id,
                'opener_id' => Auth::id(),
                'reason' => $validated['reason'],
                'description' => $validated['description'],
                'status' => 'open',
            ]);

            $transaction->update([
                'status' => TransactionStatus::DISPUTED->value,
            ]);

            if ($transaction->escrow) {
                $transaction->escrow->update([
                    'status' => 'disputed',
                ]);
            }

            $otherPartyId = ($transaction->buyer_id === Auth::id())
                ? $transaction->seller_id
                : $transaction->buyer_id;

            Notification::create([
                'user_id' => $otherPartyId,
                'type' => 'dispute_opened',
                'title' => 'Litige ouvert',
                'message' => 'Un litige a été ouvert sur la transaction "' . $transaction->product_name . '".',
                'link' => route('disputes.show', $dispute),
                'read' => false,
            ]);

            ActivityLog::log('dispute_opened', $transaction, Auth::user());

            return redirect()->route('disputes.show', $dispute)
                ->with('success', 'Litige ouvert. Un administrateur va examiner votre cas.');

        } catch (\Exception $e) {
            Log::error('Erreur ouverture litige', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Une erreur est survenue lors de l\'ouverture du litige.');
        }
    }

    /**
     * Vérifier l'accès à la transaction
     */
    private function authorizeAccess(Transaction $transaction)
    {
        $user = Auth::user();
        if ($transaction->seller_id !== $user->id && $transaction->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }
    }
}