<?php

namespace App\Contracts;

interface PaymentProviderInterface
{
    /**
     * Initier un paiement
     */
    public function initiatePayment(float $amount, string $phoneNumber, string $reference): array;

    /**
     * Vérifier le statut d'un paiement
     */
    public function checkStatus(string $transactionId): array;

    /**
     * Effectuer un remboursement
     */
    public function refund(string $transactionId, float $amount): array;

    /**
     * Valider la signature du callback
     */
    public function verifyCallbackSignature(array $payload, string $signature): bool;

    /**
     * Nom du provider
     */
    public function getName(): string;

    /**
     * Vérifier si le provider est disponible
     */
    public function isAvailable(): bool;
}
