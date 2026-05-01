<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTransactionAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $transaction = $request->route('transaction');

        if (!$transaction) {
            abort(404);
        }

        $user = auth()->user();

        // L'admin peut tout voir
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Le vendeur ou l'acheteur peuvent voir leur transaction
        if ($transaction->seller_id === $user->id || $transaction->buyer_id === $user->id) {
            return $next($request);
        }

        abort(403, 'Vous n\'êtes pas autorisé à accéder à cette transaction.');
    }
}
