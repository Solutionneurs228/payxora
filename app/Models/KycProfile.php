<?php

namespace App\Models;

use App\Enums\KycStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycProfile extends Model
{
    use HasFactory;

    protected $table = 'kyc_profiles';

    protected $fillable = [
        'user_id',
        'id_type',
        'id_number',
        'id_document_path',
        'selfie_path',
        'phone_verified',
        'status',
        'verified_at',
        'verified_by',
        'rejection_reason',
    ];

    protected $casts = [
        'phone_verified' => 'boolean',
        'verified_at' => 'datetime',
        'status' => KycStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
