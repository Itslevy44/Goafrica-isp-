<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Network;
use App\Models\PayoutAccount;
use App\Models\Settlement;
use App\Models\WalletLedgerEntry;
use App\Services\Billing\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(WalletService $walletService)
    {
        $tenant = app('currentTenant');

        $balance       = $walletService->getBalance($tenant);
        $networks      = Network::where('tenant_id', $tenant->id)->with('region')->get();

        // All payout accounts grouped by network
        $payoutAccounts = PayoutAccount::where('tenant_id', $tenant->id)
            ->with('network')
            ->orderBy('network_id')
            ->get();

        // Default (tenant-level) payout account (no network_id)
        $defaultPayoutAccount = $payoutAccounts->whereNull('network_id')->where('is_active', true)->first();

        $settlements = Settlement::where('tenant_id', $tenant->id)
            ->with('payoutAccount')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $ledgerEntries = WalletLedgerEntry::where('tenant_id', $tenant->id)
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        return view('dashboard.wallet.index', compact(
            'balance', 'defaultPayoutAccount', 'payoutAccounts',
            'settlements', 'ledgerEntries', 'tenant', 'networks'
        ));
    }

    public function savePayoutAccount(Request $request)
    {
        $tenant = app('currentTenant');

        $validated = $request->validate([
            'network_id'             => 'nullable|exists:networks,id',
            'method'                 => 'required|in:mpesa,bank',
            'account_identifier'     => 'required|string|max:100',
            'account_name'           => 'required|string|max:255',
            'mpesa_environment'      => 'nullable|in:sandbox,production',
            'mpesa_consumer_key'     => 'nullable|string|max:255',
            'mpesa_consumer_secret'  => 'nullable|string|max:255',
            'mpesa_passkey'          => 'nullable|string|max:255',
            'mpesa_shortcode'        => 'nullable|string|max:50',
        ]);

        $networkId = $validated['network_id'] ?? null;

        // If network_id provided, ensure it belongs to this tenant
        if ($networkId) {
            Network::where('id', $networkId)->where('tenant_id', $tenant->id)->firstOrFail();
        }

        // Deactivate existing account for that scope (tenant-wide or per-network)
        PayoutAccount::where('tenant_id', $tenant->id)
            ->where('network_id', $networkId)
            ->update(['is_active' => false]);

        PayoutAccount::create([
            'tenant_id'             => $tenant->id,
            'network_id'            => $networkId,
            'country_code'          => strtoupper(substr($tenant->country ?? 'KE', 0, 2)),
            'method'                => $validated['method'],
            'account_identifier'    => $validated['account_identifier'],
            'account_name'          => $validated['account_name'],
            'mpesa_environment'     => $validated['mpesa_environment'] ?? null,
            'mpesa_consumer_key'    => $validated['mpesa_consumer_key'] ?? null,
            'mpesa_consumer_secret' => $validated['mpesa_consumer_secret'] ?? null,
            'mpesa_passkey'         => $validated['mpesa_passkey'] ?? null,
            'mpesa_shortcode'       => $validated['mpesa_shortcode'] ?? null,
            'is_active'             => true,
        ]);

        $scope = $networkId ? "for network" : "as default (all networks)";
        return back()->with('success', "Payout account saved {$scope} successfully.");
    }

    public function requestPayout(Request $request, WalletService $walletService)
    {
        $tenant = app('currentTenant');

        $validated = $request->validate([
            'amount'     => 'required|numeric|min:1',
            'network_id' => 'nullable|exists:networks,id',
        ]);

        $networkId = $validated['network_id'] ?? null;

        // Find payout account: network-specific first, then default
        $payoutAccount = PayoutAccount::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->when($networkId, fn($q) => $q->where('network_id', $networkId),
                              fn($q) => $q->whereNull('network_id'))
            ->first();

        if (!$payoutAccount) {
            return back()->withErrors(['error' => 'No active payout account found. Please set one up first.']);
        }

        $amountMinor = (int) round($validated['amount'] * 100);

        try {
            $settlement = $walletService->withdraw($tenant, $payoutAccount, $amountMinor);
            return back()->with('success', "Payout of {$tenant->default_currency} {$validated['amount']} initiated. Ref: {$settlement->gateway_ref}");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
