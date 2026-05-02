<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Console\Command;

class ReleaseEscrowFunds extends Command
{
    protected $signature = 'payxora:release-escrow';
    protected $description = 'Libere les fonds des transactions livrees depuis plus de 48h sans confirmation';

    public function handle(EscrowService $escrowService): void
    {
        $toRelease = Transaction::where('status', 'in_delivery')
            ->where('delivered_at', '<', now()->subHours(48))
            ->get();

        $count = 0;
        foreach ($toRelease as $transaction) {
            $escrowService->releaseFunds($transaction);
            $count++;
        }

        $this->info("{$count} fonds liberes automatiquement.");
    }
}
