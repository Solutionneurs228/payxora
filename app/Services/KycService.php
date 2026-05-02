<?php

namespace App\Services;

use App\Enums\KycStatus;
use App\Models\KycProfile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class KycService
{
    public function submitKyc(User $user, array $data): KycProfile
    {
        $kyc = $user->kycProfile ?? new KycProfile(['user_id' => $user->id]);

        // Upload documents
        if (isset($data['id_document'])) {
            $path = $data['id_document']->store('kyc/documents', 'public');
            $kyc->id_document_path = $path;
        }

        if (isset($data['selfie'])) {
            $path = $data['selfie']->store('kyc/selfies', 'public');
            $kyc->selfie_path = $path;
        }

        $kyc->id_type = $data['id_type'];
        $kyc->id_number = $data['id_number'];
        $kyc->status = KycStatus::PENDING;
        $kyc->phone_verified = true;
        $kyc->save();

        return $kyc;
    }

    public function approveKyc(KycProfile $kyc, int $adminId): void
    {
        $kyc->update([
            'status' => KycStatus::APPROVED,
            'verified_at' => now(),
            'verified_by' => $adminId,
        ]);
    }

    public function rejectKyc(KycProfile $kyc, string $reason): void
    {
        $kyc->update([
            'status' => KycStatus::REJECTED,
            'rejection_reason' => $reason,
        ]);
    }

    public function isVerified(User $user): bool
    {
        return $user->kycProfile !== null
            && $user->kycProfile->status === KycStatus::APPROVED;
    }
}
