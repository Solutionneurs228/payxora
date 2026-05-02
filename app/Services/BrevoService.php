<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.brevo.com/v3';
    protected string $senderEmail;
    protected string $senderName;

    public function __construct()
    {
        $this->apiKey = config('payxora.notifications.brevo_api_key', env('BREVO_API_KEY', ''));
        $this->senderEmail = config('mail.from.address', 'contact@payxora.tg');
        $this->senderName = config('mail.from.name', 'PayXora');
    }

    /**
     * Envoie un email via Brevo API.
     */
    public function sendEmail(string $toEmail, string $toName, string $subject, string $htmlContent): array
    {
        if (empty($this->apiKey)) {
            Log::warning('Brevo API key non configuree. Email non envoye.');
            return ['success' => false, 'error' => 'API key manquante'];
        }

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'api-key' => $this->apiKey,
                'content-type' => 'application/json',
            ])->post($this->apiUrl . '/smtp/email', [
                'sender' => [
                    'name' => $this->senderName,
                    'email' => $this->senderEmail,
                ],
                'to' => [
                    [
                        'email' => $toEmail,
                        'name' => $toName,
                    ],
                ],
                'subject' => $subject,
                'htmlContent' => $this->wrapTemplate($htmlContent),
            ]);

            if ($response->successful()) {
                Log::info('Email envoye via Brevo', ['to' => $toEmail, 'subject' => $subject]);
                return ['success' => true, 'messageId' => $response->json('messageId')];
            }

            Log::error('Erreur Brevo API', ['status' => $response->status(), 'body' => $response->body()]);
            return ['success' => false, 'error' => $response->body()];

        } catch (\Exception $e) {
            Log::error('Exception Brevo: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Envoie un email de reinitialisation de mot de passe.
     */
    public function sendPasswordReset(string $toEmail, string $toName, string $resetUrl): array
    {
        $subject = 'Reinitialisation de votre mot de passe PayXora';
        $html = "
            <h1>Reinitialisation de mot de passe</h1>
            <p>Bonjour {$toName},</p>
            <p>Vous avez demande la reinitialisation de votre mot de passe PayXora.</p>
            <p>Cliquez sur le lien ci-dessous pour choisir un nouveau mot de passe :</p>
            <a href='{$resetUrl}' style='display:inline-block;padding:12px 24px;background:#059669;color:white;text-decoration:none;border-radius:6px;'>Reinitialiser mon mot de passe</a>
            <p>Ce lien expire dans 60 minutes.</p>
            <p>Si vous n'avez pas fait cette demande, ignorez cet email.</p>
        ";

        return $this->sendEmail($toEmail, $toName, $subject, $html);
    }

    /**
     * Envoie un email de verification d'adresse.
     */
    public function sendVerificationEmail(string $toEmail, string $toName, string $verifyUrl): array
    {
        $subject = 'Verifiez votre adresse email - PayXora';
        $html = "
            <h1>Verification de votre email</h1>
            <p>Bonjour {$toName},</p>
            <p>Merci de vous etre inscrit sur PayXora. Cliquez sur le lien ci-dessous pour verifier votre adresse email :</p>
            <a href='{$verifyUrl}' style='display:inline-block;padding:12px 24px;background:#059669;color:white;text-decoration:none;border-radius:6px;'>Verifier mon email</a>
            <p>Si vous n'avez pas cree de compte, ignorez cet email.</p>
        ";

        return $this->sendEmail($toEmail, $toName, $subject, $html);
    }

    /**
     * Wrap le contenu HTML dans un template de base.
     */
    private function wrapTemplate(string $content): string
    {
        return "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    body { font-family: 'Inter', Arial, sans-serif; line-height: 1.6; color: #374151; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    h1 { color: #065f46; font-size: 24px; }
                    a { color: #059669; }
                    blockquote { background: #fef2f2; padding: 12px; border-radius: 6px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div style='text-align:center;margin-bottom:24px;'>
                        <h2 style='color:#059669;margin:0;'>PayXora</h2>
                        <p style='color:#6b7280;font-size:14px;margin:4px 0 0;'>Paiement securise au Togo</p>
                    </div>
                    {$content}
                    <hr style='border:none;border-top:1px solid #e5e7eb;margin:32px 0;'>
                    <p style='font-size:12px;color:#9ca3af;text-align:center;'>
                        PayXora - Lome, Togo<br>
                        <a href='mailto:contact@payxora.tg'>contact@payxora.tg</a>
                    </p>
                </div>
            </body>
            </html>
        ";
    }
}
