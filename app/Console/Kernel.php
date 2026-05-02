<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Expire les transactions non payees apres 72h
        $schedule->command('payxora:expire-transactions')
            ->hourly()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/cron-expire.log'));

        // Libere les fonds apres le delai de confirmation
        $schedule->command('payxora:release-escrow-funds')
            ->hourly()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/cron-release.log'));

        // Traite les remboursements automatiques
        $schedule->command('payxora:process-auto-refunds')
            ->hourly()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/cron-refunds.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
