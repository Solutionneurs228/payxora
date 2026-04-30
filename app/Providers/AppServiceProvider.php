<?php

namespace App\Providers;

use App\Services\FakeMobileMoneyService;
use App\Services\MobileMoneyInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MobileMoneyInterface::class, function () {
            return new FakeMobileMoneyService();
        });
    }

    public function boot(): void
    {
        //
    }
}