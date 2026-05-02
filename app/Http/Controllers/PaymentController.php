<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\InitiatePaymentRequest;
use App\Models\Payment;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        if ($transaction->buyer_id !== Auth::id()) {
            abort(403, 'Seul l'acheteur designe peut effectuer le paiement.');
        }

        return view('transactions.payment', compact('transaction'));
    }

    public function processMobileMoney(InitiatePaymentRequest $request, Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        if ($transaction->buyer_id !== Auth::id()) {
            abort(403);
        }

        $result = $this->paymentService->initiateMobileMoney(
            $transaction,
            $request->input('provider'),
            $request->input('phone_number')
        );

        if ($result['success']) {
            return redirect()->route('payment.success', $transaction)
                ->with('success', $result['message']);
        }

        return redirect()->route('payment.failure', $transaction)
            ->with('error', $result['message']);
    }

    public function callback(Request $request, Transaction $transaction)
    {
        $providerReference = $request->input('provider_reference');

        $this->paymentService->handleCallback($providerReference, $request->all());

        return response()->json(['status' => 'ok']);
    }

    public function success(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('transactions.payment-success', compact('transaction'));
    }

    public function failure(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('transactions.payment-failure', compact('transaction'));
    }
}
