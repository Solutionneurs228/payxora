<?php

namespace App\Providers;

use App\Contracts\PaymentProviderInterface;
use App\Services\FakeMobileMoneyService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentProviderInterface::class, function ($app) {
            // Pour l'instant, utiliser le fake service
            // Quand les vraies API seront intégrées, switcher ici
            return new FakeMobileMoneyService();
        });
    }

    public function boot(): void
    {
        //
    }
}
