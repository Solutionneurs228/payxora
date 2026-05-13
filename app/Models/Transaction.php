<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'buyer_id',
        'seller_id',
        'title',
        'description',
        'amount',
        'commission_amount',
        'net_amount',
        'currency',
        'status',
        'tracking_number',
        'published_at',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'confirmation_deadline',
        'completed_at',
        'cancelled_at',
        'dispute_deadline',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'published_at' => 'datetime',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'confirmation_deadline' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'dispute_deadline' => 'datetime',
        'expires_at' => 'datetime',
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

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function escrow(): HasOne
    {
        return $this->hasOne(EscrowAccount::class);
    }

    public function dispute(): HasOne
    {
        return $this->hasOne(Dispute::class);
    }

    public function isPending(): bool { return $this->status === 'pending'; }
    public function isPaid(): bool { return $this->status === 'paid'; }
    public function isShipped(): bool { return $this->status === 'shipped'; }
    public function isDelivered(): bool { return $this->status === 'delivered'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isDisputed(): bool { return $this->status === 'disputed'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

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
