<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Models\EscrowAccount;
use App\Models\Transaction;
use App\Models\TransactionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service metier central de l'escrow.
 *
 * NOTE : Ce service N'INJECTE PAS PaymentService pour eviter
 * les dependances circulaires. Le remboursement est delegue au
 * controller qui appelle PaymentService separement.
 */
class EscrowService
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function create(array $data): Transaction
    {
        $transaction = Transaction::create([
            'seller_id'           => Auth::id(),
            'product_name'        => $data['product_name'],
            'product_description' => $data['product_description'] ?? null,
            'amount'              => $data['amount'],
            'currency'            => $data['currency'] ?? 'XOF',
            'status'              => TransactionStatus::DRAFT,
            'shipping_address'    => $data['shipping_address'] ?? null,
            'seller_notes'        => $data['seller_notes'] ?? null,
        ]);

        $this->logTransaction($transaction, 'created', null, TransactionStatus::DRAFT);

        return $transaction;
    }

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

    public function fund(Transaction $transaction, int $buyerId): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::FUNDED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        DB::transaction(function () use ($transaction, $buyerId) {
            $transaction->update([
                'buyer_id' => $buyerId,
                'status'   => TransactionStatus::FUNDED,
                'paid_at'  => now(),
            ]);

            EscrowAccount::updateOrCreate(
                ['user_id' => $transaction->seller_id],
                ['status' => 'active', 'currency' => 'XOF']
            );
        });

        $this->logTransaction($transaction, 'funded', $oldStatus, TransactionStatus::FUNDED);

        return true;
    }

    public function ship(Transaction $transaction, ?string $trackingNumber = null): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::SHIPPED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        $transaction->update([
            'status'          => TransactionStatus::SHIPPED,
            'tracking_number' => $trackingNumber,
            'shipped_at'      => now(),
        ]);

        $this->logTransaction($transaction, 'shipped', $oldStatus, TransactionStatus::SHIPPED);

        return true;
    }

    public function deliver(Transaction $transaction): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::DELIVERED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        $transaction->update([
            'status'                => TransactionStatus::DELIVERED,
            'delivered_at'          => now(),
            'confirmation_deadline' => now()->addHours(config('payxora.dispute_response_hours', 48)),
        ]);

        $this->logTransaction($transaction, 'delivered', $oldStatus, TransactionStatus::DELIVERED);

        return true;
    }

    public function complete(Transaction $transaction): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::COMPLETED)) {
            return false;
        }

        $oldStatus = $transaction->status;

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status'       => TransactionStatus::COMPLETED,
                'completed_at' => now(),
            ]);

            $this->releaseFunds($transaction);
        });

        $this->logTransaction($transaction, 'completed', $oldStatus, TransactionStatus::COMPLETED);

        return true;
    }

    public function dispute(Transaction $transaction, int $initiatorId, string $reason): bool
    {
        if (!$transaction->isDisputable()) {
            return false;
        }

        $oldStatus = $transaction->status;

        $transaction->update([
            'status' => TransactionStatus::DISPUTED,
        ]);

        \App\Models\Dispute::create([
            'transaction_id' => $transaction->id,
            'initiator_id'   => $initiatorId,
            'reason'         => $reason,
            'status'         => 'open',
        ]);

        $this->logTransaction($transaction, 'disputed', $oldStatus, TransactionStatus::DISPUTED);

        return true;
    }

    /**
     * Rembourser la transaction.
     *
     * NOTE : Cette methode met a jour le statut mais NE DECLENCHE PAS
     * le remboursement via le provider. Le controller doit appeler
     * PaymentService::refund() separement.
     */
    public function refund(Transaction $transaction, ?string $reason = null): bool
    {
        if (!$transaction->status->canTransitionTo(TransactionStatus::REFUNDED)) {
            Log::warning('EscrowService : remboursement impossible', [
                'transaction_id' => $transaction->id,
                'current_status' => $transaction->status->value,
            ]);
            return false;
        }

        $oldStatus = $transaction->status;
        $reason = $reason ?? 'Remboursement automatique';

        DB::transaction(function () use ($transaction, $reason) {
            $transaction->update([
                'status' => TransactionStatus::REFUNDED,
            ]);

            $this->reverseEscrowCredit($transaction);
        });

        $this->logTransaction($transaction, 'refunded', $oldStatus, TransactionStatus::REFUNDED);

        Log::info('EscrowService : transaction remboursee', [
            'transaction_id' => $transaction->id,
            'reason'         => $reason,
        ]);

        return true;
    }

    public function cancel(Transaction $transaction): bool
    {
        if (!$transaction->isCancellable()) {
            return false;
        }

        $oldStatus = $transaction->status;

        $transaction->update([
            'status'       => TransactionStatus::CANCELLED,
            'cancelled_at' => now(),
        ]);

        $this->logTransaction($transaction, 'cancelled', $oldStatus, TransactionStatus::CANCELLED);

        return true;
    }

    public function calculateCommission(float $amount): float
    {
        $rate = config('payxora.commission_rate', 3.0);
        $min  = config('payxora.commission_minimum', 100);
        $max  = config('payxora.commission_maximum', 50000);

        $commission = $amount * ($rate / 100);

        return max($min, min($commission, $max));
    }

    protected function logTransaction(
        Transaction $transaction,
        string $action,
        ?TransactionStatus $fromStatus,
        TransactionStatus $toStatus
    ): void {
        TransactionLog::create([
            'transaction_id' => $transaction->id,
            'actor_id'       => Auth::id(),
            'action'         => $action,
            'from_status'    => $fromStatus?->value,
            'to_status'      => $toStatus->value,
            'ip_address'     => request()->ip(),
        ]);
    }

    protected function releaseFunds(Transaction $transaction): void
    {
        $escrowAccount = EscrowAccount::firstOrCreate(
            ['user_id' => $transaction->seller_id],
            ['balance' => 0, 'currency' => 'XOF', 'status' => 'active']
        );

        $netAmount = $transaction->amount - $transaction->commission_amount;

        $escrowAccount->increment('balance', $netAmount);

        Log::info('EscrowService : fonds liberes au vendeur', [
            'seller_id'   => $transaction->seller_id,
            'amount'      => $netAmount,
            'commission'  => $transaction->commission_amount,
        ]);
    }

    protected function reverseEscrowCredit(Transaction $transaction): void
    {
        Log::info('EscrowService : reverse escrow credit', [
            'transaction_id' => $transaction->id,
            'note'           => 'Aucun reverse necessaire si fonds non liberes',
        ]);
    }
}
