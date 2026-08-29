@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Reports & Analytics</h2>
        <p class="text-sm text-slate-500 mt-1">Detailed overview of your network's financial performance and usage.</p>
    </div>
    <div>
        <button onclick="window.print()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors flex items-center">
            <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Export Report
        </button>
    </div>
</div>

<!-- Key Metrics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- This Month Revenue -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">This Month Revenue</p>
                <h3 class="text-2xl font-bold text-slate-800">
                    {{ $tenant->currency ?? 'KES' }} {{ number_format($thisMonthRevenue / 100, 0) }}
                </h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- All Time Revenue -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Total All-Time Revenue</p>
                <h3 class="text-2xl font-bold text-slate-800">
                    {{ $tenant->currency ?? 'KES' }} {{ number_format($totalRevenue / 100, 0) }}
                </h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
    </div>

    <!-- Total Transactions -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Total Transactions</p>
                <h3 class="text-2xl font-bold text-slate-800">
                    {{ number_format($transactions->total()) }}
                </h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
        </div>
    </div>

    <!-- Voucher Usage -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Vouchers Used</p>
                <h3 class="text-2xl font-bold text-slate-800">
                    {{ $usedVouchers }} <span class="text-sm font-normal text-slate-400">/ {{ $totalVouchers }}</span>
                </h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- All Transactions Table (Takes up 2 columns) -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Transaction History</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-white">
                            <th class="py-3 px-6 font-semibold text-xs text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="py-3 px-6 font-semibold text-xs text-slate-500 uppercase tracking-wider">Customer</th>
                            <th class="py-3 px-6 font-semibold text-xs text-slate-500 uppercase tracking-wider">Plan</th>
                            <th class="py-3 px-6 font-semibold text-xs text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="py-3 px-6 font-semibold text-xs text-slate-500 uppercase tracking-wider text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $txn)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-6 text-sm text-slate-600 whitespace-nowrap">
                                {{ $txn->created_at->format('M d, Y') }}
                                <div class="text-xs text-slate-400">{{ $txn->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="font-medium text-slate-800 text-sm">{{ $txn->customer->phone ?? 'Unknown' }}</div>
                                <div class="text-xs text-slate-500">{{ $txn->customer->mac_address ?? '' }}</div>
                            </td>
                            <td class="py-3 px-6 text-sm text-slate-600">
                                {{ $txn->offer->name ?? 'Custom / Voucher' }}
                            </td>
                            <td class="py-3 px-6 font-medium text-slate-800 text-sm">
                                {{ $txn->currency }} {{ number_format($txn->amount_minor / 100, 2) }}
                            </td>
                            <td class="py-3 px-6 text-right">
                                @if($txn->status === 'success')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        Success
                                    </span>
                                @elseif($txn->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ ucfirst($txn->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 px-6 text-center text-slate-500 text-sm">No transactions recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $transactions->links('pagination::tailwind') }}
            </div>
            @endif
        </div>
    </div>
    
    <!-- Top Packages -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Top Selling Plans</h3>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    @forelse($topOffers as $index => $stat)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center font-bold text-sm mr-3">
                                #{{ $index + 1 }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">{{ $stat->offer->name ?? 'Unknown' }}</h4>
                                <p class="text-xs text-slate-500">{{ $stat->total_sales }} purchases</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-slate-800">{{ $tenant->currency ?? 'KES' }} {{ number_format($stat->total_revenue / 100, 0) }}</div>
                        </div>
                    </div>
                    @if(!$loop->last)
                        <hr class="border-slate-100">
                    @endif
                    @empty
                    <div class="text-center text-slate-500 text-sm py-4">Not enough data to determine top plans.</div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Info Card -->
        <div class="mt-6 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl shadow-sm p-6 text-white">
            <h3 class="font-bold text-lg mb-2 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Pro Tip
            </h3>
            <p class="text-sm text-blue-100 leading-relaxed">
                Use these reports to identify which internet plans are most popular. Consider creating special offers or bundles around your top-selling packages to increase revenue!
            </p>
        </div>
    </div>
</div>
@endsection
