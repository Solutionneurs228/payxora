<?php

namespace App\Models;

use App\Enums\DisputeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'initiator_id',
        'reason',
        'description',
        'status',
        'resolution',
        'resolution_notes',
        'resolved_at',
        'resolved_by',
        'closed_at',
        'closed_by',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'status' => DisputeStatus::class,
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(DisputeMessage::class)->orderBy('created_at');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function isOpen(): bool
    {
        return $this->status === DisputeStatus::OPEN;
    }

    public function isResolved(): bool
    {
        return $this->status === DisputeStatus::RESOLVED;
    }

    public function isClosed(): bool
    {
        return $this->status === DisputeStatus::CLOSED;
    }
}
