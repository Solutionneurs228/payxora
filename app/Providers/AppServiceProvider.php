<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Blade components aliases
        Blade::component('components.alert', 'alert');
        Blade::component('components.status-badge', 'status-badge');
        Blade::component('components.transaction-card', 'transaction-card');

        // Blade directives
        Blade::directive('money', function ($expression) {
            return "<?php echo number_format({$expression}, 0, ',', ' ') . ' FCFA'; ?>";
        });

        Blade::directive('datefr', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse({$expression})->locale('fr')->isoFormat('D MMMM YYYY'); ?>";
        });
    }
}
