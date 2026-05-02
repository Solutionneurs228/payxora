<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Policies\TransactionPolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Transaction::class => TransactionPolicy::class,
        // PaymentPolicy est utilise via Gate dans PaymentController
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Gate pour les paiements
        \Gate::define('initiate-payment', [PaymentPolicy::class, 'initiate']);
        \Gate::define('refund-payment', [PaymentPolicy::class, 'refund']);
    }
}
