@extends('layouts.app')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Wallet & Payouts</h2>
        <p class="text-sm text-slate-500 mt-1">Manage your M-Pesa credentials per network and request withdrawals.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm font-medium">{{ $errors->first() }}</div>
    @endif

    {{-- ============================================================
         TOP ROW — Balance card + Quick payout
         ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Balance --}}
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-xl">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Total Wallet Balance</p>
            <h2 class="text-4xl font-black tracking-tight">
                {{ $tenant->default_currency ?? 'KES' }} {{ number_format($balance / 100, 2) }}
            </h2>
            <p class="text-slate-400 text-xs mt-2">Accrued from all customer payments across all networks</p>

            <div class="mt-5 border-t border-slate-700 pt-5">
                <p class="text-xs text-slate-400 font-medium mb-2">Default Payout Account</p>
                @if($defaultPayoutAccount)
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center font-bold text-xs text-white">
                            {{ strtoupper(substr($defaultPayoutAccount->method, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold">{{ $defaultPayoutAccount->account_name }}</div>
                            <div class="text-xs text-slate-400 font-mono">{{ $defaultPayoutAccount->account_identifier }}</div>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-amber-400">⚠ No default payout account set yet.</p>
                @endif
            </div>
        </div>

        {{-- Quick withdraw --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Request Payout
            </h3>
            @if($defaultPayoutAccount || $payoutAccounts->where('is_active', true)->count())
            <form action="{{ route('dashboard.wallet.payout') }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Amount ({{ $tenant->default_currency ?? 'KES' }})</label>
                        <input type="number" name="amount" step="0.01" min="1" max="{{ $balance / 100 }}"
                               placeholder="e.g. 500.00" required
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">From Network</label>
                        <select name="network_id" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">Default account (all networks)</option>
                            @foreach($networks as $network)
                                @if($payoutAccounts->where('network_id', $network->id)->where('is_active', true)->count())
                                <option value="{{ $network->id }}">{{ $network->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm shadow-sm transition-colors">
                        Withdraw
                    </button>
                </div>
            </form>
            @else
            <p class="text-sm text-slate-500 bg-amber-50 border border-amber-200 px-4 py-3 rounded-xl">
                Set up a payout account below before requesting a withdrawal.
            </p>
            @endif
        </div>
    </div>

    {{-- ============================================================
         NETWORK M-PESA ACCOUNTS
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">M-Pesa & Payout Accounts</h3>
                    <p class="text-xs text-slate-500">Each network can have its own Till/Paybill. You can reuse the same shortcode across networks.</p>
                </div>
            </div>
            <button onclick="document.getElementById('add-account-modal').classList.remove('hidden')"
                    class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5 border border-blue-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Account
            </button>
        </div>

        @if($payoutAccounts->isEmpty())
        <div class="p-10 text-center text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            <p class="text-sm font-medium text-slate-500">No payout accounts yet.</p>
        </div>
        @else
        <div class="divide-y divide-slate-100">
            @foreach($payoutAccounts as $account)
            <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 hover:bg-slate-50 transition-colors">
                <div class="w-10 h-10 rounded-xl {{ $account->method === 'mpesa' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center font-black text-sm flex-shrink-0">
                    {{ $account->method === 'mpesa' ? 'M' : 'B' }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-bold text-slate-900 text-sm">{{ $account->account_name }}</span>
                        <span class="font-mono text-xs text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">{{ $account->account_identifier }}</span>
                        @if($account->mpesa_shortcode && $account->mpesa_shortcode !== $account->account_identifier)
                        <span class="text-xs text-slate-400">Till: <span class="font-mono font-bold text-slate-600">{{ $account->mpesa_shortcode }}</span></span>
                        @endif
                        @if($account->network)
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600">📡 {{ $account->network->name }}</span>
                        @else
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-500">Default (all networks)</span>
                        @endif
                        @if($account->mpesa_environment)
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $account->mpesa_environment === 'production' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ ucfirst($account->mpesa_environment) }}
                        </span>
                        @endif
                        @if($account->is_active)
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Active</span>
                        @endif
                    </div>
                    <div class="text-xs text-slate-400 mt-0.5 capitalize">{{ str_replace('_', ' ', $account->method) }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ============================================================
         PAYOUT HISTORY + LEDGER
         ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Payout history --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Payout History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Ref</th>
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($settlements as $settlement)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2.5 px-4 text-slate-600">{{ $settlement->created_at->format('M d, Y') }}</td>
                            <td class="py-2.5 px-4 font-bold text-slate-900">{{ $settlement->currency }} {{ number_format($settlement->net_amount_minor / 100, 2) }}</td>
                            <td class="py-2.5 px-4 font-mono text-slate-500 truncate max-w-[100px]">{{ $settlement->gateway_ref ?? '—' }}</td>
                            <td class="py-2.5 px-4">
                                @if($settlement->status === 'paid')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Paid</span>
                                @elseif($settlement->status === 'pending')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">Pending</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">Failed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-8 text-center text-slate-400">No payouts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($settlements->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 bg-slate-50">{{ $settlements->links() }}</div>
            @endif
        </div>

        {{-- Ledger --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Wallet Ledger <span class="text-xs font-normal text-slate-400">(last 20)</span></h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="py-2.5 px-4 text-right font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="py-2.5 px-4 text-right font-bold text-slate-500 uppercase tracking-wider">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($ledgerEntries as $entry)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2.5 px-4 text-slate-500">{{ $entry->created_at->format('M d, h:i A') }}</td>
                            <td class="py-2.5 px-4">
                                @if($entry->entry_type === 'sale_credit')
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">+ Sale</span>
                                @elseif($entry->entry_type === 'payout_debit')
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">− Payout</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500">{{ $entry->entry_type }}</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right font-bold {{ $entry->entry_type === 'payout_debit' ? 'text-red-600' : 'text-emerald-600' }}">
                                {{ $entry->entry_type === 'payout_debit' ? '−' : '+' }}{{ $entry->currency }} {{ number_format($entry->amount_minor / 100, 2) }}
                            </td>
                            <td class="py-2.5 px-4 text-right font-bold text-slate-800">
                                {{ $entry->currency }} {{ number_format($entry->balance_after_minor / 100, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-8 text-center text-slate-400">No ledger entries yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ======== ADD PAYOUT ACCOUNT MODAL ======== --}}
<div id="add-account-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center sticky top-0 bg-white z-10">
            <h3 class="text-lg font-bold text-slate-800">Add Payout Account</h3>
            <button onclick="document.getElementById('add-account-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('dashboard.wallet.payout-account') }}" method="POST" class="p-6 space-y-5">
            @csrf

            {{-- Network scope --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Assign to Network</label>
                <select name="network_id" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Default — applies to all networks without a specific account</option>
                    @foreach($networks as $network)
                    <option value="{{ $network->id }}">{{ $network->name }} (/connect/{{ $network->slug }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">You can assign the same Till number to multiple networks.</p>
            </div>

            {{-- Payout method --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Method</label>
                    <select name="method" id="method-select" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500" required onchange="toggleMpesaFields()">
                        <option value="mpesa">M-Pesa</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Account Name</label>
                    <input type="text" name="account_name" placeholder="e.g. John Doe" required
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Phone / Account Number</label>
                <input type="text" name="account_identifier" placeholder="e.g. 0712345678 or bank account" required
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- M-Pesa Daraja fields --}}
            <div id="mpesa-fields" class="space-y-4 border-t border-slate-100 pt-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    M-Pesa Daraja API Credentials
                    <span class="font-normal normal-case text-slate-400">(optional — leave blank to use global credentials)</span>
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Environment</label>
                        <select name="mpesa_environment" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">Use global setting</option>
                            <option value="sandbox">Sandbox (Testing)</option>
                            <option value="production">Production (Live)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Till / Paybill Shortcode</label>
                        <input type="text" name="mpesa_shortcode" placeholder="e.g. 174379"
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Consumer Key</label>
                        <input type="password" name="mpesa_consumer_key" placeholder="Daraja consumer key"
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Consumer Secret</label>
                        <input type="password" name="mpesa_consumer_secret" placeholder="Daraja consumer secret"
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Passkey</label>
                    <input type="password" name="mpesa_passkey" placeholder="Daraja STK passkey"
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm shadow-sm transition-colors">
                Save Payout Account
            </button>
        </form>
    </div>
</div>

<script>
function toggleMpesaFields() {
    const isMpesa = document.getElementById('method-select').value === 'mpesa';
    document.getElementById('mpesa-fields').style.display = isMpesa ? 'block' : 'none';
}
</script>
@endsection
