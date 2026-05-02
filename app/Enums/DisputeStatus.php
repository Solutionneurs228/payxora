<?php

namespace App\Enums;

enum DisputeStatus: string
{
    case OPEN = 'open';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match($this) {
            self::OPEN => 'Ouvert',
            self::RESOLVED => 'Resolu',
            self::CLOSED => 'Ferme',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::OPEN => 'red',
            self::RESOLVED => 'green',
            self::CLOSED => 'gray',
        };
    }
}
