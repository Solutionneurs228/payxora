<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kyc extends Model
{
    use HasFactory;

    protected $table = 'kycs';

    protected $fillable = [
        'user_id',
        'full_name',
        'birth_date',
        'nationality',
        'document_type',
        'document_number',
        'address',
        'document_front',
        'document_back',
        'selfie',
        'status',
        'admin_notes',
        'phone_verified',
        'submitted_at',
        'verified_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'phone_verified' => 'boolean',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
