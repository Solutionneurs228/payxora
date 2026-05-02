<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Interface unifiee pour tous les providers de paiement.
 *
 * Chaque provider (TMoney, Moov Money, Stripe, Flutterwave, CinetPay, etc.)
 * doit implementer cette interface pour etre utilisable par PaymentService.
 *
 * Cette interface garantit :
 * - L'interchangeabilite des providers sans modifier le coeur metier
 * - La standardisation des flux : initier → verifier → rembourser → webhook
 * - La compatibilite dev (sandbox) et production
 */
interface PaymentProviderInterface
{
    /**
     * Identifiant unique du provider (utilise en base de donnees et config).
     *
     * Exemples : 'tmoney', 'moov', 'stripe', 'flutterwave', 'cinetpay'
     */
    public static function getProviderName(): string;

    /**
     * Verifie si le provider est correctement configure (cles API, URL, etc.).
     *
     * En environnement local / dev, un provider peut retourner true
     * meme sans vraies cles (mode simulation).
     */
    public function isConfigured(): bool;

    /**
     * Initie un paiement.
     *
     * @param float  $amount      Montant a payer
     * @param string $currency    Devise (XOF, EUR, USD...)
     * @param string $phoneNumber Numero de telephone (pour Mobile Money) ou identifiant client
     * @param string $description Description de la transaction
     * @param array  $metadata    Donnees supplementaires (reference interne, callback URL, etc.)
     *
     * @return array {
     *     @type bool   $success           true si l'initiation a reussi
     *     @type string $transaction_id    ID de transaction chez le provider
     *     @type string $status            'pending', 'processing', 'completed', 'failed'
     *     @type string $message           Message utilisateur
     *     @type string $provider_reference Reference unique chez le provider (pour callbacks)
     *     @type array  $raw_response      Reponse brute du provider (pour debug)
     * }
     */
    public function initiatePayment(
        float $amount,
        string $currency,
        string $phoneNumber,
        string $description,
        array $metadata = []
    ): array;

    /**
     * Verifie le statut d'un paiement chez le provider.
     *
     * @param string $providerTransactionId ID de transaction chez le provider
     *
     * @return array {
     *     @type bool   $success
     *     @type string $status   'pending', 'completed', 'failed', 'refunded', 'unknown'
     *     @type string $message
     *     @type array  $raw_response
     * }
     */
    public function checkStatus(string $providerTransactionId): array;

    /**
     * Effectue un remboursement partiel ou total.
     *
     * @param string $providerTransactionId ID de transaction chez le provider
     * @param float  $amount                Montant a rembourser (<= montant initial)
     * @param string $reason                Raison du remboursement
     *
     * @return array {
     *     @type bool   $success
     *     @type string $status
     *     @type string $message
     *     @type string $refund_id   ID du remboursement chez le provider
     *     @type array  $raw_response
     * }
     */
    public function refund(
        string $providerTransactionId,
        float $amount,
        string $reason = ''
    ): array;

    /**
     * Valide la signature d'un webhook/callback pour securiser les notifications.
     *
     * Chaque provider a sa propre methode de signature (HMAC, JWT, etc.).
     *
     * @param array  $payload   Donnees recues du webhook
     * @param string $signature Signature fournie dans les headers
     *
     * @return bool true si la signature est valide
     */
    public function validateWebhook(array $payload, string $signature): bool;

    /**
     * Extrait et normalise les donnees d'un webhook.
     *
     * Transforme la reponse specifique du provider en format standardise
     * pour que PaymentService puisse traiter tous les webhooks de la meme maniere.
     *
     * @param array $payload Donnees brutes du webhook
     *
     * @return array {
     *     @type string $provider_reference Reference du paiement chez le provider
     *     @type string $status              'success', 'failed', 'refunded'
     *     @type float  $amount              Montant confirme
     *     @type string $currency            Devise
     *     @type string $phone_number        Numero client (si disponible)
     *     @type string $paid_at             Date de paiement (format ISO 8601)
     *     @type string $failure_reason      Raison d'echec (si status = failed)
     *     @type array  $raw_payload         Donnees brutes completes
     * }
     */
    public function parseWebhookPayload(array $payload): array;

    /**
     * Retourne l'URL de callback/webhook a configurer chez le provider.
     *
     * Utile pour l'administration et la documentation.
     */
    public function getWebhookUrl(): string;

    /**
     * Indique si le provider supporte les remboursements.
     */
    public function supportsRefunds(): bool;

    /**
     * Indique si le provider est en mode simulation (dev/local).
     */
    public function isSandbox(): bool;
}
