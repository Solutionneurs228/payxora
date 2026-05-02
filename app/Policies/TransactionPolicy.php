<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id
            || $user->id === $transaction->buyer_id
            || $user->isAdmin();
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id
            || $user->isAdmin();
    }

    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin();
    }

    public function pay(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->buyer_id
            || ($transaction->buyer_id === null && $user->id !== $transaction->seller_id);
    }

    public function ship(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id;
    }

    public function confirmDelivery(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->buyer_id;
    }

    public function dispute(User $user, Transaction $transaction): bool
    {
        return ($user->id === $transaction->seller_id || $user->id === $transaction->buyer_id)
            && $transaction->canOpenDispute();
    }
}
