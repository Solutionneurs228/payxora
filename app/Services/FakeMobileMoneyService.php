<?php

namespace App\Services;

use App\Contracts\PaymentProviderInterface;
use Illuminate\Support\Facades\Log;

class FakeMobileMoneyService implements PaymentProviderInterface
{
    public function initiatePayment(float $amount, string $currency, string $phoneNumber, string $description): array
    {
        Log::info('Fake MNO payment initiated', [
            'amount' => $amount,
            'currency' => $currency,
            'phone' => $phoneNumber,
        ]);

        // Simulation d'un delai reseau
        usleep(500000); // 500ms

        return [
            'success' => true,
            'transaction_id' => 'FAKE-' . strtoupper(uniqid()),
            'status' => 'pending',
            'message' => 'Paiement simule avec succes',
        ];
    }

    public function checkStatus(string $transactionId): array
    {
        return [
            'success' => true,
            'status' => 'completed',
            'message' => 'Paiement confirme',
        ];
    }

    public function refund(string $transactionId, float $amount): array
    {
        Log::info('Fake MNO refund', ['transaction_id' => $transactionId, 'amount' => $amount]);

        return [
            'success' => true,
            'status' => 'refunded',
            'message' => 'Remboursement simule avec succes',
        ];
    }

    public function validateCallback(array $payload): bool
    {
        return true;
    }
}
