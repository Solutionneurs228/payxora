<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitPayment
{
    public function handle(Request $request, Closure $next, string $type = 'default'): Response
    {
        match ($type) {
            'payment' => $this->limitPaymentAttempts($request),
            'webhook' => $this->limitWebhookCalls($request),
            'ip' => $this->limitByIp($request),
            default => $this->limitDefault($request),
        };

        return $next($request);
    }

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
            decaySeconds: 600
        );

        if (!$executed) {
            $seconds = RateLimiter::availableIn($key);
            abort(429, "Trop de tentatives de paiement. Reessayez dans {$seconds} secondes.");
        }
    }

    private function limitWebhookCalls(Request $request): void
    {
        $provider = $request->route('provider') ?? 'unknown';
        $key = "webhook:{$provider}:" . $request->ip();

        $executed = RateLimiter::attempt(
            key: $key,
            maxAttempts: 5,
            callback: fn() => true,
            decaySeconds: 60
        );

        if (!$executed) {
            \Illuminate\Support\Facades\Log::warning('Rate limit webhook depasse', [
                'provider' => $provider,
                'ip' => $request->ip(),
            ]);
        }
    }

    private function limitByIp(Request $request): void
    {
        $key = "payment:ip:" . $request->ip();

        $executed = RateLimiter::attempt(
            key: $key,
            maxAttempts: 10,
            callback: fn() => true,
            decaySeconds: 3600
        );

        if (!$executed) {
            abort(429, 'Trop de requetes de paiement. Veuillez reessayer plus tard.');
        }
    }

    private function limitDefault(Request $request): void
    {
        $key = "payment:default:" . (auth()->id() ?? $request->ip());

        $executed = RateLimiter::attempt(
            key: $key,
            maxAttempts: 5,
            callback: fn() => true,
            decaySeconds: 300
        );

        if (!$executed) {
            abort(429, 'Trop de requetes. Veuillez ralentir.');
        }
    }
}
