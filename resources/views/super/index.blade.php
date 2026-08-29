@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Super Admin Dashboard</h2>
        <p class="text-sm text-slate-500 mt-1">Manage all SaaS Tenants (ISPs) and view global revenue.</p>
    </div>
</div>

@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-3 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
@endif

<!-- Top Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Active Tenants -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Active ISPs</h3>
            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
        <div class="text-3xl font-black text-slate-800">{{ number_format($activeTenants) }}</div>
    </div>
    
    <!-- Expired Tenants -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Expired / Suspended</h3>
            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="text-3xl font-black text-slate-800">{{ number_format($expiredTenants) }}</div>
    </div>

    <!-- MRR -->
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl shadow-lg border border-blue-500 p-6 flex flex-col justify-center text-white transition-all hover:shadow-xl lg:col-span-2 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
        <div class="flex items-center justify-between mb-4 relative z-10">
            <h3 class="text-sm font-semibold text-blue-100 uppercase tracking-wider">Estimated Monthly Recurring Revenue</h3>
            <div class="w-10 h-10 rounded-lg bg-white/20 text-white flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="text-4xl font-black tracking-tight relative z-10">KES {{ number_format($monthlyRecurringRevenue, 2) }}</div>
    </div>
</div>

<!-- Tenants List -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
        <h3 class="font-bold text-slate-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            All Tenant ISPs
        </h3>
        <span class="bg-slate-200 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ number_format($totalTenants) }} Total</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-white text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-semibold">ISP Name / Email</th>
                    <th class="px-6 py-3 font-semibold">Usage</th>
                    <th class="px-6 py-3 font-semibold">Status</th>
                    <th class="px-6 py-3 font-semibold">Expires On</th>
                    <th class="px-6 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($tenants as $t)
                    @php
                        $isActive = $t->subscription_ends_at && $t->subscription_ends_at > now();
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $t->name }}</div>
                            <div class="text-xs text-slate-500">{{ $t->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-600">
                                <strong>{{ $t->networks_count }}</strong> Networks<br>
                                <strong>{{ $t->users_count }}</strong> Admins
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($isActive)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                    Expired/Suspended
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $t->subscription_ends_at ? $t->subscription_ends_at->format('M d, Y') : 'Never' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <form action="{{ route('super.tenants.extend', $t) }}" method="POST" class="inline-block" onsubmit="return confirm('Extend this tenant\'s subscription by 1 month (manual override)?');">
                                    @csrf
                                    <input type="hidden" name="months" value="1">
                                    <button type="submit" class="bg-blue-50 hover:bg-blue-100 text-blue-600 font-medium py-1.5 px-3 rounded text-xs transition-colors border border-blue-200">
                                        +1 Month
                                    </button>
                                </form>
                                @if($isActive)
                                <form action="{{ route('super.tenants.suspend', $t) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to suspend this tenant? This will immediately lock them out of the dashboard.');">
                                    @csrf
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-medium py-1.5 px-3 rounded text-xs transition-colors border border-red-200">
                                        Suspend
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <p class="font-medium">No tenants registered yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
