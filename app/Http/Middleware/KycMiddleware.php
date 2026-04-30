<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KycMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->kyc_status !== 'verified') {
            return redirect()->route('kyc.show')
                ->with('warning', 'Veuillez compléter votre vérification d'identité pour continuer.');
        }

        return $next($request);
    }
}
