<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller pour recevoir et traiter les webhooks des providers de paiement.
 *
 * Chaque provider (TMoney, Moov Money, Stripe, Flutterwave, CinetPay)
 * envoie des notifications HTTP quand le statut d'un paiement change.
 * Ce controller valide, parse et delegue le traitement a PaymentService.
 *
 * Securite :
 * - Validation de la signature webhook (HMAC)
 * - Verification de l'IP source (optionnel, a configurer)
 * - Rejet des payloads invalides
 * - Reponse 200 rapide pour eviter les retries abusifs
 *
 * Routes :
 *   POST /webhook/payment/{provider}  → TMoney, Moov, Card
 */
class WebhookController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Point d'entree unique pour tous les webhooks de paiement.
     *
     * @param Request $request
     * @param string  $provider Nom du provider (tmoney, moov, card, stripe, flutterwave)
     */
    public function handlePayment(Request $request, string $provider): JsonResponse
    {
        $payload = $request->all();
        $signature = $this->extractSignature($request);

        Log::info('WebhookController : webhook recu', [
            'provider'  => $provider,
            'ip'        => $request->ip(),
            'payload'   => $payload,
        ]);

        try {
            $success = $this->paymentService->handleCallback($provider, $payload, $signature);

            if ($success) {
                return response()->json([
                    'status'  => 'ok',
                    'message' => 'Webhook traite avec succes',
                ]);
            }

            // Echec du traitement (paiement inconnu, signature invalide, etc.)
            // On retourne quand meme 200 pour eviter les retries du provider
            return response()->json([
                'status'  => 'ignored',
                'message' => 'Webhook ignore (paiement inconnu ou invalide)',
            ]);
        } catch (\Exception $e) {
            Log::error('WebhookController : erreur traitement webhook', [
                'provider' => $provider,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            // Retourner 200 meme en cas d'erreur interne
            // Les providers retry souvent sur 4xx/5xx, ce qui peut causer des doublons
            return response()->json([
                'status'  => 'error',
                'message' => 'Erreur interne, webhook enregistre pour traitement manuel',
            ]);
        }
    }

    /**
     * Webhook specifique pour Stripe (format particulier).
     *
     * Stripe envoie un header 'Stripe-Signature' au lieu d'un simple HMAC.
     * Cette methode pourra etre utilisee quand Stripe sera integre.
     */
    public function handleStripe(Request $request): JsonResponse
    {
        $payload = $request->all();
        $signature = $request->header('Stripe-Signature', '');

        Log::info('WebhookController : webhook Stripe recu', [
            'type'      => $payload['type'] ?? 'unknown',
            'id'        => $payload['id'] ?? null,
        ]);

        return $this->handlePayment($request, 'stripe');
    }

    /**
     * Webhook specifique pour Flutterwave.
     */
    public function handleFlutterwave(Request $request): JsonResponse
    {
        $signature = $request->header('verif-hash', '');

        Log::info('WebhookController : webhook Flutterwave recu');

        return $this->handlePayment($request, 'flutterwave');
    }

    /**
     * Extrait la signature du webhook depuis la requete.
     *
     * Chaque provider utilise un header different :
     * - TMoney/Moov : X-Webhook-Signature
     * - Stripe : Stripe-Signature
     * - Flutterwave : verif-hash
     * - CinetPay : cpm_signature
     */
    private function extractSignature(Request $request): string
    {
        return $request->header('X-Webhook-Signature')
            ?? $request->header('Stripe-Signature')
            ?? $request->header('verif-hash')
            ?? $request->header('cpm_signature')
            ?? '';
    }
}
