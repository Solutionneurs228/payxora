<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected BrevoService $brevoService;

    public function __construct(BrevoService $brevoService)
    {
        $this->brevoService = $brevoService;
    }

    /**
     * Cree une notification interne.
     */
    public function create(User $user, string $title, string $message, ?string $actionUrl = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'is_read' => false,
        ]);
    }

    /**
     * Notifie tous les admins.
     */
    public function notifyAdmins(string $title, string $message, ?string $actionUrl = null): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $this->create($admin, $title, $message, $actionUrl);
        }
    }

    /**
     * Envoie un email de bienvenue.
     */
    public function sendWelcomeEmail(User $user): void
    {
        try {
            $this->brevoService->sendEmail(
                $user->email,
                $user->name,
                'Bienvenue sur PayXora',
                $this->getWelcomeTemplate($user)
            );
        } catch (\Exception $e) {
            Log::error('Erreur email de bienvenue: ' . $e->getMessage());
        }
    }

    /**
     * Envoie un email de verification KYC approuvee.
     */
    public function sendKycApprovedEmail(User $user): void
    {
        try {
            $this->brevoService->sendEmail(
                $user->email,
                $user->name,
                'Votre compte PayXora est verifie',
                $this->getKycApprovedTemplate($user)
            );
        } catch (\Exception $e) {
            Log::error('Erreur email KYC approuve: ' . $e->getMessage());
        }
    }

    /**
     * Envoie un email de KYC rejetee.
     */
    public function sendKycRejectedEmail(User $user, string $reason): void
    {
        try {
            $this->brevoService->sendEmail(
                $user->email,
                $user->name,
                'Verification KYC - Action requise',
                $this->getKycRejectedTemplate($user, $reason)
            );
        } catch (\Exception $e) {
            Log::error('Erreur email KYC rejete: ' . $e->getMessage());
        }
    }

    /**
     * Envoie un email de transaction completee.
     */
    public function sendTransactionCompletedEmail(User $user, $transaction): void
    {
        try {
            $this->brevoService->sendEmail(
                $user->email,
                $user->name,
                'Transaction terminee - ' . $transaction->title,
                $this->getTransactionCompletedTemplate($user, $transaction)
            );
        } catch (\Exception $e) {
            Log::error('Erreur email transaction: ' . $e->getMessage());
        }
    }

    // Templates HTML
    private function getWelcomeTemplate(User $user): string
    {
        return "
            <h1>Bienvenue sur PayXora, {$user->name} !</h1>
            <p>Merci de vous etre inscrit sur PayXora, la plateforme de paiement securise par escrow au Togo.</p>
            <p>Pour commencer a utiliser nos services, veuillez completer votre verification d'identite (KYC).</p>
            <a href='" . route('kyc.show') . "' style='display:inline-block;padding:12px 24px;background:#059669;color:white;text-decoration:none;border-radius:6px;'>Verifier mon identite</a>
            <p>Si vous avez des questions, contactez-nous a contact@payxora.tg</p>
        ";
    }

    private function getKycApprovedTemplate(User $user): string
    {
        return "
            <h1>Felicitations {$user->name} !</h1>
            <p>Votre verification d'identite a ete approuvee. Votre compte PayXora est maintenant entierement actif.</p>
            <p>Vous pouvez maintenant :</p>
            <ul>
                <li>Creer des transactions</li>
                <li>Effectuer des paiements securises</li>
                <li>Utiliser le systeme d'escrow</li>
            </ul>
            <a href='" . route('dashboard') . "' style='display:inline-block;padding:12px 24px;background:#059669;color:white;text-decoration:none;border-radius:6px;'>Acceder au tableau de bord</a>
        ";
    }

    private function getKycRejectedTemplate(User $user, string $reason): string
    {
        return "
            <h1>Verification KYC - Action requise</h1>
            <p>Bonjour {$user->name},</p>
            <p>Votre verification d'identite n'a pas pu etre approuvee pour la raison suivante :</p>
            <blockquote style='border-left:4px solid #ef4444;padding-left:12px;color:#dc2626;'>{$reason}</blockquote>
            <p>Veuillez soumettre de nouveaux documents en suivant le lien ci-dessous :</p>
            <a href='" . route('kyc.show') . "' style='display:inline-block;padding:12px 24px;background:#059669;color:white;text-decoration:none;border-radius:6px;'>Reessayer la verification</a>
        ";
    }

    private function getTransactionCompletedTemplate(User $user, $transaction): string
    {
        $amount = number_format($transaction->amount, 0, ',', ' ');
        return "
            <h1>Transaction terminee</h1>
            <p>Bonjour {$user->name},</p>
            <p>La transaction <strong>{$transaction->title}</strong> d'un montant de <strong>{$amount} FCFA</strong> a ete terminee avec succes.</p>
            <a href='" . route('transactions.show', $transaction) . "' style='display:inline-block;padding:12px 24px;background:#059669;color:white;text-decoration:none;border-radius:6px;'>Voir la transaction</a>
        ";
    }
}
