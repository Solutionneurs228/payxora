<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service d'integration Moov Money (Moov Africa Togo).
 *
 * Implemente PaymentProviderInterface pour etre interchangeable
 * avec TMoney et les futurs providers de cartes bancaires.
 */
class MoovMoneyService implements PaymentProviderInterface
{
    private string $apiUrl;
    private string $apiKey;
    private string $apiSecret;
    private bool $sandbox;

    public function __construct()
    {
        $this->apiUrl    = config('payxora.payment_providers.moov.api_url', '');
        $this->apiKey    = config('payxora.payment_providers.moov.api_key', '');
        $this->apiSecret = config('payxora.payment_providers.moov.api_secret', '');
        $this->sandbox   = config('payxora.payment_providers.moov.sandbox', app()->environment('local', 'testing'));
    }

    public static function getProviderName(): string
    {
        return 'moov';
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiUrl) && !empty($this->apiKey);
    }

    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    public function supportsRefunds(): bool
    {
        return true;
    }

    public function initiatePayment(
        float $amount,
        string $currency,
        string $phoneNumber,
        string $description,
        array $metadata = []
    ): array {
        if (!$this->isConfigured()) {
            Log::error('Moov Money API not configured');
            return [
                'success'            => false,
                'transaction_id'     => null,
                'status'             => 'failed',
                'message'            => 'Moov Money non configure. Veuillez contacter l'administrateur.',
                'provider_reference' => null,
                'raw_response'       => [],
            ];
        }

        $providerReference = 'MV-' . strtoupper(uniqid());

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->post($this->apiUrl . '/payments', [
                'amount'       => $amount,
                'currency'     => $currency,
                'phone_number' => $phoneNumber,
                'description'  => $description,
                'reference'    => $metadata['internal_reference'] ?? $providerReference,
                'callback_url' => $metadata['callback_url'] ?? $this->getWebhookUrl(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success'            => true,
                    'transaction_id'     => $data['transaction_id'] ?? $providerReference,
                    'status'             => $data['status'] ?? 'pending',
                    'message'            => $data['message'] ?? 'Paiement Moov Money initie avec succes.',
                    'provider_reference' => $providerReference,
                    'raw_response'       => $data,
                ];
            }

            return [
                'success'            => false,
                'transaction_id'     => null,
                'status'             => 'failed',
                'message'            => $response->json('message', 'Erreur Moov Money : ' . $response->status()),
                'provider_reference' => null,
                'raw_response'       => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Moov Money payment error', [
                'error'  => $e->getMessage(),
                'amount' => $amount,
                'phone'  => $phoneNumber,
            ]);

            return [
                'success'            => false,
                'transaction_id'     => null,
                'status'             => 'failed',
                'message'            => 'Erreur de connexion Moov Money. Reessayez plus tard.',
                'provider_reference' => null,
                'raw_response'       => ['exception' => $e->getMessage()],
            ];
        }
    }

    public function checkStatus(string $providerTransactionId): array
    {
        if (!$this->isConfigured()) {
            return [
                'success'      => false,
                'status'       => 'unknown',
                'message'      => 'Moov Money non configure',
                'raw_response' => [],
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->apiUrl . '/payments/' . $providerTransactionId);

            $data = $response->json();

            return [
                'success'      => $response->successful(),
                'status'       => $data['status'] ?? 'unknown',
                'message'      => $data['message'] ?? '',
                'raw_response' => $data,
            ];
        } catch (\Exception $e) {
            Log::error('Moov Money check status error', ['error' => $e->getMessage()]);

            return [
                'success'      => false,
                'status'       => 'error',
                'message'      => 'Erreur de connexion Moov Money',
                'raw_response' => ['exception' => $e->getMessage()],
            ];
        }
    }

    public function refund(string $providerTransactionId, float $amount, string $reason = ''): array
    {
        if (!$this->isConfigured()) {
            return [
                'success'      => false,
                'status'       => 'failed',
                'message'      => 'Moov Money non configure',
                'refund_id'    => null,
                'raw_response' => [],
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->post($this->apiUrl . '/refunds', [
                'transaction_id' => $providerTransactionId,
                'amount'         => $amount,
                'reason'         => $reason,
            ]);

            $data = $response->json();

            return [
                'success'      => $response->successful(),
                'status'       => $data['status'] ?? 'refunded',
                'message'      => $data['message'] ?? 'Remboursement Moov Money effectue',
                'refund_id'    => $data['refund_id'] ?? null,
                'raw_response' => $data,
            ];
        } catch (\Exception $e) {
            Log::error('Moov Money refund error', ['error' => $e->getMessage()]);

            return [
                'success'      => false,
                'status'       => 'failed',
                'message'      => 'Erreur de remboursement Moov Money',
                'refund_id'    => null,
                'raw_response' => ['exception' => $e->getMessage()],
            ];
        }
    }

    public function validateWebhook(array $payload, string $signature): bool
    {
        if (empty($this->apiSecret)) {
            Log::warning('Moov Money webhook validation skipped : no secret configured');
            return true;
        }

        $expected = hash_hmac('sha256', json_encode($payload), $this->apiSecret);

        return hash_equals($expected, $signature);
    }

    public function parseWebhookPayload(array $payload): array
    {
        return [
            'provider_reference' => $payload['reference'] ?? $payload['transaction_id'] ?? null,
            'status'             => match ($payload['status'] ?? '') {
                'success', 'completed', 'approved' => 'success',
                'failed', 'rejected', 'declined'   => 'failed',
                'refunded'                         => 'refunded',
                default                            => 'pending',
            },
            'amount'        => (float) ($payload['amount'] ?? 0),
            'currency'      => $payload['currency'] ?? 'XOF',
            'phone_number'  => $payload['phone_number'] ?? $payload['customer_phone'] ?? null,
            'paid_at'       => $payload['paid_at'] ?? $payload['created_at'] ?? now()->toIso8601String(),
            'failure_reason'=> $payload['failure_reason'] ?? $payload['reason'] ?? null,
            'raw_payload'   => $payload,
        ];
    }

    public function getWebhookUrl(): string
    {
        return route('payment.callback', ['provider' => 'moov']);
    }
}
