<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BrevoService
{
    private static ?string $apiKey = null;

    private static string $baseUrl = 'https://api.brevo.com/v3';

    /**
     * Initialise la clé API depuis la config.
     */
    public static function init(): void
    {
        self::$apiKey = Config::get('services.brevo.key');

        if (empty(self::$apiKey)) {
            Log::warning('[BREVO] Clé API non configurée dans config/services.php');
        } else {
            Log::info('[BREVO] Clé API chargée (masquée): '.substr(self::$apiKey, 0, 10).'...');
        }
    }

    /**
     * Retourne les headers HTTP pour les requêtes Brevo.
     */
    protected static function headers(): array
    {
        if (self::$apiKey === null) {
            self::init();
        }

        return [
            'accept' => 'application/json',
            'api-key' => self::$apiKey ?? '',
            'content-type' => 'application/json',
        ];
    }

    /**
     * Envoie un email via l'API Brevo avec LOGGING complet.
     */
    public static function sendEmail(string $to, string $subject, string $htmlContent, ?string $templateId = null): bool
    {
        try {
            // ─── Vérifications préliminaires ─────────────────────────────
            if (empty(self::$apiKey)) {
                Log::error('[BREVO] ERREUR: Clé API manquante. Impossible d\'envoyer l\'email.');

                return false;
            }

            $senderEmail = config('mail.from.address', 'noreply@payxora.tg');
            $senderName = config('mail.from.name', 'PayXora Togo');

            Log::info('[BREVO] Tentative d\'envoi', [
                'to' => $to,
                'subject' => $subject,
                'sender' => $senderEmail,
            ]);

            $payload = [
                'sender' => [
                    'name' => $senderName,
                    'email' => $senderEmail,
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
                ->post(self::$baseUrl.'/smtp/email', $payload);

            // ─── LOGGING DETAILLE de la réponse ────────────────────────────
            $status = $response->status();
            $body = $response->body();

            Log::info('[BREVO] Réponse API', [
                'status' => $status,
                'body' => $body,
            ]);

            if ($status === 201) {
                Log::info('[BREVO] Email envoyé avec succès à '.$to);

                return true;
            }

            if ($status === 401) {
                $decoded = json_decode($body, true);
                $msg = $decoded['message'] ?? 'inconnue';
                Log::error('[BREVO] ERREUR 401 - Clé API invalide ou compte non vérifié: '.$msg);
                Log::error('[BREVO] Vérifiez: 1) Clé API correcte 2) Sender vérifié dans Brevo 3) Compte activé');

                return false;
            }

            if ($status === 400) {
                Log::error('[BREVO] ERREUR 400 - Requête invalide: '.$body);
                Log::error('[BREVO] Cause probable: sender email non vérifié ou domaine non authentifié');

                return false;
            }

            if ($status === 402) {
                Log::error('[BREVO] ERREUR 402 - Crédits insuffisants ou compte en attente d\'activation');

                return false;
            }

            if ($status === 403) {
                Log::error('[BREVO] ERREUR 403 - Permissions insuffisantes. Vérifiez la sécurité IP dans Brevo.');

                return false;
            }

            Log::error('[BREVO] ERREUR '.$status.' inattendue: '.$body);

            return false;

        } catch (\Exception $e) {
            Log::error('[BREVO] Exception: '.$e->getMessage(), [
                'to' => $to,
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

    public static function sendPasswordReset(string $email): bool
    {
        $token = Str::random(64);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $link = url(route('password.reset', ['token' => $token, 'email' => $email], false));
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
                'Content-Type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => 'PayXora',
                    'email' => config('services.brevo.sender'),
                ],
                'to' => [
                    ['email' => $seller->email, 'name' => $seller->name],
                ],
                // 'templateId' => 3,
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
