<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TransactionStatus;

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
        'status' => TransactionStatus::class,
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
            $transaction->commission_amount = max(100, min($transaction->amount * 0.03, 50000));
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

    public function isPendingPayment(): bool
    {
        return $this->status === TransactionStatus::PENDING_PAYMENT;
    }

    public function isFunded(): bool
    {
        return $this->status === TransactionStatus::FUNDED;
    }

    public function isShipped(): bool
    {
        return $this->status === TransactionStatus::SHIPPED;
    }

    public function isDelivered(): bool
    {
        return $this->status === TransactionStatus::DELIVERED;
    }

    public function isCompleted(): bool
    {
        return $this->status === TransactionStatus::COMPLETED;
    }

    public function isDisputed(): bool
    {
        return $this->status === TransactionStatus::DISPUTED;
    }

    public function isCancelled(): bool
    {
        return $this->status === TransactionStatus::CANCELLED;
    }

    public function isDraft(): bool
    {
        return $this->status === TransactionStatus::DRAFT;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            TransactionStatus::DRAFT,
            TransactionStatus::PENDING_PAYMENT,
            TransactionStatus::FUNDED,
        ]);
    }

    public function canOpenDispute(): bool
    {
        return in_array($this->status, [
            TransactionStatus::SHIPPED,
            TransactionStatus::DELIVERED,
        ]) && $this->dispute_deadline && $this->dispute_deadline->isFuture();
    }

    public function getStatusLabel(): string
    {
        return $this->status->label();
    }

    public function logs()
{
    return $this->hasMany(TransactionLog::class);
}

    public function getStatusColor(): string
    {
        return match($this->status) {
            TransactionStatus::DRAFT => 'bg-gray-100 text-gray-700',
            TransactionStatus::PENDING_PAYMENT => 'bg-yellow-100 text-yellow-700',
            TransactionStatus::FUNDED => 'bg-blue-100 text-blue-700',
            TransactionStatus::SHIPPED => 'bg-indigo-100 text-indigo-700',
            TransactionStatus::DELIVERED => 'bg-purple-100 text-purple-700',
            TransactionStatus::COMPLETED => 'bg-emerald-100 text-emerald-700',
            TransactionStatus::CANCELLED => 'bg-gray-100 text-gray-500',
            TransactionStatus::DISPUTED => 'bg-red-100 text-red-700',
            TransactionStatus::REFUNDED => 'bg-slate-100 text-slate-700',
        };
    }
}
