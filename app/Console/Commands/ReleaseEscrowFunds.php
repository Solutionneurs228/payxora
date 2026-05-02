<?php

namespace App\Console\Commands;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Console\Command;

class ReleaseEscrowFunds extends Command
{
    protected $signature = 'escrow:release-auto';
    protected $description = 'Libere automatiquement les fonds si delai de confirmation depasse';

    public function handle(EscrowService $escrowService): int
    {
        $toRelease = Transaction::where('status', TransactionStatus::DELIVERED)
            ->where('confirmation_deadline', '<', now())
            ->get();

        $count = 0;
        foreach ($toRelease as $transaction) {
            $escrowService->complete($transaction);
            $count++;
            $this->info("Transaction {$transaction->reference} auto-completee");
        }

        $this->info("{$count} transaction(s) auto-completee(s)");
        return 0;
    }
}
