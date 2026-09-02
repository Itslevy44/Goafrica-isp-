<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\InternetSession;
use App\Models\Network;
use App\Services\Billing\BillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $tenant = app('currentTenant');

        $query = InternetSession::with(['customer', 'device', 'network'])
            ->where('tenant_id', $tenant->id);

        // Filter by status (default to active)
        $status = $request->input('status', 'active');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by device/router
        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        // Filter by network
        if ($request->filled('network_id')) {
            $query->where('network_id', $request->network_id);
        }

        // Search by MAC or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('mac_address', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('phone', 'LIKE', "%{$search}%"));
            });
        }

        $sessions = $query->orderBy('started_at', 'desc')->paginate(25)->withQueryString();

        $devices  = Device::where('tenant_id', $tenant->id)->get();
        $networks = Network::where('tenant_id', $tenant->id)->get();

        // Stats
        $activeCount  = InternetSession::where('tenant_id', $tenant->id)->where('status', 'active')->where('ends_at', '>', now())->count();
        $expiredToday = InternetSession::where('tenant_id', $tenant->id)->where('status', 'expired')->whereDate('ends_at', today())->count();

        return view('dashboard.sessions.index', compact('sessions', 'devices', 'networks', 'status', 'activeCount', 'expiredToday'));
    }

    public function kick(InternetSession $session, BillingService $billingService)
    {
        $tenant = app('currentTenant');

        if ($session->tenant_id !== $tenant->id) {
            abort(403);
        }

        try {
            $device = $session->device;
            if ($device) {
                $driver = $billingService->resolveDeviceDriver($device);
                $driver->disconnectUser($session->mac_address);
            }
            $session->update(['status' => 'expired', 'ends_at' => now()]);

            return back()->with('success', "Session for {$session->mac_address} has been terminated.");
        } catch (\Exception $e) {
            Log::error('Failed to kick session: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Could not disconnect from router: ' . $e->getMessage()]);
        }
    }
}
