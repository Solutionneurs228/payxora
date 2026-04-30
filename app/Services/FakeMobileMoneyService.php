<?php

namespace App\Services;

class FakeMobileMoneyService implements MobileMoneyInterface
{
    public function initiatePayment(string $phone, int $amount, string $reference): array
    {
        usleep(500000); // Simule un délai réseau

        return [
            'success' => true,
            'reference' => $reference,
            'provider_reference' => 'FAKE-' . uniqid(),
            'message' => "Paiement de {$amount} FCFA initié depuis {$phone}",
            'status' => 'pending',
        ];
    }

    public function checkStatus(string $reference): array
    {
        return [
            'success' => true,
            'status' => 'success',
            'message' => 'Paiement confirmé',
        ];
    }

    public function refund(string $reference, int $amount): array
    {
        return [
            'success' => true,
            'reference' => $reference,
            'message' => "Remboursement de {$amount} FCFA effectué",
        ];
    }
}