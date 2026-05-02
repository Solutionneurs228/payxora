<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'seller_id',
        'buyer_id',
        'product_name',
        'product_description',
        'amount',
        'commission_amount',
        'net_amount',
        'currency',
        'status',
        'shipping_address',
        'seller_notes',
        'tracking_number',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'completed_at',
        'cancelled_at',
        'confirmation_deadline',
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
        'confirmation_deadline' => 'datetime',
        'status' => TransactionStatus::class,
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->reference = 'PAYX-' . strtoupper(uniqid());
            $transaction->commission_amount = $transaction->amount * config('payxora.commission_rate', 3.0) / 100;
            $transaction->net_amount = $transaction->amount - $transaction->commission_amount;
            $transaction->currency = $transaction->currency ?? 'XOF';
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

    public function logs(): HasMany
    {
        return $this->hasMany(TransactionLog::class)->orderBy('created_at', 'desc');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', TransactionStatus::PENDING_PAYMENT);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            TransactionStatus::FUNDED,
            TransactionStatus::SHIPPED,
            TransactionStatus::DELIVERED,
            TransactionStatus::DISPUTED,
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', TransactionStatus::COMPLETED);
    }

    // Helpers d'état
    public function isDraft(): bool { return $this->status === TransactionStatus::DRAFT; }
    public function isPendingPayment(): bool { return $this->status === TransactionStatus::PENDING_PAYMENT; }
    public function isFunded(): bool { return $this->status === TransactionStatus::FUNDED; }
    public function isShipped(): bool { return $this->status === TransactionStatus::SHIPPED; }
    public function isDelivered(): bool { return $this->status === TransactionStatus::DELIVERED; }
    public function isCompleted(): bool { return $this->status === TransactionStatus::COMPLETED; }
    public function isDisputed(): bool { return $this->status === TransactionStatus::DISPUTED; }
    public function isRefunded(): bool { return $this->status === TransactionStatus::REFUNDED; }
    public function isCancelled(): bool { return $this->status === TransactionStatus::CANCELLED; }

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
        ]) && $this->confirmation_deadline && $this->confirmation_deadline->isFuture();
    }

    public function isDisputable(): bool
    {
        return $this->canOpenDispute();
    }

    public function isCancellable(): bool
    {
        return $this->canBeCancelled();
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            TransactionStatus::DRAFT => 'Brouillon',
            TransactionStatus::PENDING_PAYMENT => 'En attente de paiement',
            TransactionStatus::FUNDED => 'Paye — En sequestre',
            TransactionStatus::SHIPPED => 'Expedie',
            TransactionStatus::DELIVERED => 'Livre — En attente confirmation',
            TransactionStatus::COMPLETED => 'Termine',
            TransactionStatus::CANCELLED => 'Annule',
            TransactionStatus::DISPUTED => 'En litige',
            TransactionStatus::REFUNDED => 'Rembourse',
            default => (string) $this->status->value,
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            TransactionStatus::DRAFT => 'secondary',
            TransactionStatus::PENDING_PAYMENT => 'warning',
            TransactionStatus::FUNDED => 'info',
            TransactionStatus::SHIPPED => 'primary',
            TransactionStatus::DELIVERED => 'purple',
            TransactionStatus::COMPLETED => 'success',
            TransactionStatus::CANCELLED => 'secondary',
            TransactionStatus::DISPUTED => 'danger',
            TransactionStatus::REFUNDED => 'dark',
            default => 'secondary',
        };
    }
}
