<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\InternetSession;
use App\Models\SystemEvent;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function show(Customer $customer)
    {
        $tenant = app('currentTenant');

        if ($customer->tenant_id !== $tenant->id) {
            abort(403);
        }

        $sessions = InternetSession::where('customer_id', $customer->id)
            ->with('device', 'network')
            ->orderBy('started_at', 'desc')
            ->paginate(15);

        $transactions = \App\Models\Transaction::where('customer_id', $customer->id)
            ->with('offer')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalSpent = \App\Models\Transaction::where('customer_id', $customer->id)
            ->where('status', 'success')
            ->sum('amount_minor') / 100;

        $banEvents = \App\Models\SystemEvent::where('tenant_id', $tenant->id)
            ->where('description', 'LIKE', "%{$customer->phone}%")
            ->where('action', 'LIKE', '%Customer%')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.customers.show', compact('customer', 'sessions', 'transactions', 'totalSpent', 'banEvents'));
    }

    public function index()
    {
        $tenant = app('currentTenant');

        $customers = Customer::where('tenant_id', $tenant->id)
            ->withCount('sessions')
            ->withSum(['transactions' => function ($query) {
                $query->where('status', 'success');
            }], 'amount_minor')
            ->orderBy('last_seen_at', 'desc')
            ->paginate(15);

        // Fetch latest session for each customer to get MAC address
        foreach ($customers as $customer) {
            $latestSession = InternetSession::where('customer_id', $customer->id)
                ->orderBy('created_at', 'desc')
                ->first();
            $customer->latest_mac = $latestSession ? $latestSession->mac_address : null;
            $customer->total_spent = ($customer->transactions_sum_amount_minor ?? 0) / 100;
        }

        return view('dashboard.customers.index', compact('customers'));
    }

    public function toggleBan(Customer $customer, Request $request)
    {
        // Ensure customer belongs to tenant
        if ($customer->tenant_id !== app('currentTenant')->id) {
            abort(403);
        }

        $customer->is_banned = !$customer->is_banned;
        $customer->save();
        
        $action = $customer->is_banned ? 'Banned' : 'Unbanned';

        // Disconnect from Router if banned
        if ($customer->is_banned) {
            $activeSessions = InternetSession::where('customer_id', $customer->id)
                ->where('status', 'active')
                ->get();

            $billingService = app(\App\Services\Billing\BillingService::class);

            foreach ($activeSessions as $session) {
                try {
                    $device = $session->device;
                    if ($device) {
                        $driver = $billingService->resolveDeviceDriver($device);
                        $driver->disconnectUser($session->mac_address);
                    }
                    $session->update(['status' => 'banned', 'ends_at' => now()]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Failed to kick banned user from router: " . $e->getMessage());
                }
            }
        }

        SystemEvent::create([
            'tenant_id' => $customer->tenant_id,
            'user_id' => Auth::id(),
            'action' => "{$action} Customer",
            'description' => "{$action} customer with phone {$customer->phone}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Customer successfully {$action}.");
    }
}
