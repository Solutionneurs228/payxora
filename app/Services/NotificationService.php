<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;

class NotificationService
{
    /**
     * Notifier qu'un paiement a ete recu
     */
    public function notifyPaymentReceived(Transaction $transaction): void
    {
        // Notifier le vendeur
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'payment_received',
            'title' => 'Paiement recu',
            'message' => "Le paiement pour {$transaction->product_name} a ete recu et est en sequestre.",
            'link' => route('transactions.show', $transaction),
        ]);

        // Notifier l'acheteur
        Notification::create([
            'user_id' => $transaction->buyer_id,
            'type' => 'payment_confirmed',
            'title' => 'Paiement confirme',
            'message' => "Votre paiement pour {$transaction->product_name} est confirme. Le vendeur va expedier.",
            'link' => route('transactions.show', $transaction),
        ]);

        // Email Brevo
        try {
            BrevoService::sendPaymentConfirmed($transaction->seller, $transaction);
            BrevoService::sendPaymentReceipt($transaction->buyer, $transaction);
        } catch (\Exception $e) {
            // Log mais ne pas bloquer
            \Illuminate\Support\Facades\Log::error('Erreur envoi email Brevo', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ]);
        }
    }

    /**
     * Notifier qu'un litige a ete ouvert
     */
    public function notifyDisputeOpened(Transaction $transaction, User $initiator): void
    {
        $otherPartyId = $initiator->id === $transaction->seller_id
            ? $transaction->buyer_id
            : $transaction->seller_id;

        Notification::create([
            'user_id' => $otherPartyId,
            'type' => 'dispute_opened',
            'title' => 'Litige ouvert',
            'message' => "Un litige a ete ouvert sur la transaction {$transaction->product_name}.",
            'link' => route('disputes.show', $transaction->dispute),
        ]);
    }

    /**
     * Notifier resolution d'un litige
     */
    public function notifyDisputeResolved(Transaction $transaction, string $resolution): void
    {
        Notification::create([
            'user_id' => $transaction->seller_id,
            'type' => 'dispute_resolved',
            'title' => 'Litige resolu',
            'message' => "Le litige sur {$transaction->product_name} a ete resolu : {$resolution}",
            'link' => route('transactions.show', $transaction),
        ]);

        Notification::create([
            'user_id' => $transaction->buyer_id,
            'type' => 'dispute_resolved',
            'title' => 'Litige resolu',
            'message' => "Le litige sur {$transaction->product_name} a ete resolu : {$resolution}",
            'link' => route('transactions.show', $transaction),
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            return false;
        }

        $notification->update(['read_at' => now()]);
        return true;
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Obtenir les notifications non lues
     */
    public function getUnread(int $userId, int $limit = 10): array
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->latest()
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
