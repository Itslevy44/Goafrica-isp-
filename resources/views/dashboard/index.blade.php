@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header Greeting -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Dashboard Overview</h1>
            <p class="text-sm text-slate-500 mt-1">Real-time MikroTik hotspot metrics, M-Pesa collections & active sessions.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.vouchers.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 transition-all hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Generate Vouchers
            </a>
            <a href="{{ route('dashboard.devices.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold rounded-xl shadow-sm transition-colors">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Router
            </a>
        </div>
    </div>

    <!-- 4 Key Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Live Wallet / Revenue -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Collections</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 tracking-tight">KES {{ number_format($balance / 100, 2) }}</div>
            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                <span class="text-emerald-600 font-bold">100% Direct</span> to Till / Paybill
            </p>
        </div>

        <!-- Active Internet Sessions -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Connected Users</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $activeSessions->count() }}</div>
            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Active MikroTik Leases
            </p>
        </div>

        <!-- Payout / Till Target -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Target Till / Paybill</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
            </div>
            <div class="text-lg font-black text-slate-900 truncate">
                {{ $tenant->mpesa_shortcode ?? '174379' }}
            </div>
            <p class="text-xs text-slate-500 mt-1 truncate">
                {{ $tenant->mpesa_shortcode ? 'Active Safaricom STK' : 'Default Sandbox Till' }}
            </p>
        </div>

        <!-- System Health -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Network Status</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-600 tracking-tight">100%</div>
            <p class="text-xs text-slate-500 mt-1">
                Router health checks active
            </p>
        </div>
    </div>

    <!-- Revenue Graph Section -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-900">Revenue Analytics (Last 7 Days)</h3>
                <p class="text-xs text-slate-500">Aggregated daily revenue from all hotspot plans & vouchers.</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 bg-slate-100 text-slate-600 rounded-full w-fit">Live Telemetry</span>
        </div>
        <div class="relative h-64 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Two-Column Grid: Active Sessions & Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Active Internet Sessions Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h3 class="font-bold text-slate-900 text-sm">Active WiFi Sessions</h3>
                </div>
                <span class="text-xs font-semibold bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full">
                    {{ $activeSessions->count() }} Online
                </span>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3">MAC / Device</th>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Remaining</th>
                            <th class="px-6 py-3 text-right">Type</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($activeSessions as $session)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-3.5 font-mono text-slate-700 font-medium">
                                {{ $session->mac_address }}
                            </td>
                            <td class="px-6 py-3.5 text-slate-600">
                                {{ $session->customer->phone ?? 'Guest User' }}
                            </td>
                            <td class="px-6 py-3.5 text-slate-600">
                                <span class="inline-flex items-center gap-1 text-slate-700 font-semibold">
                                    ⏱️ {{ $session->ends_at->diffForHumans(['parts' => 1]) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase {{ $session->source_type == 'voucher' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $session->source_type }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                                No active users connected right now.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-slate-900 text-sm">Recent M-Pesa Transactions</h3>
                </div>
                <a href="{{ route('dashboard.reports.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                    View All →
                </a>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/70 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Plan</th>
                            <th class="px-6 py-3">Amount</th>
                            <th class="px-6 py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentTransactions as $txn)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-3.5">
                                <div class="font-bold text-slate-800">{{ $txn->customer->phone ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $txn->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-3.5 text-slate-600 font-medium">
                                {{ $txn->offer->name ?? 'Direct Purchase' }}
                            </td>
                            <td class="px-6 py-3.5 font-bold text-slate-900">
                                {{ $txn->currency }} {{ number_format($txn->amount_minor / 100, 2) }}
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                @if($txn->status === 'success')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-700">
                                        Success
                                    </span>
                                @elseif($txn->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-700">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-700">
                                        Failed
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                No transactions recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('revenueChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!},
                datasets: [{
                    label: 'Revenue (KES)',
                    data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                    borderColor: '#2563eb',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'KES ' + Number(context.raw).toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#64748b' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { 
                            font: { size: 11 }, 
                            color: '#64748b',
                            callback: function(value) { return 'KES ' + value; }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
