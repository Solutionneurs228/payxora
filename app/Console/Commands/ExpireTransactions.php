<?php

namespace App\Console\Commands;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Illuminate\Console\Command;

class ExpireTransactions extends Command
{
    protected $signature = 'payxora:expire-transactions {--dry-run}';
    protected $description = 'Annule les transactions non payees apres le delai d\'expiration';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $expiryHours = config('payxora.transaction_expiry_hours', 72);
        $cutoffDate = now()->subHours($expiryHours);

        $this->info("Recherche des transactions expirees (plus de {$expiryHours}h en attente)...");

        $query = Transaction::where('status', TransactionStatus::PENDING_PAYMENT)
            ->where('created_at', '<', $cutoffDate);

        $count = $query->count();

        if ($count === 0) {
            $this->info('Aucune transaction expiree trouvee.');
            return self::SUCCESS;
        }

        $this->warn("{$count} transaction(s) expiree(s) trouvee(s).");

        if ($dryRun) {
            $this->info('Mode simulation -- aucune modification effectuee.');
            return self::SUCCESS;
        }

        $cancelled = 0;
        foreach ($query->cursor() as $transaction) {
            $transaction->update(['status' => TransactionStatus::CANCELLED]);
            $cancelled++;
        }

        $this->info("{$cancelled} transaction(s) annulee(s).");
        return self::SUCCESS;
    }
}
