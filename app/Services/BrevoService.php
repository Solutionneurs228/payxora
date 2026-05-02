<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    private static function sendEmail(string $to, string $subject, string $htmlContent): bool
    {
        $apiKey = config('payxora.notifications.brevo_api_key');

        if (empty($apiKey)) {
            Log::warning('Brevo API key not configured');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => config('payxora.notifications.brevo_sender_name', 'PayXora'),
                    'email' => config('payxora.notifications.brevo_sender_email', 'noreply@payxora.tg'),
                ],
                'to' => [['email' => $to]],
                'subject' => $subject,
                'htmlContent' => $htmlContent,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Brevo email failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function sendWelcomeEmail(User $user): bool
    {
        return self::sendEmail(
            $user->email,
            'Bienvenue sur PayXora !',
            "<h1>Bienvenue {$user->name} !</h1><p>Votre compte PayXora a ete cree avec succes. Veuillez completer votre verification KYC pour commencer a utiliser la plateforme.</p>"
        );
    }

    public static function sendPaymentConfirmed(User $seller, Transaction $transaction): bool
    {
        return self::sendEmail(
            $seller->email,
            'Paiement recu — ' . $transaction->product_name,
            "<h1>Paiement recu !</h1><p>Le paiement pour {$transaction->product_name} a ete effectue. Veuillez expedier le produit.</p><p>Montant : {$transaction->amount} FCFA</p>"
        );
    }

    public static function sendPaymentReceipt(User $buyer, Transaction $transaction): bool
    {
        return self::sendEmail(
            $buyer->email,
            'Recu de paiement — ' . $transaction->product_name,
            "<h1>Merci pour votre paiement !</h1><p>Votre paiement de {$transaction->amount} FCFA pour {$transaction->product_name} est confirme. Le vendeur va expedier votre commande.</p>"
        );
    }

    public static function sendPaymentReleased(User $seller, Transaction $transaction): bool
    {
        return self::sendEmail(
            $seller->email,
            'Paiement libere — ' . $transaction->product_name,
            "<h1>Vos fonds ont ete liberes !</h1><p>Le paiement de {$transaction->net_amount} FCFA (net) pour {$transaction->product_name} a ete libere sur votre compte.</p>"
        );
    }

    public static function sendDisputeOpened(User $user, Transaction $transaction): bool
    {
        return self::sendEmail(
            $user->email,
            'Litige ouvert — ' . $transaction->product_name,
            "<h1>Un litige a ete ouvert</h1><p>Un litige a ete ouvert sur la transaction {$transaction->product_name}. Notre equipe va examiner le cas.</p>"
        );
    }
}
