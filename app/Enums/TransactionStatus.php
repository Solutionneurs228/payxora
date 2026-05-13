<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case DISPUTED = 'disputed';
    case REFUNDED = 'refunded';
}
