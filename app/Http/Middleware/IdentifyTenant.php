<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Network;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Identify from authenticated user (Dashboard / Admin API)
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->tenant_id) {
                $tenant = Tenant::find($user->tenant_id);
                if ($tenant) {
                    app()->instance('currentTenant', $tenant);
                }
            }
        } 
        // 2. Identify from network slug (Captive Portal: /connect/{network_slug})
        elseif ($request->route('network_slug')) {
            $slug = $request->route('network_slug');
            // Global scope is not applied because currentTenant is not yet bound
            $network = Network::where('slug', $slug)->first();
            if ($network) {
                $tenant = Tenant::find($network->tenant_id);
                if ($tenant) {
                    app()->instance('currentTenant', $tenant);
                }
            }
        }
        // 3. Identify from webhook network slug or query param (for payments / API endpoints)
        elseif ($request->has('network_slug')) {
            $slug = $request->input('network_slug');
            $network = Network::where('slug', $slug)->first();
            if ($network) {
                $tenant = Tenant::find($network->tenant_id);
                if ($tenant) {
                    app()->instance('currentTenant', $tenant);
                }
            }
        }
        // 4. Fallback: Identify from request input tenant_id (e.g. background tasks / webhooks)
        elseif ($request->has('tenant_id')) {
            $tenantId = $request->input('tenant_id');
            $tenant = Tenant::find($tenantId);
            if ($tenant) {
                app()->instance('currentTenant', $tenant);
            }
        }

        return $next($request);
    }
}
