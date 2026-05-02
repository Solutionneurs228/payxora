<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

/**
 * Service de logging securise pour les operations critiques.
 *
 * Ce service centralise les logs de securite pour :
 * - Les paiements (initiation, succes, echec, remboursement)
 * - Les connexions (login, logout, echec)
 * - Les modifications de KYC
 * - Les actions admin (liberation fonds, remboursement force)
 * - Les tentatives d'acces non autorisees
 *
 * Chaque log inclut :
 * - Actor (user_id ou IP)
 * - Action
 * - IP + User-Agent
 * - Metadata contextuelle
 * - Timestamp
 */
class SecurityLogService
{
    /**
     * Logger un evenement de paiement.
     */
    public static function logPayment(
        string $action,
        ?int $userId,
        int $transactionId,
        array $metadata = []
    ): void {
        self::log('payment', $action, $userId, array_merge($metadata, [
            'transaction_id' => $transactionId,
        ]));
    }

    /**
     * Logger un evenement d'authentification.
     */
    public static function logAuth(
        string $action,
        ?int $userId,
        array $metadata = []
    ): void {
        self::log('auth', $action, $userId, $metadata);
    }

    /**
     * Logger une action admin.
     */
    public static function logAdmin(
        string $action,
        int $adminId,
        array $metadata = []
    ): void {
        self::log('admin', $action, $adminId, array_merge($metadata, [
            'is_admin_action' => true,
        ]));
    }

    /**
     * Logger une tentative d'acces non autorisee.
     */
    public static function logUnauthorized(
        string $resource,
        ?int $userId,
        array $metadata = []
    ): void {
        self::log('security', 'unauthorized_access', $userId, array_merge($metadata, [
            'resource' => $resource,
        ]));

        // Alerte en temps reel pour les tentatives critiques
        if (in_array($resource, ['admin', 'escrow_release', 'refund_force'])) {
            Log::channel('security')->alert('Tentative d'acces non autorisee critique', [
                'resource'  => $resource,
                'user_id'   => $userId,
                'ip'        => Request::ip(),
                'user_agent'=> Request::userAgent(),
            ]);
        }
    }

    /**
     * Logger un evenement KYC.
     */
    public static function logKyc(
        string $action,
        int $userId,
        array $metadata = []
    ): void {
        self::log('kyc', $action, $userId, $metadata);
    }

    /**
     * Logger un evenement de webhook.
     */
    public static function logWebhook(
        string $provider,
        string $status,
        array $metadata = []
    ): void {
        self::log('webhook', "{$provider}_{$status}", null, array_merge($metadata, [
            'provider' => $provider,
        ]));
    }

    /**
     * Logger un evenement generique.
     */
    private static function log(
        string $type,
        string $action,
        ?int $userId,
        array $metadata = []
    ): void {
        $data = [
            'type'       => $type,
            'action'       => $action,
            'actor_id'     => $userId,
            'ip_address'   => Request::ip(),
            'user_agent'   => Request::userAgent(),
            'metadata'     => array_merge($metadata, [
                'url'       => Request::url(),
                'method'    => Request::method(),
                'timestamp' => now()->toIso8601String(),
            ]),
        ];

        // Sauvegarder en base
        ActivityLog::create($data);

        // Logger aussi dans les fichiers pour le debug
        Log::channel('security')->info("[{$type}] {$action}", [
            'user_id'    => $userId,
            'ip'         => $data['ip_address'],
            'metadata'   => $metadata,
        ]);
    }

    /**
     * Retourne les logs de securite pour un utilisateur.
     */
    public static function getUserLogs(int $userId, int $limit = 50): array
    {
        return ActivityLog::where('actor_id', $userId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Retourne les logs de securite pour une transaction.
     */
    public static function getTransactionLogs(int $transactionId, int $limit = 50): array
    {
        return ActivityLog::whereJsonContains('metadata->transaction_id', $transactionId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Detecte les activites suspectes.
     *
     * @return array<int, string> Liste des alertes
     */
    public static function detectSuspiciousActivity(int $userId): array
    {
        $alerts = [];
        $since = now()->subHours(24);

        // Trop de paiements echoues
        $failedPayments = ActivityLog::where('actor_id', $userId)
            ->where('type', 'payment')
            ->where('action', 'failed')
            ->where('created_at', '>=', $since)
            ->count();

        if ($failedPayments >= 5) {
            $alerts[] = "{$failedPayments} paiements echoues en 24h";
        }

        // Connexions depuis plusieurs IPs differentes
        $uniqueIps = ActivityLog::where('actor_id', $userId)
            ->where('type', 'auth')
            ->where('action', 'login')
            ->where('created_at', '>=', $since)
            ->distinct('ip_address')
            ->count('ip_address');

        if ($uniqueIps >= 3) {
            $alerts[] = "Connexions depuis {$uniqueIps} IPs differentes en 24h";
        }

        // Tentatives d'acces admin
        $adminAttempts = ActivityLog::where('actor_id', $userId)
            ->where('type', 'security')
            ->where('action', 'unauthorized_access')
            ->where('created_at', '>=', $since)
            ->count();

        if ($adminAttempts >= 3) {
            $alerts[] = "{$adminAttempts} tentatives d'acces non autorisees en 24h";
        }

        return $alerts;
    }
}
