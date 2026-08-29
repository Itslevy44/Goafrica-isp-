@extends('layouts.app')

@section('content')
<div class="dashboard-grid">
    <div class="glass-panel" style="padding: 2rem;">
        <h3 style="margin-bottom: 1.5rem;">Payout Accounts</h3>
        <p style="margin-bottom: 1.5rem; color: var(--text-secondary);">Manage where you receive your automated settlements (90% of all customer purchases). Automated payouts occur daily.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 1rem; border-bottom: 2px solid var(--border-light);">Method</th>
                    <th style="text-align: left; padding: 1rem; border-bottom: 2px solid var(--border-light);">Account Identifier</th>
                    <th style="text-align: left; padding: 1rem; border-bottom: 2px solid var(--border-light);">Account Name</th>
                    <th style="text-align: left; padding: 1rem; border-bottom: 2px solid var(--border-light);">Country</th>
                    <th style="text-align: right; padding: 1rem; border-bottom: 2px solid var(--border-light);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                <tr>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-light);">
                        @if($account->method === 'mpesa_till') M-Pesa Till
                        @elseif($account->method === 'mpesa_paybill') M-Pesa Paybill
                        @elseif($account->method === 'mobile_wallet') Mobile Wallet
                        @else Bank Account @endif
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-light);">{{ $account->account_identifier }}</td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-light);">{{ $account->account_name }}</td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-light);">{{ $account->country_code }}</td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-light); text-align: right;">
                        <form action="{{ route('dashboard.payouts.destroy', $account) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; width: auto; background: var(--danger);" onclick="return confirm('Delete this payout method?')">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2rem;">No payout accounts added. Please add one below to receive your funds.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="dashboard-grid">
    <div class="glass-panel" style="padding: 2rem; max-width: 600px;">
        <h3 style="margin-bottom: 1.5rem;">Add New Payout Account</h3>
        <form action="{{ route('dashboard.payouts.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Country</label>
                <select name="country_code" class="form-control" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-light); border-radius: var(--radius-md);">
                    <option value="KEN">Kenya</option>
                    <option value="TZA">Tanzania</option>
                    <option value="UGA">Uganda</option>
                    <option value="RWA">Rwanda</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Payment Method</label>
                <select name="method" class="form-control" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-light); border-radius: var(--radius-md);">
                    <option value="mpesa_till">M-Pesa Buy Goods (Till)</option>
                    <option value="mpesa_paybill">M-Pesa Paybill</option>
                    <option value="mobile_wallet">Other Mobile Wallet</option>
                    <option value="bank_account">Bank Account</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Account Number / Till Number</label>
                <input type="text" name="account_identifier" class="form-control" required placeholder="e.g. 123456">
            </div>

            <div class="form-group">
                <label class="form-label">Registered Account Name</label>
                <input type="text" name="account_name" class="form-control" required placeholder="e.g. Downtown CyberCafe">
            </div>
            
            <button type="submit" class="btn btn-success">Save Payout Account</button>
        </form>
    </div>
</div>
@endsection
