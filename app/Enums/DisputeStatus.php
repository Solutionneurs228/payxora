<?php

namespace App\Enums;

enum DisputeStatus: string
{
    case OPEN = 'open';
    case MEDIATING = 'mediating';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match($this) {
            self::OPEN => 'Ouvert',
            self::MEDIATING => 'En médiation',
            self::RESOLVED => 'Résolu',
            self::CLOSED => 'Clôturé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::OPEN => 'red',
            self::MEDIATING => 'orange',
            self::RESOLVED => 'green',
            self::CLOSED => 'gray',
        };
    }
}
