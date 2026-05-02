<?php

namespace App\Console\Commands;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Console\Command;

class ProcessAutoRefunds extends Command
{
    protected $signature = 'refunds:process';
    protected $description = 'Traite les remboursements automatiques';

    public function handle(EscrowService $escrowService): int
    {
        // Traiter les transactions annulees avec paiement effectue
        $toRefund = Transaction::whereIn('status', [TransactionStatus::CANCELLED])
            ->whereHas('payment', function ($q) {
                $q->where('status', 'completed');
            })
            ->get();

        $count = 0;
        foreach ($toRefund as $transaction) {
            $escrowService->refund($transaction);
            $count++;
            $this->info("Transaction {$transaction->reference} remboursee");
        }

        $this->info("{$count} remboursement(s) effectue(s)");
        return 0;
    }
}
