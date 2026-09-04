<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        // app('currentTenant') throws if the binding doesn't exist.
        // Super admins have no tenant, so we must check first.
        if (! app()->bound('currentTenant')) {
            return $next($request);
        }

        $tenant = app('currentTenant');

        if ($tenant) {
            if (is_null($tenant->subscription_ends_at) || $tenant->subscription_ends_at->isPast()) {
                if (
                    ! $request->routeIs('dashboard.subscribe.*') &&
                    ! $request->routeIs('dashboard.logout') &&
                    ! $request->routeIs('dashboard.setup.*') &&
                    ! $request->routeIs('dashboard.notifications.*')
                ) {
                    return redirect()->route('dashboard.subscribe.index');
                }
            }
        }

        return $next($request);
    }
}
