<?php

namespace App\Services;

use App\Enums\KycStatus;
use App\Models\KycProfile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class KycService
{
    /**
     * Soumettre une demande KYC
     */
    public function submitKyc(User $user, array $data, UploadedFile $idPhoto, UploadedFile $selfie): KycProfile
    {
        // Supprimer l'ancien KYC s'il existe
        if ($user->kycProfile) {
            $this->deleteFiles($user->kycProfile);
            $user->kycProfile->delete();
        }

        // Stocker les fichiers
        $idPhotoPath = $this->storeFile($idPhoto, 'kyc/id-photos');
        $selfiePath = $this->storeFile($selfie, 'kyc/selfies');

        // Créer le profil KYC
        $kycProfile = KycProfile::create([
            'user_id' => $user->id,
            'id_type' => $data['id_type'],
            'id_number' => $data['id_number'],
            'id_photo_path' => $idPhotoPath,
            'selfie_path' => $selfiePath,
            'status' => KycStatus::PENDING->value,
        ]);

        // Mettre à jour le statut KYC de l'utilisateur
        $user->update(['kyc_status' => KycStatus::PENDING->value]);

        // Notifier les admins si auto-approve est désactivé
        if (!config('payxora.kyc.auto_approve')) {
            // Notification admin à implémenter
        }

        return $kycProfile;
    }

    /**
     * Approuver un KYC
     */
    public function approve(KycProfile $kycProfile, ?string $notes = null): void
    {
        $kycProfile->update([
            'status' => KycStatus::APPROVED->value,
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        $kycProfile->user->update(['kyc_status' => KycStatus::APPROVED->value]);

        // Notifier l'utilisateur
        app(NotificationService::class)->notifyKycApproved($kycProfile->user);
    }

    /**
     * Rejeter un KYC
     */
    public function reject(KycProfile $kycProfile, string $reason): void
    {
        $kycProfile->update([
            'status' => KycStatus::REJECTED->value,
            'rejection_reason' => $reason,
        ]);

        $kycProfile->user->update(['kyc_status' => KycStatus::REJECTED->value]);

        // Notifier l'utilisateur
        app(NotificationService::class)->notifyKycRejected($kycProfile->user, $reason);
    }

    /**
     * Vérifier si l'utilisateur a un KYC valide
     */
    public function isVerified(User $user): bool
    {
        return $user->kycProfile && $user->kycProfile->isApproved();
    }

    /**
     * Stocker un fichier de manière sécurisée
     */
    protected function storeFile(UploadedFile $file, string $directory): string
    {
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

        return $file->storeAs($directory, $filename, 'private');
    }

    /**
     * Supprimer les anciens fichiers
     */
    protected function deleteFiles(KycProfile $kycProfile): void
    {
        if ($kycProfile->id_photo_path) {
            Storage::disk('private')->delete($kycProfile->id_photo_path);
        }

        if ($kycProfile->selfie_path) {
            Storage::disk('private')->delete($kycProfile->selfie_path);
        }
    }
}
