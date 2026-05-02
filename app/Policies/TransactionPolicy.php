<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    /**
     * Determine whether the user can view the transaction.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->buyer_id 
            || $user->id === $transaction->seller_id
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the transaction.
     */
    public function update(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id && $transaction->status === 'pending';
    }

    /**
     * Determine whether the user can cancel the transaction.
     */
    public function cancel(User $user, Transaction $transaction): bool
    {
        return ($user->id === $transaction->buyer_id || $user->id === $transaction->seller_id)
            && in_array($transaction->status, ['pending', 'paid']);
    }

    /**
     * Determine whether the user can confirm delivery.
     */
    public function confirmDelivery(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->buyer_id 
            && $transaction->status === 'in_delivery';
    }

    /**
     * Determine whether the user can pay for the transaction.
     */
    public function pay(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->buyer_id 
            && $transaction->status === 'pending';
    }

    /**
     * Determine whether the user can create a dispute.
     */
    public function createDispute(User $user, Transaction $transaction): bool
    {
        return ($user->id === $transaction->buyer_id || $user->id === $transaction->seller_id)
            && in_array($transaction->status, ['paid', 'in_delivery', 'completed'])
            && !$transaction->dispute;
    }
}
