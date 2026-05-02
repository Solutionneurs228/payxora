<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Commande artisan pour liberer automatiquement les fonds en escrow.
 *
 * Les transactions livrees (DELIVERED) dont le delai de confirmation
 * (48h par defaut) est depasse sont automatiquement completees.
 * Les fonds sont alors liberes au vendeur.
 *
 * Usage :
 *   php artisan payxora:release-escrow
 *
 * Cette commande doit etre executee via un cron job toutes les heures :
 *   0 * * * * cd /chemin/vers/payxora && php artisan payxora:release-escrow >> /dev/null 2>&1
 */
class ReleaseEscrow extends Command
{
    protected $signature = 'payxora:release-escrow
                            {--dry-run : Simuler sans modifier les donnees}';

    protected $description = 'Libere les fonds des transactions livrees dont le delai de confirmation est depasse';

    private EscrowService $escrowService;

    public function __construct(EscrowService $escrowService)
    {
        parent::__construct();
        $this->escrowService = $escrowService;
    }

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $disputeHours = config('payxora.dispute_response_hours', 48);
        $cutoffDate = now()->subHours($disputeHours);

        $this->info("Recherche des transactions a liberer (livrees depuis plus de {$disputeHours}h)...");

        $query = Transaction::where('status', TransactionStatus::DELIVERED)
            ->where('delivered_at', '<', $cutoffDate)
            ->where(function ($q) {
                $q->whereNull('confirmation_deadline')
                  ->orWhere('confirmation_deadline', '<', now());
            });

        $count = $query->count();

        if ($count === 0) {
            $this->info('Aucune transaction a liberer.');
            return self::SUCCESS;
        }

        $this->warn("{$count} transaction(s) eligible(s) a la liberation des fonds.");

        if ($dryRun) {
            $this->info('Mode simulation -- aucune modification effectuee.');
            $query->chunk(100, function ($transactions) {
                foreach ($transactions as $transaction) {
                    $this->line("  [SIMULATION] Transaction #{$transaction->reference} - livree le {$transaction->delivered_at}");
                }
            });
            return self::SUCCESS;
        }

        $released = 0;
        $failed = 0;

        $query->chunk(100, function ($transactions) use (&$released, &$failed) {
            foreach ($transactions as $transaction) {
                try {
                    $success = $this->escrowService->complete($transaction);

                    if ($success) {
                        $released++;
                        Log::info('Fonds liberes automatiquement apres delai', [
                            'transaction_id' => $transaction->id,
                            'reference'      => $transaction->reference,
                            'seller_id'      => $transaction->seller_id,
                            'amount'         => $transaction->net_amount,
                        ]);
                    } else {
                        $failed++;
                        Log::warning('Echec liberation fonds automatique', [
                            'transaction_id' => $transaction->id,
                            'reference'      => $transaction->reference,
                        ]);
                    }
                } catch (\Exception $e) {
                    $failed++;
                    Log::error('Exception lors de la liberation des fonds', [
                        'transaction_id' => $transaction->id,
                        'error'          => $e->getMessage(),
                    ]);
                }
            }
        });

        $this->info("{$released} transaction(s) finalisee(s) avec succes.");

        if ($failed > 0) {
            $this->error("{$failed} transaction(s) n'ont pas pu etre finalisees. Verifiez les logs.");
        }

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
