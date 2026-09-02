@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Voucher Management</h2>
        <p class="text-sm text-slate-500 mt-1">Generate and manage prepaid internet scratch cards.</p>
    </div>
    <div class="flex space-x-3">
        <button onclick="document.getElementById('generate-vouchers-modal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Generate Batch
        </button>
        <a href="{{ route('dashboard.vouchers.print') }}" target="_blank" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors flex items-center">
            <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Vouchers
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">{{ $errors->first() }}</div>
@endif

<!-- Search & Filter Bar -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-5">
    <form method="GET" action="{{ route('dashboard.vouchers.index') }}" class="flex flex-col sm:flex-row gap-3 items-end">
        <div class="flex-1">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Search Code</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. AB12CD34"
                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status</label>
            <select name="filter_status" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">All</option>
                <option value="unused" {{ request('filter_status') === 'unused' ? 'selected' : '' }}>Unused</option>
                <option value="partial" {{ request('filter_status') === 'partial' ? 'selected' : '' }}>Partially Used</option>
                <option value="used" {{ request('filter_status') === 'used' ? 'selected' : '' }}>Fully Used</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-2 px-4 rounded-lg shadow-sm transition-colors">Filter</button>
            @if(request('search') || request('filter_status'))
            <a href="{{ route('dashboard.vouchers.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-bold rounded-lg transition-colors">✕</a>
            @endif
        </div>
    </form>
</div>

<!-- Vouchers Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Voucher Code</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Type</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Value</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Usage</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Created At</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($vouchers as $voucher)
                <tr class="hover:bg-slate-50/50 transition-colors {{ $voucher->uses_count >= $voucher->max_uses ? 'opacity-50' : '' }}">
                    <td class="py-3 px-4">
                        <div class="inline-flex items-center px-3 py-1 rounded bg-slate-100 border border-slate-200">
                            <span class="font-mono font-bold text-slate-800 tracking-[0.1em]">{{ $voucher->code }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium {{ $voucher->type === 'time' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }} capitalize">
                            {{ $voucher->type }}
                        </span>
                    </td>
                    <td class="py-3 px-4 font-medium text-slate-800">
                        @if($voucher->type === 'time')
                            {{ $voucher->value }} Mins
                        @else
                            {{ $network->currency }} {{ number_format($voucher->value / 100, 2) }}
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center">
                            <div class="w-16 h-2 bg-slate-200 rounded-full mr-2 overflow-hidden">
                                <div class="h-full {{ $voucher->uses_count >= $voucher->max_uses ? 'bg-red-500' : 'bg-blue-500' }}" style="width: {{ ($voucher->uses_count / $voucher->max_uses) * 100 }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 font-medium">{{ $voucher->uses_count }}/{{ $voucher->max_uses }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-sm text-slate-500">
                        {{ $voucher->created_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        @if($voucher->uses_count === 0)
                        <form action="{{ route('dashboard.vouchers.destroy', $voucher->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete voucher {{ $voucher->code }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold px-2 py-1 bg-red-50 hover:bg-red-100 rounded-md transition-colors">
                                Delete
                            </button>
                        </form>
                        @else
                            <span class="text-slate-300 text-xs">In use</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 px-4 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        </div>
                        <h3 class="text-slate-800 font-medium">No vouchers found</h3>
                        <p class="text-slate-500 text-sm mt-1">Generate a batch of scratch cards to start selling offline.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($vouchers->hasPages())
    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
        {{ $vouchers->links() }}
    </div>
    @endif
</div>

<!-- Generate Vouchers Modal -->
<div id="generate-vouchers-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Generate Vouchers</h3>
            <button onclick="document.getElementById('generate-vouchers-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <form action="{{ route('dashboard.vouchers.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Number of Vouchers</label>
                    <input type="number" name="count" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" value="10" min="1" max="100" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Voucher Type</label>
                    <select name="type" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="time">Time (Minutes)</option>
                        <option value="money">Money (Balance)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Value (Mins or Amount)</label>
                    <input type="number" name="value" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="e.g. 60 for 1 hour" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Max Uses per Voucher</label>
                    <input type="number" name="max_uses" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" value="1" min="1" required>
                    <p class="text-xs text-slate-500 mt-1">Set to 1 for standard single-use scratch cards.</p>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors shadow-sm">
                        Generate Batch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
