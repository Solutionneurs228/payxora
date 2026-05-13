<?php

namespace App\Enums;

enum KycStatus: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';
    case NOT_SUBMITTED = 'not_submitted';
}
