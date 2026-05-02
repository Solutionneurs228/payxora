<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case SELLER = 'seller';
    case BUYER = 'buyer';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrateur',
            self::SELLER => 'Vendeur',
            self::BUYER => 'Acheteur',
        };
    }
}
