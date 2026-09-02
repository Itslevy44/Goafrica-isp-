<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
        // Inject $tenant and $currentTenant into ALL views so no controller
        // needs to pass it explicitly. Both variable names are set for safety.
        View::composer('*', function ($view) {
            $tenant = null;

            // 1. Try the IoC container (set by IdentifyTenant middleware)
            if (app()->has('currentTenant')) {
                $tenant = app('currentTenant');
            }

            // 2. Fall back to the authenticated user's tenant
            if (!$tenant && Auth::check()) {
                $tenant = Auth::user()?->tenant;
            }

            // Expose as both names so any blade template works regardless of name used
            $view->with('tenant', $tenant);
            $view->with('currentTenant', $tenant);
        });
    }
}
