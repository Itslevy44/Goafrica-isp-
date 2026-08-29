@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Customer CRM</h2>
    <p class="text-sm text-slate-500 mt-1">Manage your hotspot users, view their lifetime value, and enforce access control.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">All Customers</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 bg-white">
                    <th class="py-3 px-6 font-semibold text-xs text-slate-500 uppercase tracking-wider">Phone / MAC</th>
                    <th class="py-3 px-6 font-semibold text-xs text-slate-500 uppercase tracking-wider">First Seen</th>
                    <th class="py-3 px-6 font-semibold text-xs text-slate-500 uppercase tracking-wider">Last Active</th>
                    <th class="py-3 px-6 font-semibold text-xs text-slate-500 uppercase tracking-wider">Total Value</th>
                    <th class="py-3 px-6 font-semibold text-xs text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-6 font-semibold text-xs text-slate-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($customers as $customer)
                <tr class="hover:bg-slate-50 transition-colors {{ $customer->is_banned ? 'bg-red-50/30' : '' }}">
                    <td class="py-3 px-6">
                        <div class="font-bold text-slate-800 text-sm">{{ $customer->phone }}</div>
                        <div class="text-xs text-slate-500 font-mono mt-0.5">{{ $customer->latest_mac ?? 'No MAC recorded' }}</div>
                    </td>
                    <td class="py-3 px-6 text-sm text-slate-600">
                        {{ $customer->first_seen_at ? $customer->first_seen_at->format('M d, Y') : 'Unknown' }}
                    </td>
                    <td class="py-3 px-6 text-sm text-slate-600">
                        {{ $customer->last_seen_at ? $customer->last_seen_at->diffForHumans() : 'Never' }}
                        <div class="text-xs text-slate-400 mt-0.5">{{ $customer->sessions_count }} total sessions</div>
                    </td>
                    <td class="py-3 px-6 font-medium text-slate-800 text-sm">
                        {{ app('currentTenant')->currency ?? 'KES' }} {{ number_format($customer->total_spent, 2) }}
                    </td>
                    <td class="py-3 px-6">
                        @if($customer->is_banned)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                Banned
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                Active
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-right">
                        <form action="{{ route('dashboard.customers.toggleBan', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to {{ $customer->is_banned ? 'unban' : 'ban' }} this customer?');">
                            @csrf
                            @if($customer->is_banned)
                                <button type="submit" class="text-emerald-600 hover:text-emerald-900 font-medium text-sm transition-colors px-3 py-1 bg-emerald-50 hover:bg-emerald-100 rounded-md">
                                    Unban
                                </button>
                            @else
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm transition-colors px-3 py-1 bg-red-50 hover:bg-red-100 rounded-md">
                                    Ban User
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 px-6 text-center text-slate-500 text-sm">No customers recorded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($customers->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
        {{ $customers->links('pagination::tailwind') }}
    </div>
    @endif
</div>
@endsection
