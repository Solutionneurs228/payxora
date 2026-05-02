<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id
            || $user->id === $transaction->buyer_id
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSeller() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Transaction $transaction): bool
    {
        return ($user->id === $transaction->seller_id && $transaction->isDraft())
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return ($user->id === $transaction->seller_id && ($transaction->isDraft() || $transaction->isCancelled()))
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can pay for the transaction.
     */
    public function pay(User $user, Transaction $transaction): bool
    {
        return $transaction->isPendingPayment()
            && (!$transaction->buyer_id || $transaction->buyer_id === $user->id)
            && $user->isBuyer();
    }

    /**
     * Determine whether the user can ship the transaction.
     */
    public function ship(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id
            && $transaction->isFunded();
    }

    /**
     * Determine whether the user can deliver the transaction.
     */
    public function deliver(User $user, Transaction $transaction): bool
    {
        return ($user->id === $transaction->seller_id && $transaction->isShipped())
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can complete the transaction.
     */
    public function complete(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->buyer_id
            && $transaction->isDelivered();
    }

    /**
     * Determine whether the user can cancel the transaction.
     */
    public function cancel(User $user, Transaction $transaction): bool
    {
        return ($user->id === $transaction->seller_id && $transaction->canBeCancelled())
            || ($user->id === $transaction->buyer_id && $transaction->canBeCancelled())
            || $user->isAdmin();
    }
}
