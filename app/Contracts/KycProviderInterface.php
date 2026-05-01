<?php

namespace App\Contracts;

use App\Models\KycProfile;

interface KycProviderInterface
{
    /**
     * Vérifier la validité d'un document d'identité
     */
    public function verifyDocument(string $idNumber, string $idType): array;

    /**
     * Vérifier la correspondance selfie/document
     */
    public function verifyFaceMatch(string $idPhotoPath, string $selfiePath): array;

    /**
     * Vérifier si le provider est disponible
     */
    public function isAvailable(): bool;
}
