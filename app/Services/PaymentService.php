<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\TransactionStatus;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        private EscrowService $escrowService,
        private NotificationService $notificationService,
    ) {}

    /**
     * Initialiser un paiement Mobile Money
     */
    public function initiateMobileMoney(
        Transaction $transaction,
        string $provider,
        string $phoneNumber
    ): array {
        // Verifier que la transaction est en attente de paiement
        if (!$transaction->isPendingPayment()) {
            return [
                'success' => false,
                'message' => 'Cette transaction ne peut plus etre payee.',
            ];
        }

        $payment = Payment::create([
            'transaction_id' => $transaction->id,
            'provider' => $provider,
            'phone_number' => $phoneNumber,
            'amount' => $transaction->amount,
            'currency' => $transaction->currency,
            'status' => PaymentStatus::PENDING,
            'reference' => 'PAY-' . strtoupper(uniqid()),
        ]);

        // TODO: Appeler l'API du provider MNO
        // Pour l'instant, simulation
        $result = $this->processWithProvider($payment);

        if ($result['success']) {
            DB::transaction(function () use ($transaction, $payment, $result) {
                $payment->update([
                    'status' => PaymentStatus::COMPLETED,
                    'provider_reference' => $result['provider_reference'] ?? null,
                    'paid_at' => now(),
                ]);

                // Fonder l'escrow
                $this->escrowService->fund($transaction, $transaction->buyer_id);
            });

            $this->notificationService->notifyPaymentReceived($transaction);
        } else {
            $payment->update([
                'status' => PaymentStatus::FAILED,
                'failure_reason' => $result['message'] ?? 'Erreur inconnue',
            ]);
        }

        return $result;
    }

    /**
     * Traiter le callback du provider MNO
     */
    public function handleCallback(string $providerReference, array $payload): bool
    {
        $payment = Payment::where('provider_reference', $providerReference)->first();

        if (!$payment) {
            Log::warning('Callback payment inconnu', ['reference' => $providerReference]);
            return false;
        }

        $status = $payload['status'] ?? 'unknown';

        if ($status === 'success') {
            DB::transaction(function () use ($payment) {
                $payment->update([
                    'status' => PaymentStatus::COMPLETED,
                    'paid_at' => now(),
                ]);

                $transaction = $payment->transaction;
                $this->escrowService->fund($transaction, $transaction->buyer_id);
            });
        } elseif ($status === 'failed') {
            $payment->update([
                'status' => PaymentStatus::FAILED,
                'failure_reason' => $payload['reason'] ?? 'Echec du paiement',
            ]);
        }

        return true;
    }

    /**
     * Simuler le traitement avec le provider (FAKE pour dev)
     */
    private function processWithProvider(Payment $payment): array
    {
        // En dev, on simule toujours le succes
        // En prod, cette methode appellera TMoneyService ou MoovMoneyService
        return [
            'success' => true,
            'message' => 'Paiement simule avec succes',
            'provider_reference' => 'SIM-' . strtoupper(uniqid()),
        ];
    }

    /**
     * Verifier le statut d'un paiement
     */
    public function checkStatus(Payment $payment): PaymentStatus
    {
        return $payment->status;
    }
}
