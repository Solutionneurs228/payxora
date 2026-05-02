<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentProviderInterface;
use Illuminate\Support\Facades\Log;

/**
 * Service de simulation Mobile Money pour le developpement local.
 *
 * Implemente PaymentProviderInterface pour etre interchangeable
 * avec TMoneyService et MoovMoneyService en production.
 *
 * Ce service simule TOUJOURS le succes en environnement local/testing,
 * sans appeler d'API externe. Utile pour :
 * - Tester le workflow escrow sans cles API reelles
 * - Developer sans connexion internet
 * - Executer des tests automatisés
 *
 * IMPORTANT : Ne JAMAIS utiliser en production.
 */
class FakeMobileMoneyService implements PaymentProviderInterface
{
    public static function getProviderName(): string
    {
        return 'fake_mobile_money';
    }

    public function isConfigured(): bool
    {
        return true; // Toujours configure en dev
    }

    public function isSandbox(): bool
    {
        return true; // Toujours en mode simulation
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
        $providerReference = 'FAKE-' . strtoupper(uniqid());

        Log::info('FakeMobileMoney : paiement simule', [
            'amount'      => $amount,
            'currency'    => $currency,
            'phone'       => $phoneNumber,
            'description' => $description,
            'reference'   => $providerReference,
        ]);

        // Simulation d'un delai reseau (100-500ms)
        usleep(random_int(100000, 500000));

        return [
            'success'            => true,
            'transaction_id'     => $providerReference,
            'status'             => 'pending',
            'message'            => 'Paiement Mobile Money simule avec succes (mode dev).',
            'provider_reference' => $providerReference,
            'raw_response'       => [
                'simulated'   => true,
                'provider'    => 'fake',
                'amount'      => $amount,
                'currency'    => $currency,
                'phone'       => $phoneNumber,
                'description' => $description,
            ],
        ];
    }

    public function checkStatus(string $providerTransactionId): array
    {
        Log::info('FakeMobileMoney : verification statut simulee', [
            'transaction_id' => $providerTransactionId,
        ]);

        // Simulation : apres verification, on considere que c'est reussi
        return [
            'success'      => true,
            'status'       => 'completed',
            'message'      => 'Paiement confirme (simulation)',
            'raw_response' => [
                'simulated'      => true,
                'transaction_id' => $providerTransactionId,
                'status'         => 'completed',
            ],
        ];
    }

    public function refund(string $providerTransactionId, float $amount, string $reason = ''): array
    {
        Log::info('FakeMobileMoney : remboursement simule', [
            'transaction_id' => $providerTransactionId,
            'amount'         => $amount,
            'reason'         => $reason,
        ]);

        return [
            'success'      => true,
            'status'       => 'refunded',
            'message'      => 'Remboursement simule avec succes (mode dev).',
            'refund_id'    => 'REFUND-' . strtoupper(uniqid()),
            'raw_response' => [
                'simulated'      => true,
                'transaction_id' => $providerTransactionId,
                'amount'         => $amount,
                'reason'         => $reason,
            ],
        ];
    }

    public function validateWebhook(array $payload, string $signature): bool
    {
        // En mode simulation, on accepte tous les webhooks
        Log::info('FakeMobileMoney : webhook valide (simulation)');
        return true;
    }

    public function parseWebhookPayload(array $payload): array
    {
        Log::info('FakeMobileMoney : parsing webhook simule', ['payload' => $payload]);

        return [
            'provider_reference' => $payload['reference'] ?? $payload['transaction_id'] ?? 'UNKNOWN',
            'status'             => $payload['status'] ?? 'success',
            'amount'             => (float) ($payload['amount'] ?? 0),
            'currency'           => $payload['currency'] ?? 'XOF',
            'phone_number'       => $payload['phone_number'] ?? null,
            'paid_at'            => $payload['paid_at'] ?? now()->toIso8601String(),
            'failure_reason'     => $payload['failure_reason'] ?? null,
            'raw_payload'        => $payload,
        ];
    }

    public function getWebhookUrl(): string
    {
        return route('payment.callback', ['provider' => 'fake']);
    }
}
