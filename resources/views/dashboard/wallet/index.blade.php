@extends('layouts.app')

@section('content')
<div class="dashboard-grid">
    <div class="glass-panel" style="padding: 2rem; background: linear-gradient(135deg, var(--primary-bg), #0f172a); color: white;">
        <h3 style="margin-bottom: 0.5rem; color: #94a3b8;">Available Balance</h3>
        <h1 style="font-size: 3rem; margin-bottom: 1.5rem; font-weight: 700;">{{ $tenant->default_currency ?? 'KES' }} {{ number_format($balance / 100, 2) }}</h1>
        <p style="color: #cbd5e1; font-size: 0.9rem;">This balance reflects your 90% earnings from all customer purchases on your network.</p>
    </div>

    <div class="glass-panel" style="padding: 2rem;">
        <h3 style="margin-bottom: 1.5rem;">Request Withdrawal</h3>
        <form action="{{ route('dashboard.wallet.withdraw') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Withdraw To</label>
                <select name="payout_account_id" class="form-control" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-light); border-radius: var(--radius-md);">
                    @forelse($payoutAccounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->account_name }} ({{ $acc->account_identifier }})</option>
                    @empty
                        <option value="">No payout accounts found. Please add one first.</option>
                    @endforelse
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Amount ({{ $tenant->default_currency ?? 'KES' }})</label>
                <input type="number" name="amount" class="form-control" required min="10" max="{{ $balance / 100 }}" step="0.01" value="{{ $balance / 100 }}">
            </div>
            
            <button type="submit" class="btn btn-success" {{ $payoutAccounts->isEmpty() || $balance <= 0 ? 'disabled' : '' }}>
                Withdraw Funds
            </button>
        </form>
    </div>
</div>

<div class="dashboard-grid" style="grid-template-columns: 1fr; margin-top: 2rem;">
    <div class="glass-panel" style="padding: 2rem;">
        <h3 style="margin-bottom: 1.5rem;">Recent Ledger Activity</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 1rem; border-bottom: 2px solid var(--border-light);">Date</th>
                    <th style="text-align: left; padding: 1rem; border-bottom: 2px solid var(--border-light);">Type</th>
                    <th style="text-align: right; padding: 1rem; border-bottom: 2px solid var(--border-light);">Amount</th>
                    <th style="text-align: right; padding: 1rem; border-bottom: 2px solid var(--border-light);">Balance After</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ledger as $entry)
                <tr>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-light);">{{ $entry->created_at->format('Y-m-d H:i') }}</td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-light);">
                        @if($entry->entry_type === 'sale_credit')
                            <span style="color: var(--success); font-weight: bold;">+ Sale (90%)</span>
                        @elseif($entry->entry_type === 'payout_debit')
                            <span style="color: var(--danger); font-weight: bold;">- Withdrawal</span>
                        @else
                            {{ $entry->entry_type }}
                        @endif
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-light); text-align: right;">{{ $entry->currency }} {{ number_format($entry->amount_minor / 100, 2) }}</td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-light); text-align: right; font-family: monospace;">{{ $entry->currency }} {{ number_format($entry->balance_after_minor / 100, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem;">No transactions yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
