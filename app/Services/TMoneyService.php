<?php

namespace App\Services;

use App\Contracts\PaymentProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TMoneyService implements PaymentProviderInterface
{
    private string $apiUrl;
    private string $apiKey;
    private string $apiSecret;

    public function __construct()
    {
        $this->apiUrl = config('payxora.payment_providers.tmoney.api_url', '');
        $this->apiKey = config('payxora.payment_providers.tmoney.api_key', '');
        $this->apiSecret = config('payxora.payment_providers.tmoney.api_secret', '');
    }

    public function initiatePayment(float $amount, string $currency, string $phoneNumber, string $description): array
    {
        if (empty($this->apiUrl) || empty($this->apiKey)) {
            Log::error('TMoney API not configured');
            return [
                'success' => false,
                'message' => 'TMoney non configure',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/payments', [
                'amount' => $amount,
                'currency' => $currency,
                'phone_number' => $phoneNumber,
                'description' => $description,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'transaction_id' => $response->json('transaction_id'),
                    'status' => 'pending',
                    'message' => 'Paiement TMoney initie',
                ];
            }

            return [
                'success' => false,
                'message' => $response->json('message', 'Erreur TMoney'),
            ];
        } catch (\Exception $e) {
            Log::error('TMoney payment error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Erreur de connexion TMoney',
            ];
        }
    }

    public function checkStatus(string $transactionId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->apiUrl . '/payments/' . $transactionId);

            return [
                'success' => $response->successful(),
                'status' => $response->json('status', 'unknown'),
                'message' => $response->json('message', ''),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function refund(string $transactionId, float $amount): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/refunds', [
                'transaction_id' => $transactionId,
                'amount' => $amount,
            ]);

            return [
                'success' => $response->successful(),
                'status' => 'refunded',
                'message' => 'Remboursement effectue',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function validateCallback(array $payload): bool
    {
        // Verifier la signature du webhook
        $signature = $payload['signature'] ?? '';
        $expected = hash_hmac('sha256', json_encode($payload), $this->apiSecret);
        return hash_equals($expected, $signature);
    }
}
