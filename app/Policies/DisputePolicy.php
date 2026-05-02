<?php

namespace App\Policies;

use App\Models\Dispute;
use App\Models\User;

class DisputePolicy
{
    /**
     * Determine whether the user can view the dispute.
     */
    public function view(User $user, Dispute $dispute): bool
    {
        return $user->id === $dispute->initiator_id
            || $user->id === $dispute->transaction->buyer_id
            || $user->id === $dispute->transaction->seller_id
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can reply to the dispute.
     */
    public function reply(User $user, Dispute $dispute): bool
    {
        return $user->id === $dispute->initiator_id
            || $user->id === $dispute->transaction->buyer_id
            || $user->id === $dispute->transaction->seller_id;
    }
}
