<?php

namespace App\Contracts;

interface KycProviderInterface
{
    /**
     * Verifier un document d'identite
     */
    public function verifyDocument(string $documentPath, string $documentType): array;

    /**
     * Verifier un selfie / liveness
     */
    public function verifySelfie(string $selfiePath, string $documentPath): array;

    /**
     * Verifier un numero de telephone
     */
    public function verifyPhone(string $phoneNumber, string $otp): array;

    /**
     * Generer un OTP
     */
    public function generateOtp(string $phoneNumber): array;
}
