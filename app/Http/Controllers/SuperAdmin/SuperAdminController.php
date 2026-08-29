<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index()
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('subscription_ends_at', '>', now())->count();
        $expiredTenants = $totalTenants - $activeTenants;
        
        // 3000 KES per active tenant
        $monthlyRecurringRevenue = $activeTenants * 3000;

        $tenants = Tenant::withCount(['networks', 'users'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('super.index', compact('totalTenants', 'activeTenants', 'expiredTenants', 'monthlyRecurringRevenue', 'tenants'));
    }

    public function extendSubscription(Request $request, Tenant $tenant)
    {
        $months = (int) $request->input('months', 1);
        
        $currentEnd = $tenant->subscription_ends_at && $tenant->subscription_ends_at > now() 
            ? $tenant->subscription_ends_at 
            : now();
            
        $tenant->subscription_ends_at = $currentEnd->addMonths($months);
        $tenant->save();

        return back()->with('success', "Subscription extended by {$months} month(s).");
    }

    public function suspendTenant(Request $request, Tenant $tenant)
    {
        $tenant->subscription_ends_at = now()->subDay();
        $tenant->save();

        return back()->with('success', "Tenant has been suspended (subscription expired).");
    }
}
