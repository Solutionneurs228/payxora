<?php

namespace App\Services;

use App\Models\KycProfile;
use App\Models\User;
use App\Enums\KycStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class KycService
{
    /**
     * Soumet un profil KYC pour verification.
     */
    public function submit(User $user, array $data): KycProfile
    {
        // Supprimer l'ancien KYC si rejete
        if ($user->kycProfile && $user->kycProfile->isRejected()) {
            $this->deleteDocuments($user->kycProfile);
            $user->kycProfile->delete();
        }

        $kyc = KycProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'id_type' => $data['id_type'],
                'id_number' => $data['id_number'],
                'address' => $data['address'],
                'city' => $data['city'],
                'country' => $data['country'] ?? 'Togo',
                'status' => KycStatus::PENDING,
                'submitted_at' => now(),
                'verified_at' => null,
                'verified_by' => null,
                'rejection_reason' => null,
            ]
        );

        // Stocker les documents
        if (isset($data['id_front']) && $data['id_front'] instanceof UploadedFile) {
            $kyc->id_front_path = $data['id_front']->store('private/kyc/' . $user->id . '/id_front', 'local');
        }
        if (isset($data['id_back']) && $data['id_back'] instanceof UploadedFile) {
            $kyc->id_back_path = $data['id_back']->store('private/kyc/' . $user->id . '/id_back', 'local');
        }
        if (isset($data['selfie']) && $data['selfie'] instanceof UploadedFile) {
            $kyc->selfie_path = $data['selfie']->store('private/kyc/' . $user->id . '/selfie', 'local');
        }

        $kyc->save();

        return $kyc;
    }

    /**
     * Approuve un profil KYC (admin).
     */
    public function approve(KycProfile $kyc, User $admin): void
    {
        $kyc->update([
            'status' => KycStatus::APPROVED,
            'verified_at' => now(),
            'verified_by' => $admin->id,
            'rejection_reason' => null,
        ]);
    }

    /**
     * Rejette un profil KYC (admin).
     */
    public function reject(KycProfile $kyc, User $admin, string $reason): void
    {
        $kyc->update([
            'status' => KycStatus::REJECTED,
            'verified_at' => now(),
            'verified_by' => $admin->id,
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Supprime les documents d'un profil KYC.
     */
    public function deleteDocuments(KycProfile $kyc): void
    {
        if ($kyc->id_front_path) {
            Storage::disk('local')->delete($kyc->id_front_path);
        }
        if ($kyc->id_back_path) {
            Storage::disk('local')->delete($kyc->id_back_path);
        }
        if ($kyc->selfie_path) {
            Storage::disk('local')->delete($kyc->selfie_path);
        }
    }

    /**
     * Recupere un document KYC securise.
     */
    public function getDocument(KycProfile $kyc, string $type): ?string
    {
        $path = match ($type) {
            'id_front' => $kyc->id_front_path,
            'id_back' => $kyc->id_back_path,
            'selfie' => $kyc->selfie_path,
            default => null,
        };

        if (!$path || !Storage::disk('local')->exists($path)) {
            return null;
        }

        return Storage::disk('local')->path($path);
    }
}
