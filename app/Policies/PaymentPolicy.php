<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;

class PaymentPolicy
{
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->seller_id
            || $user->id === $transaction->buyer_id
            || $user->isAdmin();
    }

    public function initiate(User $user, Transaction $transaction): bool
    {
        return $transaction->isPendingPayment()
            && $transaction->buyer_id === $user->id;
    }

    public function refund(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin()
            || ($user->id === $transaction->buyer_id && $transaction->isDisputed());
    }
}
