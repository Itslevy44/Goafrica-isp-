@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Title & Export -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Reports & Analytics</h1>
            <p class="text-sm text-slate-500 mt-1">Detailed overview of M-Pesa collections, vouchers, and queryable transaction history.</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Report
            </button>
            <a href="{{ route('dashboard.reports.export', request()->query()) }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white border border-emerald-600 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- 4 Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <!-- This Month Revenue -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-3">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">This Month Revenue</p>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                {{ $currentTenant->currency ?? 'KES' }} {{ number_format($thisMonthRevenue / 100, 2) }}
            </h3>
            <p class="text-xs text-slate-500 mt-1">Direct M-Pesa deposits</p>
        </div>

        <!-- All Time Revenue -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-3">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Lifetime Revenue</p>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                {{ $currentTenant->currency ?? 'KES' }} {{ number_format($totalRevenue / 100, 2) }}
            </h3>
            <p class="text-xs text-slate-500 mt-1">Total revenue collected</p>
        </div>

        <!-- Total Transactions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-3">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Transactions</p>
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                {{ number_format($transactions->total()) }}
            </h3>
            <p class="text-xs text-slate-500 mt-1">Processed STK pushes & redemptions</p>
        </div>

        <!-- Voucher Usage -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-3">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Vouchers Redeemed</p>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 tracking-tight">
                {{ $usedVouchers }} <span class="text-xs font-semibold text-slate-400">/ {{ $totalVouchers }} total</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">Redemption rate: {{ $totalVouchers > 0 ? round(($usedVouchers / $totalVouchers) * 100, 1) : 0 }}%</p>
        </div>
    </div>

    <!-- Query & Filter Search Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
        <form method="GET" action="{{ route('dashboard.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Search Query</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Phone (e.g. 0718...), Checkout ID, Receipt..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success (Paid)</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed / Cancelled</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-2.5 px-4 rounded-xl shadow-sm transition-colors flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                <a href="{{ route('dashboard.reports.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-colors" title="Reset Filters">
                    ✕
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Two-Column Grid: Transaction History & Top Plans -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- All Transactions Table (Takes up 2 columns) with INDEPENDENT SCROLLING -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-slate-900 text-sm">Transaction Ledger</h3>
                        <span class="text-xs bg-blue-50 text-blue-700 px-2.5 py-0.5 rounded-full font-bold">
                            {{ $transactions->total() }} records
                        </span>
                    </div>
                    @if(request()->hasAny(['search', 'status', 'date_from']))
                    <div class="text-xs font-bold text-emerald-600">
                        Filtered Revenue: {{ $currentTenant->currency ?? 'KES' }} {{ number_format($filteredTotalMinor / 100, 2) }}
                    </div>
                    @endif
                </div>
                
                <!-- INDEPENDENT SCROLLABLE CONTAINER with Sticky Header -->
                <div class="max-h-[520px] overflow-y-auto overflow-x-auto relative">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 bg-slate-100/95 backdrop-blur-xs text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200 z-10">
                            <tr>
                                <th class="py-3 px-5">Date & Time</th>
                                <th class="py-3 px-5">Customer / Device</th>
                                <th class="py-3 px-5">Plan Purchased</th>
                                <th class="py-3 px-5">Amount</th>
                                <th class="py-3 px-5 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($transactions as $txn)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-5 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ $txn->created_at->format('M d, Y') }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $txn->created_at->format('h:i:s A') }}</div>
                                </td>
                                <td class="py-3 px-5">
                                    <div class="font-bold text-slate-900">{{ $txn->customer->phone ?? 'Guest / TV' }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono truncate max-w-[140px]">{{ $txn->gateway_ref }}</div>
                                </td>
                                <td class="py-3 px-5 text-slate-700 font-medium">
                                    {{ $txn->offer->name ?? 'Custom Voucher' }}
                                </td>
                                <td class="py-3 px-5 font-black text-slate-900 whitespace-nowrap">
                                    {{ $txn->currency }} {{ number_format($txn->amount_minor / 100, 2) }}
                                </td>
                                <td class="py-3 px-5 text-right whitespace-nowrap">
                                    @if($txn->status === 'success')
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('dashboard.receipts.show', $txn->id) }}" target="_blank"
                                               class="text-blue-600 hover:text-blue-800 text-[11px] font-bold underline">Receipt</a>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                                Success
                                            </span>
                                        </div>
                                    @elseif($txn->status === 'failed')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                            Failed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-ping"></span>
                                            {{ ucfirst($txn->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 px-6 text-center text-slate-400">
                                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    No transactions match your search query.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($transactions->hasPages())
                <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50">
                    {{ $transactions->links() }}
                </div>
                @endif
            </div>
        </div>
        
        <!-- Top Packages & Pro Tip -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-sm">Top Selling Plans</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($topOffers as $index => $stat)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-7 h-7 rounded-lg {{ $index === 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center font-bold text-xs mr-3">
                                    #{{ $index + 1 }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">{{ $stat->offer->name ?? 'Special Package' }}</h4>
                                    <p class="text-[11px] text-slate-400">{{ $stat->total_sales }} purchases</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-black text-slate-900">{{ $currentTenant->currency ?? 'KES' }} {{ number_format($stat->total_revenue / 100, 0) }}</div>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="border-slate-100">
                        @endif
                        @empty
                        <div class="text-center text-slate-400 text-xs py-4">No purchases recorded yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-md p-6 text-white relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-white/10 rounded-full blur-xl"></div>
                <h3 class="font-bold text-sm mb-2 flex items-center gap-2">
                    <span>💡</span> Revenue Insights
                </h3>
                <p class="text-xs text-blue-100 leading-relaxed">
                    Filter by date or phone number to quickly audit customer payments during peak hotspot hours.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
