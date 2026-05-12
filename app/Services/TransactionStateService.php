<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TransactionStateService
{
    public function transition(Transaction $transaction, TransactionStatus $targetStatus): Transaction
    {
        if (!$transaction->status->canTransitionTo($targetStatus)) {
            throw new RuntimeException(
                sprintf(
                    'Transition invalide : %s -> %s',
                    $transaction->status->value,
                    $targetStatus->value
                )
            );
        }

        return DB::transaction(function () use ($transaction, $targetStatus) {

            $transaction->refresh();

            $transaction->status = $targetStatus;

            match ($targetStatus) {
                TransactionStatus::FUNDED => $transaction->paid_at = now(),
                TransactionStatus::SHIPPED => $transaction->shipped_at = now(),
                TransactionStatus::DELIVERED => $transaction->delivered_at = now(),
                TransactionStatus::COMPLETED => $transaction->completed_at = now(),
                TransactionStatus::CANCELLED => $transaction->cancelled_at = now(),
                default => null,
            };

            $transaction->save();

            $transaction->logs()->create([
                'action' => 'status_transition',
                'description' => sprintf(
                    'Transition %s -> %s',
                    $transaction->getOriginal('status'),
                    $targetStatus->value
                ),
            ]);

            return $transaction;
        });
    }
}
