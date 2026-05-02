<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case DRAFT = 'draft';
    case PENDING_PAYMENT = 'pending_payment';
    case FUNDED = 'funded';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
    case DISPUTED = 'disputed';
    case REFUNDED = 'refunded';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Brouillon',
            self::PENDING_PAYMENT => 'En attente de paiement',
            self::FUNDED => 'Paye — En sequestre',
            self::SHIPPED => 'Expedie',
            self::DELIVERED => 'Livre',
            self::COMPLETED => 'Termine',
            self::DISPUTED => 'En litige',
            self::REFUNDED => 'Rembourse',
            self::CANCELLED => 'Annule',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::PENDING_PAYMENT => 'yellow',
            self::FUNDED => 'blue',
            self::SHIPPED => 'indigo',
            self::DELIVERED => 'purple',
            self::COMPLETED => 'green',
            self::DISPUTED => 'red',
            self::REFUNDED => 'slate',
            self::CANCELLED => 'gray',
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return match($this) {
            self::DRAFT => in_array($target, [self::PENDING_PAYMENT, self::CANCELLED]),
            self::PENDING_PAYMENT => in_array($target, [self::FUNDED, self::CANCELLED]),
            self::FUNDED => in_array($target, [self::SHIPPED, self::CANCELLED, self::DISPUTED]),
            self::SHIPPED => in_array($target, [self::DELIVERED, self::DISPUTED]),
            self::DELIVERED => in_array($target, [self::COMPLETED, self::DISPUTED]),
            self::DISPUTED => in_array($target, [self::COMPLETED, self::REFUNDED]),
            self::COMPLETED, self::REFUNDED, self::CANCELLED => false,
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::COMPLETED, self::REFUNDED, self::CANCELLED]);
    }
}
