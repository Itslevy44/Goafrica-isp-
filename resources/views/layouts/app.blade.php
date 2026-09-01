<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $tenant->name ?? 'goAfrica Connect' }}</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="stylesheet" href="/css/app.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @auth
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="/images/logo.png" alt="Logo" style="height: 40px; border-radius: 8px;">
                <h3 style="margin-bottom: 0; font-size: 1.15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $tenant->name ?? 'goAfrica Connect' }}</h3>
            </div>
            
            <nav class="sidebar-nav">
                @if(Auth::user()->isSuperAdmin())
                    <a href="{{ route('super.index') }}" class="{{ request()->routeIs('super.index') ? 'active' : '' }}">Global Overview</a>
                @else
                    <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">Network Overview</a>
                    <a href="{{ route('dashboard.reports.index') }}" class="{{ request()->routeIs('dashboard.reports.*') ? 'active' : '' }}">Reports & Analytics</a>
                    <a href="{{ route('dashboard.customers.index') }}" class="{{ request()->routeIs('dashboard.customers.*') ? 'active' : '' }}">Customers (CRM)</a>
                    <a href="{{ route('dashboard.devices.index') }}" class="{{ request()->routeIs('dashboard.devices.*') ? 'active' : '' }}">Routers & Devices</a>
                    <a href="{{ route('dashboard.offers.index') }}" class="{{ request()->routeIs('dashboard.offers.*') ? 'active' : '' }}">Internet Plans</a>
                    <a href="{{ route('dashboard.vouchers.index') }}" class="{{ request()->routeIs('dashboard.vouchers.*') ? 'active' : '' }}">Vouchers</a>
                    <a href="{{ route('dashboard.cmd') }}" class="{{ request()->routeIs('dashboard.cmd') ? 'active' : '' }}">Web Terminal</a>
                    <a href="{{ route('dashboard.settings.index') }}" class="{{ request()->routeIs('dashboard.settings.*') ? 'active' : '' }}">Settings</a>
                    <a href="{{ route('dashboard.docs') }}" class="{{ request()->routeIs('dashboard.docs') ? 'active' : '' }}">Documentation</a>
                @endif
                
                <div style="margin-top: auto;">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: var(--danger);">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </nav>
        </aside>

            @if(Auth::check() && !Auth::user()->isSuperAdmin() && !Auth::user()->hasVerifiedEmail())
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-amber-900">Please verify your email address ({{ Auth::user()->email }})</p>
                            <p class="text-xs text-amber-700">Check your inbox for the activation link to secure your account.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('verification.send') }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="text-xs font-semibold bg-amber-600 hover:bg-amber-700 text-white px-3.5 py-2 rounded-lg transition-colors shadow-sm">
                            Resend Verification Link
                        </button>
                    </form>
                </div>
            @endif

            @if(session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
    @else
    <!-- Guest Layout (For Login/Register pages that extend this layout) -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>
    @endauth
</body>
</html>
<script>
    (function(){
        const btn = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        if(btn && sidebar){
            btn.addEventListener('click', function(e){
                e.preventDefault();
                sidebar.classList.toggle('-translate-x-full');
            });

            // Close when clicking outside on small screens
            document.addEventListener('click', function(e){
                if(window.innerWidth < 768){
                    if(!sidebar.contains(e.target) && !btn.contains(e.target)){
                        sidebar.classList.add('-translate-x-full');
                    }
                }
            });
        }
    })();
</script>
