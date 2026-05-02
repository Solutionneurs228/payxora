<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KycMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin bypass KYC
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check KYC status
        $kyc = $user->kycProfile;

        if (!$kyc || $kyc->status !== 'approved') {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Verification d\'identite requise.',
                    'redirect' => route('kyc.show'),
                ], 403);
            }

            return redirect()->route('kyc.show')
                ->with('info', 'Veuillez completer votre verification d\'identite pour acceder a cette page.');
        }

        return $next($request);
    }
}
