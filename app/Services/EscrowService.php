<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Models\EscrowAccount;
use App\Models\Transaction;
use App\Models\TransactionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EscrowService
{
    /**
     * Creer une transaction en brouillon
     */
    public function create(array $data): Transaction
    {
        $transaction = Transaction::create([
            'seller_id' => Auth::id(),
            'product_name' => $data['product_name'],
            'product_description' => $data['product_description'] ?? null,
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'XOF',
            'status' => TransactionStatus::DRAFT,
            'shipping_address' => $data['shipping_address'] ?? null,
            'seller_notes' => $data['seller_notes'] ?? null,
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
            'status' => TransactionStatus::PENDING_PAYMENT,
        ]);

        $this->logTransaction($transaction, 'published', $oldStatus, TransactionStatus::PENDING_PAYMENT);

        return true;
    }

    /**
     * Confirmer le paiement (fonds bloques)
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
                'status' => TransactionStatus::FUNDED,
                'paid_at' => now(),
            ]);

            // Creer/mettre a jour le compte sequestre
            EscrowAccount::updateOrCreate(
                ['user_id' => $transaction->seller_id],
                ['status' => 'active', 'currency' => 'XOF']
            );
        });

        $this->logTransaction($transaction, 'funded', $oldStatus, TransactionStatus::FUNDED);

        return true;
    }

    /**
     * Marquer comme expedie
     */
    public function ship(Transaction $transaction, ?string $trackingNumber = null): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::SHIPPED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        $transaction->update([
            'status' => TransactionStatus::SHIPPED,
            'tracking_number' => $trackingNumber,
            'shipped_at' => now(),
        ]);

        $this->logTransaction($transaction, 'shipped', $oldStatus, TransactionStatus::SHIPPED);

        return true;
    }

    /**
     * Marquer comme livre
     */
    public function deliver(Transaction $transaction): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::DELIVERED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        $transaction->update([
            'status' => TransactionStatus::DELIVERED,
            'delivered_at' => now(),
            'confirmation_deadline' => now()->addHours(config('payxora.dispute_response_hours', 48)),
        ]);

        $this->logTransaction($transaction, 'delivered', $oldStatus, TransactionStatus::DELIVERED);

        return true;
    }

    /**
     * Confirmer reception et liberer fonds
     */
    public function complete(Transaction $transaction): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::COMPLETED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => TransactionStatus::COMPLETED,
                'completed_at' => now(),
            ]);

            // Liberer les fonds vers le vendeur
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
            'status' => TransactionStatus::DISPUTED,
        ]);

        // Creer le litige
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
                'status' => TransactionStatus::REFUNDED,
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
            'status' => TransactionStatus::CANCELLED,
            'cancelled_at' => now(),
        ]);

        $this->logTransaction($transaction, 'cancelled', $oldStatus, TransactionStatus::CANCELLED);

        return true;
    }

    /**
     * Calculer la commission
     */
    public function calculateCommission(float $amount): float
    {
        $rate = config('payxora.commission_rate', 3.0);
        $min = config('payxora.commission_minimum', 100);
        $max = config('payxora.commission_maximum', 50000);

        $commission = $amount * ($rate / 100);

        return max($min, min($commission, $max));
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
     * Liberer les fonds au vendeur
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
        // TODO: Implementer le remboursement via le service de paiement MNO
        // Cette methode sera appelee par le PaymentService quand les providers seront integres
    }
}
