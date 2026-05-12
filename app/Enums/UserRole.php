<?php

namespace App\Enums;

enum UserRole: string
{
    case BUYER = 'buyer';
    case SELLER = 'seller';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match($this) {
            self::BUYER => 'Acheteur',
            self::SELLER => 'Vendeur',
            self::ADMIN => 'Administrateur',
        };
    }

    public function canSell(): bool
    {
        return in_array($this, [self::SELLER, self::ADMIN]);
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }
}
