@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.customers.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $customer->phone }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">Customer since {{ $customer->first_seen_at?->format('M d, Y') ?? 'Unknown' }}</p>
            </div>
        </div>
        <form action="{{ route('dashboard.customers.toggleBan', $customer->id) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to {{ $customer->is_banned ? 'unban' : 'ban' }} this customer?')">
            @csrf
            @if($customer->is_banned)
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Unban Customer
                </button>
            @else
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    Ban Customer
                </button>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Spent</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ app('currentTenant')->currency ?? 'KES' }} {{ number_format($totalSpent, 2) }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Sessions</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $sessions->total() }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Last Active</p>
            <h3 class="text-lg font-black text-slate-900 mt-1">{{ $customer->last_seen_at ? $customer->last_seen_at->diffForHumans() : 'Never' }}</h3>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Account Status</p>
            <div class="mt-2">
                @if($customer->is_banned)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">🚫 Banned</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">✓ Active</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Transactions & Sessions Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Transaction History -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Transaction History</h3>
                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-bold">{{ $transactions->total() }} total</span>
            </div>
            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                <table class="w-full text-xs">
                    <thead class="sticky top-0 bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="py-2 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="py-2 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Plan</th>
                            <th class="py-2 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="py-2 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="py-2 px-4 text-right font-bold text-slate-500 uppercase tracking-wider">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $txn)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2.5 px-4 text-slate-600">{{ $txn->created_at->format('M d, Y') }}</td>
                            <td class="py-2.5 px-4 font-medium text-slate-800">{{ $txn->offer->name ?? 'Voucher' }}</td>
                            <td class="py-2.5 px-4 font-bold text-slate-900">{{ $txn->currency }} {{ number_format($txn->amount_minor / 100, 2) }}</td>
                            <td class="py-2.5 px-4">
                                @if($txn->status === 'success')
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">Paid</span>
                                @elseif($txn->status === 'failed')
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Failed</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700">Pending</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                @if($txn->status === 'success')
                                <a href="{{ route('dashboard.receipts.show', $txn->id) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800 font-bold underline">View</a>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-6 text-center text-slate-400">No transactions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 bg-slate-50 text-xs">{{ $transactions->links() }}</div>
            @endif
        </div>

        <!-- Session History -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Session History</h3>
                <span class="text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded-full font-bold">{{ $sessions->total() }} total</span>
            </div>
            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                <table class="w-full text-xs">
                    <thead class="sticky top-0 bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="py-2 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Started</th>
                            <th class="py-2 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">MAC Address</th>
                            <th class="py-2 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Duration</th>
                            <th class="py-2 px-4 text-left font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sessions as $session)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2.5 px-4 text-slate-600">{{ $session->started_at?->format('M d, Y h:i A') }}</td>
                            <td class="py-2.5 px-4 font-mono text-slate-800">{{ $session->mac_address }}</td>
                            <td class="py-2.5 px-4 text-slate-700">
                                @if($session->started_at && $session->ends_at)
                                    {{ $session->started_at->diffForHumans($session->ends_at, true) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="py-2.5 px-4">
                                @if($session->status === 'active' && $session->ends_at > now())
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">Active</span>
                                @elseif($session->status === 'banned')
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Banned</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500">Expired</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-6 text-center text-slate-400">No sessions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sessions->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 bg-slate-50 text-xs">{{ $sessions->links() }}</div>
            @endif
        </div>
    </div>

    <!-- Ban History -->
    @if($banEvents->isNotEmpty())
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800">Ban / Unban History</h3>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($banEvents as $event)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <span class="text-sm font-bold {{ str_contains($event->action, 'Ban') && !str_contains($event->action, 'Unban') ? 'text-red-600' : 'text-emerald-600' }}">
                        {{ $event->action }}
                    </span>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $event->description }}</p>
                </div>
                <span class="text-xs text-slate-400">{{ $event->created_at->format('M d, Y h:i A') }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
