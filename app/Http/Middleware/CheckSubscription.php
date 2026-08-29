<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app('currentTenant');

        if ($tenant) {
            if (is_null($tenant->subscription_ends_at) || $tenant->subscription_ends_at->isPast()) {
                // Ensure we don't end up in an infinite redirect loop
                if (! $request->routeIs('dashboard.subscribe.*') && ! $request->routeIs('dashboard.logout')) {
                    return redirect()->route('dashboard.subscribe.index');
                }
            }
        }

        return $next($request);
    }
}
