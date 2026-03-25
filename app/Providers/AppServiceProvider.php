<?php

namespace App\Providers;

use App\Models\PersonnelConge;
use App\Observers\PersonnelCongeObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrer l'Observer pour les congés
        PersonnelConge::observe(PersonnelCongeObserver::class);

        Blade::directive('can', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->hasPermission({$expression})): ?>";
        });

        // Redéfinir @endcan
        Blade::directive('endcan', function () {
            return "<?php endif; ?>";
        });
    }
}
