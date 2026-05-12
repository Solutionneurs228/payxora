<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\EscrowAccount;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Transaction $transaction)
    {
        if ($transaction->buyer_id !== Auth::id()) {
            abort(403);
        }

        return view('transactions.payment', compact('transaction'));
    }

    public function processMobileMoney(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'method' => ['required', 'in:tmoney,moov'],
            'phone' => ['required', 'regex:/^\+228[0-9]{8}$/'],
        ]);

        // Simulation API Mobile Money (a remplacer par integration reelle)
        $providerRef = strtoupper(uniqid('MM-'));

        $payment = Payment::create([
            'transaction_id' => $transaction->id,
            'user_id' => Auth::id(),
            'method' => $validated['method'],
            'amount' => $transaction->amount,
            'fees' => $transaction->amount * 0.01, // 1% frais
            'provider_reference' => $providerRef,
            'status' => 'processing',
        ]);

        // Simulation reussite (en prod: appel API TMoney/Moov)
        $payment->update([
            'status' => 'success',
            'processed_at' => now(),
        ]);

        $transaction->update([
            'status' => 'paid',
            'payment_method' => $validated['method'],
            'payment_reference' => $providerRef,
            'paid_at' => now(),
        ]);

        // Creer compte sequestre
        EscrowAccount::create([
            'transaction_id' => $transaction->id,
            'amount_held' => $transaction->amount,
            'status' => 'holding',
        ]);

        // Notifier vendeur
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'payment_received',
            'title' => 'Paiement recu',
            'message' => "Paiement recu pour {$transaction->product_name}. Expediez maintenant !",
            'link' => route('transactions.show', $transaction),
        ]);

        // Email Brevo
        \App\Services\BrevoService::sendPaymentReceived($transaction->seller, $transaction);

        ActivityLog::log('payment_processed', $payment, Auth::user(), [
            'method' => $validated['method'],
            'amount' => $transaction->amount,
        ]);

        return redirect()->route('payment.success', $transaction)
            ->with('success', 'Paiement effectue avec succes !');
    }

    public function callback(Request $request, Transaction $transaction)
    {
        // Webhook pour callback TMoney/Moov
        // Verification signature, mise a jour statut
        return response()->json(['status' => 'ok']);
    }

    public function success(Transaction $transaction)
    {
        return view('transactions.payment-success', compact('transaction'));
    }

    public function failure(Transaction $transaction)
    {
        return view('transactions.payment-failure', compact('transaction'));
    }
}
