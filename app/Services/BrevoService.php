<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    protected static string $apiKey;
    protected static string $baseUrl = 'https://api.brevo.com/v3';

    public static function init()
    {
        self::$apiKey = config('services.brevo.key');
    }

    protected static function headers(): array
    {
        return [
            'accept' => 'application/json',
            'api-key' => self::$apiKey,
            'content-type' => 'application/json',
        ];
    }

    public static function sendEmail(string $to, string $subject, string $htmlContent, ?string $templateId = null)
    {
        try {
            $payload = [
                'sender' => [
                    'name' => config('app.name', 'PayXora Togo'),
                    'email' => config('mail.from.address', 'noreply@payxora.tg'),
                ],
                'to' => [['email' => $to]],
                'subject' => $subject,
                'htmlContent' => $htmlContent,
            ];

            if ($templateId) {
                $payload['templateId'] = (int) $templateId;
                unset($payload['htmlContent'], $payload['subject']);
            }

            $response = Http::withHeaders(self::headers())
                ->post(self::$baseUrl . '/smtp/email', $payload);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Brevo email failed: ' . $e->getMessage());
            return false;
        }
    }

    public static function sendWelcomeEmail(User $user)
    {
        $html = view('emails.welcome', ['user' => $user])->render();
        return self::sendEmail($user->email, 'Bienvenue sur PayXora Togo', $html);
    }

    public static function sendPaymentReceived(User $user, Transaction $transaction)
    {
        $html = view('emails.payment-received', ['user' => $user, 'transaction' => $transaction])->render();
        return self::sendEmail($user->email, 'Paiement recu - Expediez votre commande', $html);
    }

    public static function sendPaymentReleased(User $user, Transaction $transaction)
    {
        $html = view('emails.payment-released', ['user' => $user, 'transaction' => $transaction])->render();
        return self::sendEmail($user->email, 'Paiement libere', $html);
    }

    public static function sendKycApproved(User $user)
    {
        $html = view('emails.kyc-approved', ['user' => $user])->render();
        return self::sendEmail($user->email, 'Verification KYC approuvee', $html);
    }

    public static function sendPasswordReset(string $email)
    {
        $token = \Illuminate\Support\Str::random(64);
        \DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => \Illuminate\Support\Facades\Hash::make($token), 'created_at' => now()]
        );

        $link = url(route('password.reset', ['token' => $token, 'email' => $email], false));
        $html = view('emails.password-reset', ['link' => $link])->render();

        return self::sendEmail($email, 'Reinitialisation de mot de passe', $html);
    }

    public static function sendTransactionNotification(User $user, Transaction $transaction, string $type)
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
}
