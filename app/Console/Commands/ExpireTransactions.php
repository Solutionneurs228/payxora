<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Console\Command;

class ExpireTransactions extends Command
{
    protected $signature = 'payxora:expire-transactions';
    protected $description = 'Annule les transactions en attente depuis plus de 48h';

    public function handle(EscrowService $escrowService): void
    {
        $expired = Transaction::where('status', 'pending')
            ->where('created_at', '<', now()->subHours(48))
            ->get();

        $count = 0;
        foreach ($expired as $transaction) {
            $escrowService->cancelTransaction($transaction, 'system');
            $count++;
        }

        $this->info("{$count} transactions expirees annulees.");
    }
}
