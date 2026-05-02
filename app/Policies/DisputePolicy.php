<?php

namespace App\Policies;

use App\Models\Dispute;
use App\Models\User;

class DisputePolicy
{
    public function view(User $user, Dispute $dispute): bool
    {
        return $user->id === $dispute->transaction->seller_id
            || $user->id === $dispute->transaction->buyer_id
            || $user->isAdmin();
    }

    public function reply(User $user, Dispute $dispute): bool
    {
        return ($user->id === $dispute->transaction->seller_id
            || $user->id === $dispute->transaction->buyer_id)
            && $dispute->isOpen();
    }

    public function arbitrate(User $user, Dispute $dispute): bool
    {
        return $user->isAdmin() && $dispute->isOpen();
    }

    public function close(User $user, Dispute $dispute): bool
    {
        return $user->isAdmin();
    }
}
