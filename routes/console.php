<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled commands for PayXora
Schedule::command('payxora:expire-transactions')->hourly();
Schedule::command('payxora:release-escrow')->hourly();
