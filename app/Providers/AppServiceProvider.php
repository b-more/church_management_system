<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;

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
        // Register Filament Navigation Groups for Church Income Management
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make('Income Management'),
                NavigationGroup::make('Projects Management'),
                NavigationGroup::make('Reports'),
            ]);
        });
    }
}
