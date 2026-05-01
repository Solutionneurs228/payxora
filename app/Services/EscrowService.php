<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\EscrowAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EscrowService
{
    /**
     * Créer une transaction en brouillon
     */
    public function createDraft(array $data): Transaction
    {
        $transaction = Transaction::create([
            'seller_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'XOF',
            'status' => TransactionStatus::DRAFT->value,
            'delivery_address' => $data['delivery_address'] ?? null,
            'commission_amount' => $this->calculateCommission($data['amount']),
        ]);

        $this->logTransaction($transaction, 'created', null, TransactionStatus::DRAFT);

        return $transaction;
    }

    /**
     * Publier la transaction (passage en attente de paiement)
     */
    public function publish(Transaction $transaction): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::PENDING_PAYMENT)) {
            return false;
        }

        $oldStatus = $transaction->status;

        $transaction->update([
            'status' => TransactionStatus::PENDING_PAYMENT->value,
        ]);

        $this->logTransaction($transaction, 'published', $oldStatus, TransactionStatus::PENDING_PAYMENT);

        return true;
    }

    /**
     * Confirmer le paiement (fonds bloqués)
     */
    public function fund(Transaction $transaction, int $buyerId): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::FUNDED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        DB::transaction(function () use ($transaction, $buyerId) {
            $transaction->update([
                'buyer_id' => $buyerId,
                'status' => TransactionStatus::FUNDED->value,
            ]);

            // Créer/mettre à jour le compte séquestre
            EscrowAccount::updateOrCreate(
                ['user_id' => $transaction->seller_id],
                ['status' => 'active']
            );
        });

        $this->logTransaction($transaction, 'funded', $oldStatus, TransactionStatus::FUNDED);

        return true;
    }

    /**
     * Marquer comme expédié
     */
    public function ship(Transaction $transaction, ?string $trackingNumber = null): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::SHIPPED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        $transaction->update([
            'status' => TransactionStatus::SHIPPED->value,
            'tracking_number' => $trackingNumber,
        ]);

        $this->logTransaction($transaction, 'shipped', $oldStatus, TransactionStatus::SHIPPED);

        return true;
    }

    /**
     * Marquer comme livré
     */
    public function deliver(Transaction $transaction): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::DELIVERED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        $transaction->update([
            'status' => TransactionStatus::DELIVERED->value,
            'confirmation_deadline' => now()->addHours(config('payxora.confirmation_deadline_hours', 48)),
        ]);

        $this->logTransaction($transaction, 'delivered', $oldStatus, TransactionStatus::DELIVERED);

        return true;
    }

    /**
     * Confirmer réception et libérer fonds
     */
    public function complete(Transaction $transaction): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::COMPLETED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => TransactionStatus::COMPLETED->value,
            ]);

            // Libérer les fonds vers le vendeur
            $this->releaseFunds($transaction);
        });

        $this->logTransaction($transaction, 'completed', $oldStatus, TransactionStatus::COMPLETED);

        return true;
    }

    /**
     * Ouvrir un litige
     */
    public function dispute(Transaction $transaction, int $initiatorId, string $reason): bool
    {
        if (!$transaction->isDisputable()) {
            return false;
        }

        $oldStatus = $transaction->status;

        $transaction->update([
            'status' => TransactionStatus::DISPUTED->value,
        ]);

        // Créer le litige
        \App\Models\Dispute::create([
            'transaction_id' => $transaction->id,
            'initiator_id' => $initiatorId,
            'reason' => $reason,
            'status' => 'open',
        ]);

        $this->logTransaction($transaction, 'disputed', $oldStatus, TransactionStatus::DISPUTED);

        return true;
    }

    /**
     * Rembourser l'acheteur
     */
    public function refund(Transaction $transaction): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::REFUNDED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => TransactionStatus::REFUNDED->value,
            ]);

            // Rembourser l'acheteur
            $this->processRefund($transaction);
        });

        $this->logTransaction($transaction, 'refunded', $oldStatus, TransactionStatus::REFUNDED);

        return true;
    }

    /**
     * Annuler la transaction
     */
    public function cancel(Transaction $transaction): bool
    {
        if (!$transaction->isCancellable()) {
            return false;
        }

        $oldStatus = $transaction->status;

        $transaction->update([
            'status' => TransactionStatus::CANCELLED->value,
        ]);

        $this->logTransaction($transaction, 'cancelled', $oldStatus, TransactionStatus::CANCELLED);

        return true;
    }

    /**
     * Calculer la commission
     */
    public function calculateCommission(float $amount): float
    {
        $config = config('payxora.commission');
        $commission = $amount * $config['rate'];

        return max($config['minimum'], min($commission, $config['maximum']));
    }

    /**
     * Logger une action sur la transaction
     */
    protected function logTransaction(
        Transaction $transaction, 
        string $action, 
        ?TransactionStatus $fromStatus, 
        TransactionStatus $toStatus
    ): void {
        TransactionLog::create([
            'transaction_id' => $transaction->id,
            'actor_id' => Auth::id(),
            'action' => $action,
            'from_status' => $fromStatus?->value,
            'to_status' => $toStatus->value,
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Libérer les fonds au vendeur
     */
    protected function releaseFunds(Transaction $transaction): void
    {
        $escrowAccount = EscrowAccount::firstOrCreate(
            ['user_id' => $transaction->seller_id],
            ['balance' => 0, 'currency' => 'XOF', 'status' => 'active']
        );

        $netAmount = $transaction->amount - $transaction->commission_amount;

        $escrowAccount->increment('balance', $netAmount);
    }

    /**
     * Traiter le remboursement
     */
    protected function processRefund(Transaction $transaction): void
    {
        // Logique de remboursement via le service de paiement
        // À implémenter selon le provider MNO
    }
}
