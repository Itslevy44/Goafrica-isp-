<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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

        $balance      = $walletService->getBalance($tenant);
        $payoutAccount = PayoutAccount::where('tenant_id', $tenant->id)->where('is_active', true)->first();

        $settlements = Settlement::where('tenant_id', $tenant->id)
            ->with('payoutAccount')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $ledgerEntries = WalletLedgerEntry::where('tenant_id', $tenant->id)
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        return view('dashboard.wallet.index', compact('balance', 'payoutAccount', 'settlements', 'ledgerEntries', 'tenant'));
    }

    public function savePayoutAccount(Request $request)
    {
        $tenant = app('currentTenant');

        $validated = $request->validate([
            'method'             => 'required|in:mpesa,bank',
            'account_identifier' => 'required|string|max:100',
            'account_name'       => 'required|string|max:255',
        ]);

        // Deactivate old accounts
        PayoutAccount::where('tenant_id', $tenant->id)->update(['is_active' => false]);

        PayoutAccount::create([
            'tenant_id'          => $tenant->id,
            'country_code'       => strtoupper(substr($tenant->country ?? 'KE', 0, 2)),
            'method'             => $validated['method'],
            'account_identifier' => $validated['account_identifier'],
            'account_name'       => $validated['account_name'],
            'is_active'          => true,
        ]);

        return back()->with('success', 'Payout account saved successfully.');
    }

    public function requestPayout(Request $request, WalletService $walletService)
    {
        $tenant = app('currentTenant');

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $payoutAccount = PayoutAccount::where('tenant_id', $tenant->id)->where('is_active', true)->first();

        if (!$payoutAccount) {
            return back()->withErrors(['error' => 'Please set up a payout account first.']);
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
