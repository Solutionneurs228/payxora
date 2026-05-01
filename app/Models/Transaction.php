<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TransactionStatus;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'seller_id',
        'buyer_id',
        'product_name',
        'product_description',
        'amount',
        'commission_amount',
        'net_amount',
        'status',
        'payment_method',
        'payment_reference',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'completed_at',
        'cancelled_at',
        'shipping_address',
        'tracking_number',
        'dispute_deadline',
        'seller_notes',
        'buyer_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'dispute_deadline' => 'datetime',
        'status' => TransactionStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->reference = 'PAYX-' . strtoupper(uniqid());
            $transaction->commission_amount = $transaction->amount * 0.03;
            $transaction->net_amount = $transaction->amount - $transaction->commission_amount;
        });
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function escrow()
    {
        return $this->hasOne(EscrowAccount::class);
    }

    public function dispute()
    {
        return $this->hasOne(Dispute::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['paid', 'shipped', 'delivered', 'disputed']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function isPending(): bool { return $this->status === 'pending'; }
    public function isPaid(): bool { return $this->status === 'paid'; }
    public function isShipped(): bool { return $this->status === 'shipped'; }
    public function isDelivered(): bool { return $this->status === 'delivered'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isDisputed(): bool { return $this->status === 'disputed'; }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'paid']);
    }

    public function canOpenDispute(): bool
    {
        return in_array($this->status, ['shipped', 'delivered'])
            && $this->dispute_deadline && $this->dispute_deadline->isFuture();
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'En attente de paiement',
            'paid' => 'Paye — En sequestre',
            'shipped' => 'Expedie',
            'delivered' => 'Livre — En attente confirmation',
            'completed' => 'Termine',
            'cancelled' => 'Annule',
            'disputed' => 'En litige',
            'refunded' => 'Rembourse',
            default => $this->status,
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'paid' => 'info',
            'shipped' => 'primary',
            'delivered' => 'purple',
            'completed' => 'success',
            'cancelled' => 'secondary',
            'disputed' => 'danger',
            'refunded' => 'dark',
            default => 'secondary',
        };
    }
}
