<?php

namespace App\Http\Middleware;

use App\Enums\KycStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KycMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin bypass KYC
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Verifier KYC
        $kycProfile = $user->kycProfile;

        if (!$kycProfile) {
            return redirect()->route('kyc')
                ->with('warning', 'Veuillez completer votre verification d\'identite (KYC) pour acceder a cette page.');
        }

        if ($kycProfile->status !== KycStatus::APPROVED) {
            if ($kycProfile->status === KycStatus::PENDING) {
                return redirect()->route('kyc.verification')
                    ->with('info', 'Votre verification KYC est en cours de traitement.');
            }

            if ($kycProfile->status === KycStatus::REJECTED) {
                return redirect()->route('kyc')
                    ->with('error', 'Votre verification KYC a ete refusee. Veuillez soumettre a nouveau vos documents.');
            }
        }

        return $next($request);
    }
}
