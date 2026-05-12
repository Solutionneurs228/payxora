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
            self::FUNDED => 'Payé - En séquestre',
            self::SHIPPED => 'Expédié',
            self::DELIVERED => 'Livré',
            self::COMPLETED => 'Terminé',
            self::DISPUTED => 'En litige',
            self::REFUNDED => 'Remboursé',
            self::CANCELLED => 'Annulé',
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
            self::REFUNDED => 'orange',
            self::CANCELLED => 'gray',
        };
    }

    public function canTransitionTo(self $newStatus): bool
    {
        return match($this) {
            self::DRAFT => in_array($newStatus, [self::PENDING_PAYMENT, self::CANCELLED]),
            self::PENDING_PAYMENT => in_array($newStatus, [self::FUNDED, self::CANCELLED]),
            self::FUNDED => in_array($newStatus, [self::SHIPPED, self::CANCELLED]),
            self::SHIPPED => in_array($newStatus, [self::DELIVERED, self::DISPUTED]),
            self::DELIVERED => in_array($newStatus, [self::COMPLETED, self::DISPUTED]),
            self::DISPUTED => in_array($newStatus, [self::COMPLETED, self::REFUNDED]),
            default => false,
        };
    }
}
