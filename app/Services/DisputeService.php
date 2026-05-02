<?php

namespace App\Services;

use App\Enums\DisputeStatus;
use App\Enums\TransactionStatus;
use App\Models\Dispute;
use App\Models\DisputeMessage;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisputeService
{
    public function __construct(
        private EscrowService $escrowService,
        private NotificationService $notificationService,
    ) {}

    /**
     * Ouvrir un litige
     */
    public function open(Transaction $transaction, string $reason, ?string $description = null): Dispute
    {
        return DB::transaction(function () use ($transaction, $reason, $description) {
            $dispute = Dispute::create([
                'transaction_id' => $transaction->id,
                'initiator_id' => Auth::id(),
                'reason' => $reason,
                'description' => $description,
                'status' => DisputeStatus::OPEN,
            ]);

            $this->escrowService->dispute($transaction, Auth::id(), $reason);

            $this->notificationService->notifyDisputeOpened($transaction, Auth::user());

            return $dispute;
        });
    }

    /**
     * Repondre a un litige
     */
    public function reply(Dispute $dispute, string $message): DisputeMessage
    {
        $this->authorizeAccess($dispute);

        return DisputeMessage::create([
            'dispute_id' => $dispute->id,
            'user_id' => Auth::id(),
            'message' => $message,
        ]);
    }

    /**
     * Arbitrer un litige (admin)
     */
    public function arbitrate(Dispute $dispute, string $resolution, ?string $notes = null): bool
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Seul un administrateur peut arbitrer.');
        }

        return DB::transaction(function () use ($dispute, $resolution, $notes) {
            $dispute->update([
                'status' => DisputeStatus::RESOLVED,
                'resolution' => $resolution,
                'resolution_notes' => $notes,
                'resolved_at' => now(),
                'resolved_by' => Auth::id(),
            ]);

            $transaction = $dispute->transaction;

            if ($resolution === 'refund_buyer') {
                $this->escrowService->refund($transaction);
            } elseif ($resolution === 'release_seller') {
                $this->escrowService->complete($transaction);
            }

            $this->notificationService->notifyDisputeResolved($transaction, $resolution);

            return true;
        });
    }

    /**
     * Fermer un litige
     */
    public function close(Dispute $dispute): bool
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $dispute->update([
            'status' => DisputeStatus::CLOSED,
            'closed_at' => now(),
            'closed_by' => Auth::id(),
        ]);

        return true;
    }

    private function authorizeAccess(Dispute $dispute): void
    {
        $user = Auth::user();
        $transaction = $dispute->transaction;

        if ($transaction->seller_id !== $user->id
            && $transaction->buyer_id !== $user->id
            && !$user->isAdmin()) {
            abort(403);
        }
    }
}
