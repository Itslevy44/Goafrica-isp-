<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Required - ISP Dashboard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: var(--bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }
        .subscribe-card {
            background: var(--surface-color);
            border-radius: var(--radius-lg);
            padding: 3rem 2rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid var(--border-light);
        }
        .icon-lock {
            width: 64px;
            height: 64px;
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
        }
        h1 { margin-bottom: 1rem; font-size: 1.5rem; }
        p { color: var(--text-secondary); margin-bottom: 2rem; line-height: 1.6; }
        .price-tag {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2rem;
        }
        .price-tag span { font-size: 1rem; font-weight: 500; color: var(--text-secondary); }
    </style>
</head>
<body>

    <div class="subscribe-card">
        <div class="icon-lock">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
        </div>
        
        <h1>Dashboard Locked</h1>
        
        @if(is_null($tenant->subscription_ends_at))
            <p>Welcome! To start using the ISP Billing System and accepting payments directly to your own M-Pesa account, you must activate your monthly subscription.</p>
        @else
            <p>Your subscription ended on <strong>{{ $tenant->subscription_ends_at->format('M d, Y') }}</strong>. Please renew your subscription to regain access to your dashboard and network settings.</p>
        @endif

        <div class="price-tag">
            Ksh 500 <span>/ month</span>
        </div>

        @if(session('success'))
            <div style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                {{ session('success') }}
                <br><br>
                <button onclick="window.location.reload()" class="btn btn-primary" style="padding: 0.5rem 1rem;">I have entered my PIN</button>
            </div>
        @elseif(session('error') || $errors->any())
            <div style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                {{ session('error') ?? $errors->first() }}
            </div>
        @endif

        <form action="{{ route('dashboard.subscribe.pay') }}" method="POST">
            @csrf
            <div class="form-group" style="text-align: left;">
                <label class="form-label">M-Pesa Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="07XXXXXXXX" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem; margin-top: 1rem;">
                Pay via M-Pesa
            </button>
        </form>

        <div style="margin-top: 1.5rem;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background: none; border: none; color: var(--text-secondary); text-decoration: underline; cursor: pointer;">Sign Out</button>
            </form>
        </div>
    </div>

</body>
</html>
