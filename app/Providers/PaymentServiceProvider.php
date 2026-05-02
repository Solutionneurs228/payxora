<?php

namespace App\Providers;

use App\Contracts\PaymentProviderInterface;
use App\Services\FakeMobileMoneyService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentProviderInterface::class, function () {
            // En dev, on utilise le fake. En prod, on switchera vers TMoney/Moov
            return new FakeMobileMoneyService();
        });
    }

    public function boot(): void
    {
        //
    }
}
