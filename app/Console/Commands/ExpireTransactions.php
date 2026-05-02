<?php

namespace App\Console\Commands;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Console\Command;

class ExpireTransactions extends Command
{
    protected $signature = 'transactions:expire';
    protected $description = 'Annule automatiquement les transactions non payees apres le delai';

    public function handle(EscrowService $escrowService): int
    {
        $expired = Transaction::where('status', TransactionStatus::PENDING_PAYMENT)
            ->where('created_at', '<', now()->subHours(config('payxora.auto_expire_hours', 72)))
            ->get();

        $count = 0;
        foreach ($expired as $transaction) {
            $escrowService->cancel($transaction);
            $count++;
            $this->info("Transaction {$transaction->reference} annulee (expiration)");
        }

        $this->info("{$count} transaction(s) annulee(s)");
        return 0;
    }
}
