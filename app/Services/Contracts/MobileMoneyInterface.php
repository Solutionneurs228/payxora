<?php

namespace App\Services\Contracts;

interface MobileMoneyInterface
{
    /**
     * Initie un paiement mobile money.
     */
    public function initiatePayment(float $amount, string $phoneNumber, string $reference): array;

    /**
     * Verifie le statut d'un paiement.
     */
    public function checkStatus(string $transactionId): array;

    /**
     * Effectue un remboursement.
     */
    public function refund(string $transactionId, float $amount): array;
}
