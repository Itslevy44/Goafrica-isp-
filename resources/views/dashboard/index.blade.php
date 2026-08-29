@extends('layouts.app')

@section('content')
<div class="dashboard-grid">
    <div class="glass-panel stat-card">
        <h3>Wallet Balance</h3>
        <div class="stat-value">KES {{ number_format($balance / 100, 2) }}</div>
        <p>Available for Payout</p>
    </div>
    
    <div class="glass-panel stat-card">
        <h3>Active Sessions</h3>
        <div class="stat-value">{{ $activeSessions->count() }}</div>
        <p>Currently Connected Users</p>
    </div>

    <div class="glass-panel stat-card">
        <h3>Payment Wallet</h3>
        <div class="stat-value">{{ $payoutAccount ? ucfirst(str_replace('_', ' ', $payoutAccount->method)) : 'Not set' }}</div>
        <p>{{ $payoutAccount ? $payoutAccount->account_identifier : 'Set your till or wallet in settings' }}</p>
    </div>
</div>

<div class="dashboard-grid" style="margin-bottom: 2rem;">
    <div class="glass-panel" style="padding: 1.5rem; grid-column: 1 / -1;">
        <h3 style="margin-bottom: 1rem;">Revenue Overview (Last 7 Days)</h3>
        <canvas id="revenueChart" height="60"></canvas>
    </div>
</div>

<div class="dashboard-grid" style="margin-bottom: 2rem;">
    <div class="glass-panel" style="padding: 2rem;">
        <h3 style="margin-bottom: 1.5rem;">Quick Navigation</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
            <a href="{{ route('dashboard.cmd') }}" class="btn btn-primary" style="background: rgba(29, 78, 216, 0.1); color: var(--primary-bg); text-align: left; border: 1px solid rgba(29, 78, 216, 0.2);">
                <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">💻</div>
                <strong>Web Terminal</strong>
                <div style="font-size: 0.85rem; margin-top: 0.25rem;">Execute Commands</div>
            </a>
            <a href="{{ route('dashboard.docs') }}" class="btn btn-primary" style="background: rgba(16, 185, 129, 0.1); color: var(--success); text-align: left; border: 1px solid rgba(16, 185, 129, 0.2);">
                <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📚</div>
                <strong>API Docs</strong>
                <div style="font-size: 0.85rem; margin-top: 0.25rem;">Integration Guide</div>
            </a>
            <a href="{{ route('dashboard.vouchers.index') }}" class="btn btn-primary" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; text-align: left; border: 1px solid rgba(139, 92, 246, 0.2);">
                <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🎟️</div>
                <strong>Vouchers</strong>
                <div style="font-size: 0.85rem; margin-top: 0.25rem;">Manage Scratch Cards</div>
            </a>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="glass-panel" style="padding: 1.5rem;">
        <h3>Recent Transactions</h3>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Offer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $txn)
                    <tr>
                        <td>#{{ $txn->id }}</td>
                        <td>{{ $txn->customer->phone ?? 'N/A' }}</td>
                        <td>{{ $txn->offer->name ?? 'N/A' }}</td>
                        <td>{{ $txn->currency }} {{ number_format($txn->amount_minor / 100, 2) }}</td>
                        <td>
                            <span style="color: {{ $txn->status === 'success' ? 'var(--success)' : ($txn->status === 'failed' ? 'var(--danger)' : 'var(--text-secondary)') }}">
                                {{ ucfirst($txn->status) }}
                            </span>
                        </td>
                        <td>{{ $txn->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">No transactions yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="glass-panel" style="padding: 1.5rem;">
        <h3>Active Internet Sessions</h3>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>MAC Address</th>
                        <th>Customer</th>
                        <th>Expires</th>
                        <th>Source</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeSessions as $session)
                    <tr>
                        <td>{{ $session->mac_address }}</td>
                        <td>{{ $session->customer->phone ?? 'Unknown' }}</td>
                        <td>{{ $session->ends_at->diffForHumans() }}</td>
                        <td>{{ ucfirst($session->source_type) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">No active sessions.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? []) !!},
            datasets: [{
                label: 'Revenue (KES)',
                data: {!! json_encode($chartData ?? []) !!},
                borderColor: '#1d4ed8',
                backgroundColor: 'rgba(29, 78, 216, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
