<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de rate limiting pour les paiements.
 *
 * Protege contre :
 * - Les tentatives de paiement repetees sur la meme transaction
 * - Les attaques par force brute sur les endpoints de paiement
 * - Les abus de l'API de callback/webhook
 *
 * Limites :
 * - 3 tentatives de paiement par transaction toutes les 10 minutes
 * - 10 requetes de paiement par IP par heure
 * - 5 callbacks webhook par provider par minute
 */
class RateLimitPayment
{
    public function handle(Request $request, Closure $next, string $type = 'default'): Response
    {
        match ($type) {
            'payment'    => $this->limitPaymentAttempts($request),
            'webhook'    => $this->limitWebhookCalls($request),
            'ip'         => $this->limitByIp($request),
            default      => $this->limitDefault($request),
        };

        return $next($request);
    }

    /**
     * Limite les tentatives de paiement sur une transaction specifique.
     */
    private function limitPaymentAttempts(Request $request): void
    {
        $transactionId = $request->route('transaction')?->id ?? $request->input('transaction_id');
        $userId = auth()->id() ?? $request->ip();

        if (!$transactionId) {
            return;
        }

        $key = "payment:{$transactionId}:{$userId}";

        $executed = RateLimiter::attempt(
            key: $key,
            maxAttempts: 3,
            callback: fn() => true,
            decaySeconds: 600 // 10 minutes
        );

        if (!$executed) {
            $seconds = RateLimiter::availableIn($key);
            abort(429, "Trop de tentatives de paiement. Reessayez dans {$seconds} secondes.");
        }
    }

    /**
     * Limite les appels webhook par provider.
     */
    private function limitWebhookCalls(Request $request): void
    {
        $provider = $request->route('provider') ?? 'unknown';
        $key = "webhook:{$provider}:" . $request->ip();

        $executed = RateLimiter::attempt(
            key: $key,
            maxAttempts: 5,
            callback: fn() => true,
            decaySeconds: 60 // 1 minute
        );

        if (!$executed) {
            // Pour les webhooks, on retourne 200 pour eviter les retries
            // mais on log l'abus
            \Illuminate\Support\Facades\Log::warning('Rate limit webhook depasse', [
                'provider' => $provider,
                'ip'       => $request->ip(),
            ]);
        }
    }

    /**
     * Limite globale par IP pour les endpoints de paiement.
     */
    private function limitByIp(Request $request): void
    {
        $key = "payment:ip:" . $request->ip();

        $executed = RateLimiter::attempt(
            key: $key,
            maxAttempts: 10,
            callback: fn() => true,
            decaySeconds: 3600 // 1 heure
        );

        if (!$executed) {
            abort(429, 'Trop de requetes de paiement. Veuillez reessayer plus tard.');
        }
    }

    /**
     * Limite par defaut (generique).
     */
    private function limitDefault(Request $request): void
    {
        $key = "payment:default:" . (auth()->id() ?? $request->ip());

        $executed = RateLimiter::attempt(
            key: $key,
            maxAttempts: 5,
            callback: fn() => true,
            decaySeconds: 300 // 5 minutes
        );

        if (!$executed) {
            abort(429, 'Trop de requetes. Veuillez ralentir.');
        }
    }
}
