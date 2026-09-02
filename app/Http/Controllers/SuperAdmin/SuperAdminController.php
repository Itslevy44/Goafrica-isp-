<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\InternetSession;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SuperAdminController extends Controller
{
    public function index()
    {
        $totalTenants  = Tenant::count();
        $activeTenants = Tenant::where('subscription_ends_at', '>', now())->count();
        $expiredTenants = $totalTenants - $activeTenants;
        $trialTenants  = Tenant::whereBetween('subscription_ends_at', [now(), now()->addDays(3)])->count();

        // SaaS revenue: 500 KES per active tenant/month
        $monthlyRecurringRevenue = $activeTenants * 500;

        // Platform-wide stats
        $totalCustomers = Customer::count();
        $totalSessions  = InternetSession::count();
        $activeSessions = InternetSession::where('status', 'active')->where('ends_at', '>', now())->count();
        $totalTransactionRevenue = Transaction::where('status', 'success')->sum('amount_minor');
        $thisMonthRevenue = Transaction::where('status', 'success')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount_minor');

        // Revenue chart — last 7 days of SaaS subscriptions (new signups)
        $newSignupsThisMonth = Tenant::whereMonth('created_at', now()->month)->count();

        $tenants = Tenant::withCount(['networks', 'users'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('super.index', compact(
            'totalTenants', 'activeTenants', 'expiredTenants', 'trialTenants',
            'monthlyRecurringRevenue', 'tenants',
            'totalCustomers', 'totalSessions', 'activeSessions',
            'totalTransactionRevenue', 'thisMonthRevenue', 'newSignupsThisMonth'
        ));
    }

    public function extendSubscription(Request $request, Tenant $tenant)
    {
        $months = max(1, min(12, (int) $request->input('months', 1)));

        $currentEnd = $tenant->subscription_ends_at && $tenant->subscription_ends_at > now()
            ? $tenant->subscription_ends_at
            : now();

        $tenant->subscription_ends_at = $currentEnd->addMonths($months);
        $tenant->status = 'active';
        $tenant->save();

        return back()->with('success', "Extended {$tenant->name} by {$months} month(s). New expiry: {$tenant->subscription_ends_at->format('M d, Y')}");
    }

    public function suspendTenant(Request $request, Tenant $tenant)
    {
        $tenant->subscription_ends_at = now()->subDay();
        $tenant->status = 'suspended';
        $tenant->save();

        return back()->with('success', "{$tenant->name} has been suspended.");
    }

    public function activateTenant(Request $request, Tenant $tenant)
    {
        $tenant->subscription_ends_at = now()->addMonth();
        $tenant->status = 'active';
        $tenant->save();

        return back()->with('success', "{$tenant->name} has been activated with 1 month subscription.");
    }

    public function impersonate(Tenant $tenant)
    {
        // Store original super admin session and redirect to tenant dashboard
        $adminUser = User::where('tenant_id', $tenant->id)->where('role', 'admin')->first();

        if (!$adminUser) {
            return back()->withErrors(['error' => 'No admin user found for this tenant.']);
        }

        session(['impersonating_tenant_id' => $tenant->id, 'impersonating_as' => $adminUser->id]);
        auth()->login($adminUser);

        return redirect()->route('dashboard.index')->with('success', "Now viewing as {$tenant->name}");
    }

    public function deleteTenant(Request $request, Tenant $tenant)
    {
        $request->validate(['confirm_name' => 'required|string']);

        if ($request->confirm_name !== $tenant->name) {
            return back()->withErrors(['error' => 'Tenant name did not match. Deletion cancelled.']);
        }

        $name = $tenant->name;
        $tenant->delete();

        return back()->with('success', "Tenant \"{$name}\" and all associated data deleted permanently.");
    }

    public function bulkEmailForm()
    {
        $totalAdmins = User::whereNotNull('tenant_id')->where('role', 'admin')->count();
        $activeTenantAdmins = User::whereHas('tenant', fn($q) => $q->where('subscription_ends_at', '>', now()))
            ->where('role', 'admin')->count();
        $expiredTenantAdmins = $totalAdmins - $activeTenantAdmins;

        return view('super.bulk-email', compact('totalAdmins', 'activeTenantAdmins', 'expiredTenantAdmins'));
    }

    public function sendBulkEmail(Request $request)
    {
        $validated = $request->validate([
            'subject'     => 'required|string|max:255',
            'body'        => 'required|string|max:5000',
            'action_text' => 'nullable|string|max:100',
            'action_url'  => 'nullable|url|max:255',
            'audience'    => 'required|in:all,active,expired',
        ]);

        $query = User::whereNotNull('tenant_id')->where('role', 'admin');

        if ($validated['audience'] === 'active') {
            $query->whereHas('tenant', fn($q) => $q->where('subscription_ends_at', '>', now()));
        } elseif ($validated['audience'] === 'expired') {
            $query->whereHas('tenant', fn($q) => $q->where(function ($q2) {
                $q2->whereNull('subscription_ends_at')->orWhere('subscription_ends_at', '<=', now());
            }));
        }

        $admins = $query->get();
        $sent = 0;

        foreach ($admins as $admin) {
            try {
                $admin->notify(new \App\Notifications\BulkAdminNotification(
                    $validated['subject'],
                    $validated['body'],
                    $validated['action_text'] ?? '',
                    $validated['action_url'] ?? ''
                ));
                $sent++;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Bulk email failed for {$admin->email}: " . $e->getMessage());
            }
        }

        return back()->with('success', "Email sent to {$sent} ISP admin(s) successfully.");
    }
}
