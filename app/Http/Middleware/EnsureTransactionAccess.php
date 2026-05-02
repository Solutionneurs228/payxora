<?php

namespace App\Http\Middleware;

use App\Models\Transaction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTransactionAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $transaction = $request->route('transaction');

        if (!$transaction instanceof Transaction) {
            $transaction = Transaction::find($request->route('transaction'));
        }

        if (!$transaction) {
            abort(404);
        }

        $user = $request->user();

        if ($transaction->seller_id !== $user->id
            && $transaction->buyer_id !== $user->id
            && !$user->isAdmin()) {
            abort(403, 'Vous n'avez pas acces a cette transaction.');
        }

        return $next($request);
    }
}
