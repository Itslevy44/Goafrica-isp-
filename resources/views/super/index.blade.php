@extends('layouts.app')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Super Admin Dashboard</h2>
            <p class="text-sm text-slate-500 mt-1">Platform-wide overview of all ISPs, customers, and revenue.</p>
        </div>
        <span class="text-xs font-bold bg-purple-100 text-purple-700 px-3 py-1.5 rounded-full flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.956 11.956 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Super Admin Mode
        </span>
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

    {{-- ======= SAAS METRICS ======= --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active ISPs</p>
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $activeTenants }}</div>
            <p class="text-xs text-slate-400 mt-1">{{ $trialTenants }} expiring in 3 days</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Expired / Suspended</p>
                <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $expiredTenants }}</div>
            <p class="text-xs text-slate-400 mt-1">{{ $newSignupsThisMonth }} new this month</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Customers</p>
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ number_format($totalCustomers) }}</div>
            <p class="text-xs text-slate-400 mt-1">{{ number_format($activeSessions) }} active sessions</p>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-5 text-white hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-blue-200 uppercase tracking-wider">SaaS MRR</p>
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black">KES {{ number_format($monthlyRecurringRevenue) }}</div>
            <p class="text-xs text-blue-200 mt-1">{{ $activeTenants }} × KES 500/mo</p>
        </div>
    </div>

    {{-- ======= PLATFORM REVENUE ======= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Platform-Wide Revenue (This Month)</p>
            <div class="text-2xl font-black text-emerald-600">KES {{ number_format($thisMonthRevenue / 100, 2) }}</div>
            <p class="text-xs text-slate-400 mt-1">All customer payments across all ISPs</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Platform-Wide Revenue (All Time)</p>
            <div class="text-2xl font-black text-slate-900">KES {{ number_format($totalTransactionRevenue / 100, 2) }}</div>
            <p class="text-xs text-slate-400 mt-1">Total successful transactions across all ISPs</p>
        </div>
    </div>

    {{-- ======= TENANT TABLE ======= --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                All ISP Tenants
            </h3>
            <span class="text-xs font-bold bg-slate-200 text-slate-600 px-2.5 py-1 rounded-full">{{ $totalTenants }} Total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">ISP Name / Email</th>
                        <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Usage</th>
                        <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Expires On</th>
                        <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tenants as $t)
                    @php $isActive = $t->subscription_ends_at && $t->subscription_ends_at > now(); @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-500 flex items-center justify-center font-black text-white text-sm flex-shrink-0">
                                    {{ strtoupper(substr($t->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">{{ $t->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $t->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="text-xs text-slate-600 space-y-0.5">
                                <div><strong>{{ $t->networks_count }}</strong> Networks</div>
                                <div><strong>{{ $t->users_count }}</strong> Users</div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($isActive)
                                @php $daysLeft = (int) now()->diffInDays($t->subscription_ends_at, false); @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                    Active · {{ $daysLeft }}d left
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                    Expired/Suspended
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            {{ $t->subscription_ends_at ? $t->subscription_ends_at->format('M d, Y') : 'Never' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                {{-- Extend 1 month --}}
                                <form action="{{ route('super.tenants.extend', $t) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="months" value="1">
                                    <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition-colors border border-blue-200">
                                        +1 Mo
                                    </button>
                                </form>
                                {{-- Extend 3 months --}}
                                <form action="{{ route('super.tenants.extend', $t) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="months" value="3">
                                    <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded-lg transition-colors border border-indigo-200">
                                        +3 Mo
                                    </button>
                                </form>
                                @if($isActive)
                                {{-- Suspend --}}
                                <form action="{{ route('super.tenants.suspend', $t) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Suspend {{ $t->name }}?')">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-lg transition-colors border border-red-200">
                                        Suspend
                                    </button>
                                </form>
                                @else
                                {{-- Activate --}}
                                <form action="{{ route('super.tenants.activate', $t) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Activate {{ $t->name }} for 1 month?')">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1.5 rounded-lg transition-colors border border-emerald-200">
                                        Activate
                                    </button>
                                </form>
                                @endif
                                {{-- View as ISP --}}
                                <form action="{{ route('super.tenants.impersonate', $t) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Switch to {{ $t->name }} dashboard?')">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 px-2.5 py-1.5 rounded-lg transition-colors border border-slate-200">
                                        View As
                                    </button>
                                </form>
                                {{-- Delete --}}
                                <button onclick="openDeleteModal({{ $t->id }}, '{{ addslashes($t->name) }}')"
                                        class="text-xs font-bold text-red-400 hover:text-red-700 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-lg transition-colors border border-red-100">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <p class="font-medium">No tenants registered yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm">
        <div class="px-6 py-5 border-b border-red-100 bg-red-50">
            <h3 class="text-lg font-black text-red-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.834-1.923-.834-2.693 0L3.34 16.5c-.77.834.192 2.5 1.732 2.5z"/></svg>
                ⚠️ Permanently Delete Tenant
            </h3>
        </div>
        <form id="delete-form" action="" method="POST" class="p-6 space-y-4">
            @csrf @method('DELETE')
            <p class="text-sm text-slate-600">This will delete <strong id="delete-name-display"></strong> and ALL associated data — networks, customers, transactions, sessions, vouchers. <strong class="text-red-600">This cannot be undone.</strong></p>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Type the ISP name to confirm</label>
                <input type="text" name="confirm_name" required placeholder="Type ISP name exactly..."
                       class="w-full px-3 py-2.5 border-2 border-red-200 rounded-xl text-sm focus:ring-2 focus:ring-red-400 focus:border-red-400">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl text-sm transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl text-sm shadow-sm transition-colors">
                    Delete Permanently
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openDeleteModal(id, name) {
    document.getElementById('delete-form').action = '/super/tenants/' + id;
    document.getElementById('delete-name-display').textContent = name;
    document.getElementById('delete-modal').classList.remove('hidden');
}
</script>
@endsection
