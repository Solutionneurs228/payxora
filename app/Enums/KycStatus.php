<?php

namespace App\Enums;

enum KycStatus: string
{
    case NOT_SUBMITTED = 'not_submitted';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::NOT_SUBMITTED => 'Non soumis',
            self::PENDING => 'En attente',
            self::APPROVED => 'Approuve',
            self::REJECTED => 'Rejete',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NOT_SUBMITTED => 'gray',
            self::PENDING => 'yellow',
            self::APPROVED => 'green',
            self::REJECTED => 'red',
        };
    }

    public function canSubmit(): bool
    {
        return in_array($this, [self::NOT_SUBMITTED, self::REJECTED]);
    }
}
