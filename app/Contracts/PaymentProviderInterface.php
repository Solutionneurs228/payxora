<?php

namespace App\Contracts;

interface PaymentProviderInterface
{
    /**
     * Initier un paiement
     */
    public function initiatePayment(float $amount, string $currency, string $phoneNumber, string $description): array;

    /**
     * Verifier le statut d'un paiement
     */
    public function checkStatus(string $transactionId): array;

    /**
     * Effectuer un remboursement
     */
    public function refund(string $transactionId, float $amount): array;

    /**
     * Valider un callback webhook
     */
    public function validateCallback(array $payload): bool;
}
