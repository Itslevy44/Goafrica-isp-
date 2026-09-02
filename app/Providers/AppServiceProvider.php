<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $tenant = app()->has('currentTenant') ? app('currentTenant') : null;
            if (!$tenant && \Illuminate\Support\Facades\Auth::check()) {
                $tenant = \Illuminate\Support\Facades\Auth::user()->tenant;
            }
            $view->with('tenant', $tenant);
        });
    }
}
