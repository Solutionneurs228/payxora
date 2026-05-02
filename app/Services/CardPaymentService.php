<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentProviderInterface;
use Illuminate\Support\Facades\Log;

/**
 * Service de paiement par carte bancaire.
 *
 * Architecture preparee pour l'integration de multiples providers :
 * - Stripe (international)
 * - Flutterwave (Afrique)
 * - CinetPay (Afrique de l'Ouest)
 * - PayDunya (Afrique de l'Ouest)
 * - Providers bancaires locaux
 *
 * En environnement local / dev :
 * - Mode simulation automatique si aucune cle API n'est configuree
 * - Utilise des cartes de test standard (4242 4242 4242 4242 pour Stripe)
 * - Simule les webhooks sans appel externe
 *
 * En production :
 * - Le provider actif est determine par la configuration 'payxora.card_provider'
 * - Chaque provider a sa propre logique d'API
 */
class CardPaymentService implements PaymentProviderInterface
{
    private string $provider;
    private string $apiKey;
    private string $apiSecret;
    private string $webhookSecret;
    private bool $sandbox;

    public function __construct()
    {
        $this->provider      = config('payxora.payment_providers.card.provider', 'stripe');
        $this->apiKey        = config('payxora.payment_providers.card.api_key', '');
        $this->apiSecret     = config('payxora.payment_providers.card.api_secret', '');
        $this->webhookSecret = config('payxora.payment_providers.card.webhook_secret', '');
        $this->sandbox       = config('payxora.payment_providers.card.sandbox', app()->environment('local', 'testing'));
    }

    public static function getProviderName(): string
    {
        return 'card';
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    public function supportsRefunds(): bool
    {
        return true;
    }

    /**
     * Initie un paiement par carte bancaire.
     *
     * En mode simulation (dev) :
     * - Retourne immediatement un succes
     * - Genere une reference simulee
     * - Le paiement est considere comme "pending" jusqu'au webhook
     *
     * En production :
     * - Cree une intention de paiement (PaymentIntent) chez le provider
     * - Retourne le client_secret pour le frontend (Stripe.js, Flutterwave inline, etc.)
     */
    public function initiatePayment(
        float $amount,
        string $currency,
        string $phoneNumber,
        string $description,
        array $metadata = []
    ): array {
        // Mode simulation automatique en dev si non configure
        if (!$this->isConfigured() || $this->isSandbox()) {
            return $this->simulatePayment($amount, $currency, $description, $metadata);
        }

        $providerReference = 'CARD-' . strtoupper(uniqid());

        try {
            // TODO : Implementer l'appel API reel selon le provider actif
            // Exemple pour Stripe :
            // $response = Http::withBasicAuth($this->apiKey, '')
            //     ->post('https://api.stripe.com/v1/payment_intents', [...]);
            //
            // Exemple pour Flutterwave :
            // $response = Http::withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
            //     ->post('https://api.flutterwave.com/v3/payments', [...]);

            Log::info('Card payment initiated', [
                'provider' => $this->provider,
                'amount'   => $amount,
                'currency' => $currency,
            ]);

            return [
                'success'            => true,
                'transaction_id'     => $providerReference,
                'status'             => 'pending',
                'message'            => 'Paiement par carte initie. Veuillez completer la transaction.',
                'provider_reference' => $providerReference,
                'client_secret'      => null, // A remplir quand l'API reelle sera integree
                'payment_url'        => null, // URL de paiement pour redirect (Flutterwave/CinetPay)
                'raw_response'       => [
                    'provider' => $this->provider,
                    'mode'     => 'production',
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Card payment error', [
                'provider' => $this->provider,
                'error'    => $e->getMessage(),
            ]);

            return [
                'success'            => false,
                'transaction_id'     => null,
                'status'             => 'failed',
                'message'            => 'Erreur lors de l'initiation du paiement par carte.',
                'provider_reference' => null,
                'raw_response'       => ['exception' => $e->getMessage()],
            ];
        }
    }

    /**
     * Simule un paiement par carte pour le developpement.
     */
    private function simulatePayment(float $amount, string $currency, string $description, array $metadata): array
    {
        $providerReference = 'CARD-FAKE-' . strtoupper(uniqid());

        Log::info('CardPaymentService : paiement carte simule', [
            'amount'      => $amount,
            'currency'    => $currency,
            'description' => $description,
            'reference'   => $providerReference,
        ]);

        usleep(random_int(100000, 500000));

        return [
            'success'            => true,
            'transaction_id'     => $providerReference,
            'status'             => 'pending',
            'message'            => 'Paiement par carte simule (mode dev). Utilisez une carte de test.',
            'provider_reference' => $providerReference,
            'client_secret'      => 'fake_secret_' . uniqid(),
            'payment_url'        => null,
            'raw_response'       => [
                'simulated'   => true,
                'provider'    => $this->provider,
                'amount'      => $amount,
                'currency'    => $currency,
                'description' => $description,
            ],
        ];
    }

    public function checkStatus(string $providerTransactionId): array
    {
        if ($this->isSandbox()) {
            return [
                'success'      => true,
                'status'       => 'completed',
                'message'      => 'Paiement par carte confirme (simulation)',
                'raw_response' => ['simulated' => true],
            ];
        }

        // TODO : Implementer l'appel API reel
        return [
            'success'      => true,
            'status'       => 'pending',
            'message'      => 'Statut en attente de confirmation',
            'raw_response' => [],
        ];
    }

    public function refund(string $providerTransactionId, float $amount, string $reason = ''): array
    {
        if ($this->isSandbox()) {
            Log::info('CardPaymentService : remboursement simule', [
                'transaction_id' => $providerTransactionId,
                'amount'         => $amount,
                'reason'         => $reason,
            ]);

            return [
                'success'      => true,
                'status'       => 'refunded',
                'message'      => 'Remboursement par carte simule avec succes.',
                'refund_id'    => 'REFUND-CARD-' . strtoupper(uniqid()),
                'raw_response' => ['simulated' => true],
            ];
        }

        // TODO : Implementer l'appel API reel
        try {
            Log::info('Card refund initiated', [
                'provider'         => $this->provider,
                'transaction_id'   => $providerTransactionId,
                'amount'           => $amount,
            ]);

            return [
                'success'      => true,
                'status'       => 'refunded',
                'message'      => 'Remboursement en cours de traitement.',
                'refund_id'    => 'REFUND-' . strtoupper(uniqid()),
                'raw_response' => [],
            ];
        } catch (\Exception $e) {
            return [
                'success'      => false,
                'status'       => 'failed',
                'message'      => 'Erreur de remboursement par carte.',
                'refund_id'    => null,
                'raw_response' => ['exception' => $e->getMessage()],
            ];
        }
    }

    public function validateWebhook(array $payload, string $signature): bool
    {
        if ($this->isSandbox()) {
            return true;
        }

        if (empty($this->webhookSecret)) {
            Log::warning('Card webhook validation skipped : no webhook secret configured');
            return true;
        }

        // Stripe : signature = Stripe-Signature header (timestamp + signatures)
        // Flutterwave : signature = verif-hash header
        // CinetPay : signature = cpm_trans_id + apiKey
        // TODO : Adapter selon le provider actif

        $expected = hash_hmac('sha256', json_encode($payload), $this->webhookSecret);
        return hash_equals($expected, $signature);
    }

    public function parseWebhookPayload(array $payload): array
    {
        // Normalisation des payloads selon le provider
        // Stripe : type = 'payment_intent.succeeded', 'payment_intent.payment_failed'
        // Flutterwave : status = 'successful', 'failed'
        // CinetPay : cpm_result = '00' (succes), autre = echec

        $status = match ($payload['type'] ?? $payload['status'] ?? $payload['cpm_result'] ?? '') {
            'payment_intent.succeeded', 'successful', '00', 'completed', 'approved' => 'success',
            'payment_intent.payment_failed', 'failed', 'rejected', 'declined'       => 'failed',
            'charge.refunded', 'refunded'                                             => 'refunded',
            default                                                                  => 'pending',
        };

        return [
            'provider_reference' => $payload['data']['object']['id']
                ?? $payload['transaction_id']
                ?? $payload['cpm_trans_id']
                ?? $payload['reference']
                ?? null,
            'status'          => $status,
            'amount'          => (float) (
                $payload['data']['object']['amount'] ??
                $payload['amount'] ??
                $payload['cpm_amount'] ??
                0
            ) / 100, // Stripe envoie les montants en centimes
            'currency'        => $payload['data']['object']['currency']
                ?? $payload['currency']
                ?? 'XOF',
            'phone_number'    => $payload['customer_phone'] ?? $payload['phone_number'] ?? null,
            'paid_at'         => $payload['created_at'] ?? $payload['paid_at'] ?? now()->toIso8601String(),
            'failure_reason'  => $payload['data']['object']['last_payment_error']['message']
                ?? $payload['failure_reason']
                ?? null,
            'raw_payload'     => $payload,
        ];
    }

    public function getWebhookUrl(): string
    {
        return route('payment.callback', ['provider' => 'card']);
    }

    /**
     * Retourne la liste des cartes de test recommandees pour le dev.
     *
     * @return array<string, string>
     */
    public static function getTestCards(): array
    {
        return [
            'Visa (succes)'            => '4242 4242 4242 4242',
            'Visa (decline)'           => '4000 0000 0000 0002',
            'Mastercard (succes)'      => '5555 5555 5555 4444',
            'Mastercard (decline)'     => '5105 1051 0510 5100',
            'AMEX (succes)'            => '3782 822463 10005',
            '3D Secure (succes)'       => '4000 0025 0000 3155',
            '3D Secure (authentifier)' => '4000 0027 6000 3184',
        ];
    }
}
