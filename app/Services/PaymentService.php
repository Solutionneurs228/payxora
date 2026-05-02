<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentProviderInterface;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service d'orchestration des paiements.
 *
 * Ce service est le point d'entree unique pour tous les paiements.
 * Il delegue les appels API aux providers via PaymentProviderFactory.
 *
 * NOTE : Ce service N'INJECTE PAS EscrowService pour eviter
 * les dependances circulaires. Les appels a l'escrow sont faits
 * par le controller ou via app() en dernier recours.
 */
class PaymentService
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Initie un paiement pour une transaction.
     */
    public function initiate(
        Transaction $transaction,
        string $method,
        string $phoneNumber,
        ?string $subProvider = null,
        array $metadata = []
    ): array {
        if (!$transaction->isPendingPayment()) {
            return [
                'success' => false,
                'message' => 'Cette transaction ne peut plus etre payee.',
            ];
        }

        // Verrouiller pour eviter les paiements doubles
        $existingPayment = Payment::where('transaction_id', $transaction->id)
            ->whereIn('status', [PaymentStatus::PENDING, PaymentStatus::COMPLETED])
            ->first();

        if ($existingPayment) {
            return [
                'success' => false,
                'message' => 'Un paiement est deja en cours pour cette transaction.',
            ];
        }

        // Instancier le provider via la factory
        try {
            $provider = PaymentProviderFactory::forMethod($method, $subProvider);
        } catch (\InvalidArgumentException $e) {
            Log::error('PaymentService : provider non trouve', [
                'method'       => $method,
                'sub_provider' => $subProvider,
                'error'        => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Methode de paiement non disponible.',
            ];
        }

        // Creer l'enregistrement Payment en DB
        $payment = Payment::create([
            'transaction_id'     => $transaction->id,
            'method'             => $method,
            'provider'           => $provider::getProviderName(),
            'phone_number'       => $phoneNumber,
            'amount'             => $transaction->amount,
            'currency'           => $transaction->currency,
            'status'             => PaymentStatus::PENDING,
            'reference'          => 'PAY-' . strtoupper(uniqid()),
        ]);

        // Initier le paiement chez le provider
        $result = $provider->initiatePayment(
            $transaction->amount,
            $transaction->currency,
            $phoneNumber,
            "PayXora - {$transaction->product_name}",
            array_merge($metadata, [
                'internal_reference' => $payment->reference,
                'callback_url'       => $provider->getWebhookUrl(),
            ])
        );

        // Mettre a jour le Payment avec la reponse du provider
        $payment->update([
            'provider_reference' => $result['provider_reference'] ?? null,
            'provider_response'  => $result['raw_response'] ?? null,
        ]);

        if ($result['success']) {
            // Si le provider retourne immediatement un succes (simulation)
            if ($result['status'] === 'completed') {
                $this->confirmPayment($payment, $result);
            }

            return [
                'success'            => true,
                'message'            => $result['message'],
                'provider_reference' => $result['provider_reference'],
                'payment_id'         => $payment->id,
            ];
        }

        // Echec de l'initiation
        $payment->update([
            'status'         => PaymentStatus::FAILED,
            'failure_reason' => $result['message'] ?? 'Erreur inconnue',
        ]);

        return [
            'success' => false,
            'message' => $result['message'] ?? 'Erreur lors de l\'initiation du paiement.',
        ];
    }

    /**
     * Traite le callback/webhook d'un provider de paiement.
     */
    public function handleCallback(string $providerName, array $payload, string $signature = ''): bool
    {
        Log::info('PaymentService : webhook recu', [
            'provider' => $providerName,
            'payload'  => $payload,
        ]);

        try {
            $provider = PaymentProviderFactory::make($providerName);
        } catch (\InvalidArgumentException $e) {
            Log::error('PaymentService : provider webhook inconnu', [
                'provider' => $providerName,
            ]);
            return false;
        }

        // Valider la signature du webhook
        if (!$provider->validateWebhook($payload, $signature)) {
            Log::warning('PaymentService : signature webhook invalide', [
                'provider' => $providerName,
            ]);
            return false;
        }

        // Parser et normaliser le payload
        $parsed = $provider->parseWebhookPayload($payload);
        $providerReference = $parsed['provider_reference'];

        if (empty($providerReference)) {
            Log::warning('PaymentService : reference provider manquante dans le webhook');
            return false;
        }

        // Trouver le Payment correspondant
        $payment = Payment::where('provider_reference', $providerReference)->first();

        if (!$payment) {
            Log::warning('PaymentService : paiement inconnu pour le webhook', [
                'provider_reference' => $providerReference,
            ]);
            return false;
        }

        // Traiter selon le statut
        match ($parsed['status']) {
            'success'  => $this->confirmPayment($payment, $parsed),
            'failed'   => $this->failPayment($payment, $parsed),
            'refunded' => $this->processRefundWebhook($payment, $parsed),
            default    => Log::info('PaymentService : statut webhook ignore', ['status' => $parsed['status']]),
        };

        return true;
    }

    /**
     * Confirme un paiement et met a jour le statut.
     * NOTE : Le fondage de l'escrow est delegue au controller
     * pour eviter la dependance circulaire.
     */
    private function confirmPayment(Payment $payment, array $data): void
    {
        $payment->update([
            'status'         => PaymentStatus::COMPLETED,
            'paid_at'        => $data['paid_at'] ?? now(),
            'failure_reason' => null,
        ]);

        Log::info('PaymentService : paiement confirme', [
            'payment_id'         => $payment->id,
            'provider_reference' => $payment->provider_reference,
        ]);
    }

    /**
     * Marque un paiement comme echoue.
     */
    private function failPayment(Payment $payment, array $data): void
    {
        $payment->update([
            'status'         => PaymentStatus::FAILED,
            'failure_reason' => $data['failure_reason'] ?? 'Echec du paiement',
        ]);

        Log::warning('PaymentService : paiement echoue', [
            'payment_id'         => $payment->id,
            'provider_reference' => $payment->provider_reference,
            'reason'             => $data['failure_reason'] ?? 'Inconnue',
        ]);
    }

    /**
     * Effectue un remboursement pour une transaction.
     *
     * Cette methode est appelee par le controller, pas par EscrowService
     * directement, pour eviter les dependances circulaires.
     */
    public function refund(Transaction $transaction, ?float $amount = null, string $reason = ''): array
    {
        if (!in_array($transaction->status->value, ['funded', 'shipped', 'delivered', 'disputed'])) {
            return [
                'success' => false,
                'message' => 'Cette transaction ne peut pas etre remboursee.',
            ];
        }

        $payment = $transaction->payment;

        if (!$payment || !$payment->isCompleted()) {
            return [
                'success' => false,
                'message' => 'Aucun paiement valide trouve pour cette transaction.',
            ];
        }

        $refundAmount = $amount ?? $transaction->amount;

        if ($refundAmount > $transaction->amount) {
            return [
                'success' => false,
                'message' => 'Le montant du remboursement ne peut pas depasser le montant initial.',
            ];
        }

        try {
            $provider = PaymentProviderFactory::make($payment->provider);
        } catch (\InvalidArgumentException $e) {
            return [
                'success' => false,
                'message' => 'Provider de paiement non trouve pour le remboursement.',
            ];
        }

        if (!$provider->supportsRefunds()) {
            return [
                'success' => false,
                'message' => 'Ce mode de paiement ne supporte pas les remboursements.',
            ];
        }

        $result = $provider->refund(
            $payment->provider_reference,
            $refundAmount,
            $reason
        );

        if ($result['success']) {
            $payment->update([
                'status'      => PaymentStatus::REFUNDED,
                'refunded_at' => now(),
            ]);

            Log::info('PaymentService : remboursement effectue', [
                'payment_id'   => $payment->id,
                'amount'       => $refundAmount,
                'reason'       => $reason,
                'refund_id'    => $result['refund_id'] ?? null,
            ]);

            return [
                'success' => true,
                'message' => 'Remboursement effectue avec succes.',
                'refund_id' => $result['refund_id'] ?? null,
            ];
        }

        Log::error('PaymentService : echec du remboursement', [
            'payment_id' => $payment->id,
            'error'      => $result['message'] ?? 'Inconnue',
        ]);

        return [
            'success' => false,
            'message' => $result['message'] ?? 'Erreur lors du remboursement.',
        ];
    }

    /**
     * Traite un webhook de remboursement.
     */
    private function processRefundWebhook(Payment $payment, array $data): void
    {
        $payment->update([
            'status'      => PaymentStatus::REFUNDED,
            'refunded_at' => $data['paid_at'] ?? now(),
        ]);

        Log::info('PaymentService : remboursement confirme par webhook', [
            'payment_id'         => $payment->id,
            'provider_reference' => $payment->provider_reference,
        ]);
    }

    /**
     * Verifie le statut d'un paiement chez le provider.
     */
    public function checkStatus(Payment $payment): PaymentStatus
    {
        try {
            $provider = PaymentProviderFactory::make($payment->provider);
        } catch (\InvalidArgumentException $e) {
            return $payment->status;
        }

        $result = $provider->checkStatus($payment->provider_reference);

        if ($result['success'] && isset($result['status'])) {
            $status = match ($result['status']) {
                'completed', 'success', 'approved' => PaymentStatus::COMPLETED,
                'failed', 'rejected', 'declined'   => PaymentStatus::FAILED,
                'refunded'                         => PaymentStatus::REFUNDED,
                default                            => $payment->status,
            };

            if ($status !== $payment->status) {
                $payment->update(['status' => $status]);
            }

            return $status;
        }

        return $payment->status;
    }

    /**
     * Retourne les providers de paiement disponibles pour l'utilisateur.
     */
    public function getAvailableMethods(): array
    {
        $methods = [];

        // Mobile Money
        $mmProviders = [];
        if (app(TMoneyService::class)->isConfigured()) {
            $mmProviders[] = [
                'id'      => 'tmoney',
                'name'    => 'TMoney',
                'logo'    => '/images/payments/tmoney.svg',
                'sandbox' => app(TMoneyService::class)->isSandbox(),
            ];
        }
        if (app(MoovMoneyService::class)->isConfigured()) {
            $mmProviders[] = [
                'id'      => 'moov',
                'name'    => 'Moov Money',
                'logo'    => '/images/payments/moov.svg',
                'sandbox' => app(MoovMoneyService::class)->isSandbox(),
            ];
        }

        if (!empty($mmProviders) || app()->environment('local', 'testing')) {
            $methods[] = [
                'type'      => 'mobile_money',
                'name'      => 'Mobile Money',
                'providers' => $mmProviders,
            ];
        }

        // Carte bancaire
        $cardService = app(CardPaymentService::class);
        if ($cardService->isConfigured() || app()->environment('local', 'testing')) {
            $methods[] = [
                'type'      => 'card',
                'name'      => 'Carte Bancaire',
                'providers' => [
                    [
                        'id'      => 'card',
                        'name'    => 'Visa / Mastercard',
                        'logo'    => '/images/payments/card.svg',
                        'sandbox' => $cardService->isSandbox(),
                    ],
                ],
            ];
        }

        return $methods;
    }
}
