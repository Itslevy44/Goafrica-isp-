<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceCommandLog;
use App\Models\InternetSession;
use App\Models\PayoutAccount;
use App\Models\Transaction;
use App\Services\Billing\WalletService;
use App\Services\Billing\BillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(WalletService $walletService)
    {
        $tenant = app('currentTenant');
        
        $balance = $walletService->getBalance($tenant);
        $payoutAccount = PayoutAccount::where('tenant_id', $tenant->id)->where('is_active', true)->first();
        
        $recentTransactions = Transaction::with('offer', 'customer')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $activeSessions = InternetSession::with('customer')
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->orderBy('started_at', 'desc')
            ->limit(10)
            ->get();

        $devices = Device::all();

        // 7-Day Revenue Chart Data
        $revenueData = Transaction::where('tenant_id', $tenant->id)
            ->where('status', 'success')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(amount_minor) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M d');
            $chartData[] = isset($revenueData[$date]) ? ($revenueData[$date]->total / 100) : 0;
        }

        return view('dashboard.index', compact('balance', 'recentTransactions', 'activeSessions', 'devices', 'tenant', 'payoutAccount', 'chartLabels', 'chartData'));
    }

    public function cmd()
    {
        $tenant = app('currentTenant');
        $devices = Device::all();
        $logs = DeviceCommandLog::with('user', 'device')->orderBy('created_at', 'desc')->limit(50)->get();

        return view('dashboard.cmd', compact('devices', 'logs', 'tenant'));
    }

    public function runCmd(Request $request, BillingService $billingService)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'command' => 'required|string',
        ]);

        $device = Device::findOrFail($request->device_id);
        $command = $request->command;

        try {
            $driver = $billingService->resolveDeviceDriver($device);
            $response = $driver->runCommand($command);

            DeviceCommandLog::create([
                'tenant_id' => app('currentTenant')->id,
                'device_id' => $device->id,
                'user_id' => Auth::id(),
                'command' => $command,
                'response' => $response,
            ]);

            return back()->with('success', 'Command executed successfully.')->with('cmd_response', $response);
        } catch (\Exception $e) {
            return back()->withErrors(['cmd_error' => 'Command execution failed: ' . $e->getMessage()]);
        }
    }
}
