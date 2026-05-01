<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Dispute;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected BrevoService $brevoService;

    public function __construct(BrevoService $brevoService)
    {
        $this->brevoService = $brevoService;
    }

    /**
     * Notifier création de transaction
     */
    public function notifyTransactionCreated(Transaction $transaction): void
    {
        $this->sendToUser($transaction->seller, 'transaction_created', [
            'transaction_title' => $transaction->title,
            'transaction_amount' => $transaction->amount,
        ]);
    }

    /**
     * Notifier paiement reçu
     */
    public function notifyPaymentReceived(Transaction $transaction): void
    {
        $this->sendToUser($transaction->seller, 'payment_received', [
            'transaction_title' => $transaction->title,
            'amount' => $transaction->amount,
        ]);

        $this->sendToUser($transaction->buyer, 'payment_confirmed', [
            'transaction_title' => $transaction->title,
        ]);
    }

    /**
     * Notifier expédition
     */
    public function notifyShipped(Transaction $transaction): void
    {
        $this->sendToUser($transaction->buyer, 'item_shipped', [
            'transaction_title' => $transaction->title,
            'tracking_number' => $transaction->tracking_number,
        ]);
    }

    /**
     * Notifier livraison
     */
    public function notifyDelivered(Transaction $transaction): void
    {
        $this->sendToUser($transaction->buyer, 'item_delivered', [
            'transaction_title' => $transaction->title,
            'deadline' => $transaction->confirmation_deadline->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Notifier transaction complétée
     */
    public function notifyCompleted(Transaction $transaction): void
    {
        $this->sendToUser($transaction->seller, 'transaction_completed', [
            'transaction_title' => $transaction->title,
            'net_amount' => $transaction->amount - $transaction->commission_amount,
        ]);

        $this->sendToUser($transaction->buyer, 'purchase_completed', [
            'transaction_title' => $transaction->title,
        ]);
    }

    /**
     * Notifier KYC approuvé
     */
    public function notifyKycApproved(User $user): void
    {
        $this->sendToUser($user, 'kyc_approved', []);
    }

    /**
     * Notifier KYC rejeté
     */
    public function notifyKycRejected(User $user, string $reason): void
    {
        $this->sendToUser($user, 'kyc_rejected', [
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Notifier début médiation
     */
    public function notifyMediationStarted(Dispute $dispute): void
    {
        $this->sendToUser($dispute->transaction->seller, 'mediation_started', [
            'dispute_id' => $dispute->id,
        ]);

        $this->sendToUser($dispute->transaction->buyer, 'mediation_started', [
            'dispute_id' => $dispute->id,
        ]);
    }

    /**
     * Notifier résolution litige
     */
    public function notifyDisputeResolved(Dispute $dispute, string $resolution): void
    {
        $this->sendToUser($dispute->transaction->seller, 'dispute_resolved', [
            'resolution' => $resolution,
        ]);

        $this->sendToUser($dispute->transaction->buyer, 'dispute_resolved', [
            'resolution' => $resolution,
        ]);
    }

    /**
     * Envoyer un email à l'utilisateur
     */
    protected function sendToUser(User $user, string $template, array $data): void
    {
        if (!config('payxora.notifications.email.enabled')) {
            return;
        }

        try {
            $this->brevoService->sendEmail($user->email, $template, array_merge($data, [
                'user_name' => $user->name,
            ]));
        } catch (\Exception $e) {
            Log::error('Failed to send notification', [
                'user_id' => $user->id,
                'template' => $template,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envoyer SMS (quand disponible)
     */
    protected function sendSms(User $user, string $message): void
    {
        if (!config('payxora.notifications.sms.enabled')) {
            return;
        }

        // Implémentation SMS à venir
        Log::info('SMS would be sent', ['to' => $user->phone, 'message' => $message]);
    }
}
