<?php

namespace App\Services;

use App\Enums\DisputeStatus;
use App\Enums\TransactionStatus;
use App\Models\Dispute;
use App\Models\DisputeMessage;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DisputeService
{
    /**
     * Créer un litige
     */
    public function createDispute(Transaction $transaction, string $reason): Dispute
    {
        $dispute = Dispute::create([
            'transaction_id' => $transaction->id,
            'initiator_id' => Auth::id(),
            'reason' => $reason,
            'status' => DisputeStatus::OPEN->value,
            'resolution' => 'none',
        ]);

        // Mettre la transaction en litige
        app(EscrowService::class)->dispute($transaction, Auth::id(), $reason);

        return $dispute;
    }

    /**
     * Ajouter un message au litige
     */
    public function addMessage(Dispute $dispute, string $message, ?array $attachment = null): DisputeMessage
    {
        $disputeMessage = DisputeMessage::create([
            'dispute_id' => $dispute->id,
            'user_id' => Auth::id(),
            'message' => $message,
            'attachment_path' => $attachment['path'] ?? null,
        ]);

        return $disputeMessage;
    }

    /**
     * Démarrer la médiation
     */
    public function startMediation(Dispute $dispute): void
    {
        $dispute->update([
            'status' => DisputeStatus::MEDIATING->value,
        ]);

        // Notifier les parties
        app(NotificationService::class)->notifyMediationStarted($dispute);
    }

    /**
     * Résoudre le litige - rembourser l'acheteur
     */
    public function resolveRefund(Dispute $dispute): void
    {
        DB::transaction(function () use ($dispute) {
            $dispute->update([
                'status' => DisputeStatus::RESOLVED->value,
                'resolution' => 'refund_buyer',
                'refund_amount' => $dispute->transaction->amount,
                'resolved_at' => now(),
            ]);

            app(EscrowService::class)->refund($dispute->transaction);
        });

        app(NotificationService::class)->notifyDisputeResolved($dispute, 'refund');
    }

    /**
     * Résoudre le litige - payer le vendeur
     */
    public function resolvePaySeller(Dispute $dispute): void
    {
        DB::transaction(function () use ($dispute) {
            $dispute->update([
                'status' => DisputeStatus::RESOLVED->value,
                'resolution' => 'pay_seller',
                'resolved_at' => now(),
            ]);

            app(EscrowService::class)->complete($dispute->transaction);
        });

        app(NotificationService::class)->notifyDisputeResolved($dispute, 'pay_seller');
    }

    /**
     * Résoudre le litige - split
     */
    public function resolveSplit(Dispute $dispute, float $refundAmount): void
    {
        DB::transaction(function () use ($dispute, $refundAmount) {
            $dispute->update([
                'status' => DisputeStatus::RESOLVED->value,
                'resolution' => 'split',
                'refund_amount' => $refundAmount,
                'resolved_at' => now(),
            ]);

            // Logique de split à implémenter
        });

        app(NotificationService::class)->notifyDisputeResolved($dispute, 'split');
    }

    /**
     * Clôturer le litige
     */
    public function close(Dispute $dispute): void
    {
        $dispute->update([
            'status' => DisputeStatus::CLOSED->value,
            'resolved_at' => now(),
        ]);
    }
}
