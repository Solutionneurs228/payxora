<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    /**
     * Envoie un email via l'API Brevo.
     */
    public static function sendEmail(string $to, string $subject, string $htmlContent, ?string $templateId = null): bool
    {
        try {
            $apiKey = config('services.brevo.key');

            $payload = [
                'sender' => [
                    'name' => config('mail.from.name', 'PayXora'),
                    'email' => config('mail.from.address'),
                ],
                'to' => [['email' => $to]],
                'subject' => $subject,
                'htmlContent' => $htmlContent,
            ];

            if ($templateId) {
                $payload['templateId'] = (int) $templateId;
                unset($payload['htmlContent'], $payload['subject']);
            }

            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', $payload);

            Log::info('Brevo email envoyé', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $response->successful();

        } catch (\Throwable $e) {
            Log::error('Erreur envoi Brevo', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Méthodes helpers */
    /* ------------------------------------------------------------------ */

    public static function sendWelcomeEmail(User $user): bool
    {
        $html = view('emails.welcome', ['user' => $user])->render();
        return self::sendEmail($user->email, 'Bienvenue sur PayXora Togo', $html);
    }

    public static function sendPaymentReceived(User $user, Transaction $transaction): bool
    {
        $html = view('emails.payment-received', ['user' => $user, 'transaction' => $transaction])->render();
        return self::sendEmail($user->email, 'Paiement recu - Expediez votre commande', $html);
    }

    public static function sendPaymentReleased(User $user, Transaction $transaction): bool
    {
        $html = view('emails.payment-released', ['user' => $user, 'transaction' => $transaction])->render();
        return self::sendEmail($user->email, 'Paiement libere', $html);
    }

    public static function sendKycApproved(User $user): bool
    {
        $html = view('emails.kyc-approved', ['user' => $user])->render();
        return self::sendEmail($user->email, 'Verification KYC approuvee', $html);
    }

    public static function sendPasswordReset(string $email, string $link): bool
    {
        $html = view('emails.password-reset', ['link' => $link])->render();
        return self::sendEmail($email, 'Reinitialisation de mot de passe', $html);
    }

    public static function sendTransactionNotification(User $user, Transaction $transaction, string $type): bool
    {
        $subjects = [
            'created' => 'Nouvelle transaction creee',
            'paid' => 'Paiement confirme',
            'shipped' => 'Commande expediee',
            'delivered' => 'Commande livree',
            'completed' => 'Transaction terminee',
            'cancelled' => 'Transaction annulee',
            'disputed' => 'Litige ouvert',
        ];

        $html = view('emails.transaction-update', [
            'user' => $user,
            'transaction' => $transaction,
            'type' => $type,
        ])->render();

        return self::sendEmail($user->email, $subjects[$type] ?? 'Mise a jour transaction', $html);
    }

    /**
     * Notifie le vendeur qu'un acheteur a ete trouve
     */
    public static function sendBuyerFound(User $seller, Transaction $transaction, User $buyer): void
    {
        try {
            $response = Http::withHeaders([
                'api-key' => config('services.brevo.key'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => 'PayXora',
                    'email' => config('mail.from.address'),
                ],
                'to' => [
                    ['email' => $seller->email, 'name' => $seller->name],
                ],
                'subject' => 'Acheteur trouve pour votre transaction',
                'htmlContent' => '<p>Bonjour '.$seller->name.',</p><p>'.$buyer->name.' souhaite acheter '.$transaction->title.'.</p>',
                'params' => [
                    'SELLER_NAME' => $seller->name,
                    'BUYER_NAME' => $buyer->name,
                    'BUYER_EMAIL' => $buyer->email,
                    'PRODUCT_NAME' => $transaction->title,
                    'AMOUNT' => number_format($transaction->amount, 0, ',', ' ').' FCFA',
                    'TRANSACTION_LINK' => route('transactions.show', $transaction),
                ],
            ]);

            if (! $response->successful()) {
                Log::error('Brevo buyer found email failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Brevo buyer found email exception: '.$e->getMessage());
        }
    }
}