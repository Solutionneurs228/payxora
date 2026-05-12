<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KycVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->kyc || $user->kyc->status->value !== 'approved') {
            return redirect()->route('kyc')
                ->with('error', 'Validation KYC requise.');
        }

        return $next($request);
    }
}
