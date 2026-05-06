<?php

namespace App\Models;

use App\Enums\KycStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycProfile extends Model
{
    use HasFactory;

    protected $table = 'kyc_profiles';

    protected $fillable = [
        'user_id',
        'id_type',
        'id_number',
        'id_front_path',
        'id_back_path',
        'selfie_path',
        'address',
        'city',
        'country',
        'status',
        'submitted_at',
        'verified_at',
        'verified_by',
        'rejection_reason',
        'phone_verified',
    ];

    protected $casts = [
        'status' => KycStatus::class,
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'phone_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isApproved(): bool
    {
        return $this->status === KycStatus::APPROVED;
    }

    public function isPending(): bool
    {
        return $this->status === KycStatus::PENDING;
    }

    public function isRejected(): bool
    {
        return $this->status === KycStatus::REJECTED;
    }

    public function canSubmit(): bool
    {
        return in_array($this->status, [KycStatus::NOT_SUBMITTED, KycStatus::REJECTED]);
    }

    public function getIdFrontUrl(): ?string
    {
        return $this->id_front_path ? route('kyc.document', ['type' => 'id_front', 'id' => $this->id]) : null;
    }

    public function getIdBackUrl(): ?string
    {
        return $this->id_back_path ? route('kyc.document', ['type' => 'id_back', 'id' => $this->id]) : null;
    }

    public function getSelfieUrl(): ?string
    {
        return $this->selfie_path ? route('kyc.document', ['type' => 'selfie', 'id' => $this->id]) : null;
    }
}
