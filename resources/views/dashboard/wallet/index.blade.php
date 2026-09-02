@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Wallet & Payouts</h2>
        <p class="text-sm text-slate-500 mt-1">View your earnings and request withdrawals to your M-Pesa or bank account.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">{{ $errors->first() }}</div>
    @endif

    <!-- Balance + Payout Request Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Balance Card -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-xl lg:col-span-1">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Available Balance</p>
            <h2 class="text-4xl font-black tracking-tight">
                {{ $tenant->default_currency ?? 'KES' }} {{ number_format($balance / 100, 2) }}
            </h2>
            <p class="text-slate-400 text-xs mt-2">Accrued from customer payments</p>

            <div class="mt-6 border-t border-slate-700 pt-5">
                <p class="text-xs text-slate-400 font-medium mb-2">Payout Account</p>
                @if($payoutAccount)
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center font-bold text-xs">
                            {{ strtoupper(substr($payoutAccount->method, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold">{{ $payoutAccount->account_name }}</div>
                            <div class="text-xs text-slate-400 font-mono">{{ $payoutAccount->account_identifier }}</div>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-amber-400">⚠ No payout account set up yet.</p>
                @endif
            </div>
        </div>

        <!-- Request Payout + Account Setup -->
        <div class="lg:col-span-2 space-y-4">

            <!-- Request Payout -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Request Payout
                </h3>
                @if($payoutAccount)
                <form action="{{ route('dashboard.wallet.payout') }}" method="POST" class="flex items-end gap-3">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Amount ({{ $tenant->default_currency ?? 'KES' }})
                        </label>
                        <input type="number" name="amount" step="0.01" min="1"
                               max="{{ $balance / 100 }}"
                               placeholder="e.g. 500.00"
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-5 py-2.5 rounded-lg text-sm shadow-sm transition-colors whitespace-nowrap"
                            onclick="return confirm('Withdraw to {{ $payoutAccount->account_identifier }}?')">
                        Withdraw
                    </button>
                </form>
                <p class="text-xs text-slate-400 mt-2">Funds will be sent to: <strong>{{ $payoutAccount->account_name }}</strong> ({{ $payoutAccount->account_identifier }})</p>
                @else
                <p class="text-sm text-slate-500 bg-amber-50 border border-amber-200 px-4 py-3 rounded-lg">
                    Please set up a payout account below before requesting a withdrawal.
                </p>
                @endif
            </div>

            <!-- Setup Payout Account -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    {{ $payoutAccount ? 'Update' : 'Set Up' }} Payout Account
                </h3>
                <form action="{{ route('dashboard.wallet.payout-account') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Method</label>
                            <select name="method" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" required>
                                <option value="mpesa" {{ $payoutAccount?->method === 'mpesa' ? 'selected' : '' }}>M-Pesa</option>
                                <option value="bank" {{ $payoutAccount?->method === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Phone / Account No.</label>
                            <input type="text" name="account_identifier"
                                   value="{{ old('account_identifier', $payoutAccount?->account_identifier) }}"
                                   placeholder="e.g. 0712345678"
                                   class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Account Name</label>
                            <input type="text" name="account_name"
                                   value="{{ old('account_name', $payoutAccount?->account_name) }}"
                                   placeholder="e.g. John Doe"
                                   class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-lg text-sm shadow-sm transition-colors">
                        Save Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Payout History + Ledger -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Payout History -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Payout History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Reference</th>
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($settlements as $settlement)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2.5 px-4 text-slate-600">{{ $settlement->created_at->format('M d, Y') }}</td>
                            <td class="py-2.5 px-4 font-bold text-slate-900">{{ $settlement->currency }} {{ number_format($settlement->net_amount_minor / 100, 2) }}</td>
                            <td class="py-2.5 px-4 font-mono text-slate-500">{{ $settlement->gateway_ref ?? '—' }}</td>
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

        <!-- Ledger Entries -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Wallet Ledger <span class="text-xs font-normal text-slate-400">(last 20 entries)</span></h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="py-2.5 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="py-2.5 px-4 text-right font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="py-2.5 px-4 text-right font-bold text-slate-500 uppercase tracking-wider">Balance After</th>
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
@endsection
