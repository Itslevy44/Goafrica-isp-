<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>{{ isset($currentTenant) && $currentTenant ? $currentTenant->name : 'GoAfrica Connect' }} — ISP Dashboard</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'xs':  '375px',
                        '3xl': '1920px',
                        '4xl': '2560px',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        body.sidebar-open { overflow: hidden; }

        /* Fluid base font: comfortable from 360px phone to 4K TV */
        html { font-size: clamp(13px, 1.1vw, 17px); }

        /* On very large screens expand sidebar slightly */
        @media (min-width: 1920px) {
            #sidebar { width: 18rem; }
            .sidebar-offset { margin-left: 18rem; }
        }
        @media (min-width: 2560px) {
            #sidebar { width: 20rem; }
            .sidebar-offset { margin-left: 20rem; }
        }
    </style>
</head>
<body class="h-full text-slate-800 antialiased bg-slate-50">

@auth
@php
    /* Safe tenant resolution — works regardless of which controllers pass $tenant */
    $currentTenant = isset($currentTenant) && $currentTenant
        ? $currentTenant
        : (isset($tenant) && $tenant
            ? $tenant
            : (Auth::check() ? Auth::user()?->tenant : null));
    $tenant = $currentTenant;
@endphp

{{-- =====================================================================
     MOBILE TOP BAR — fixed at top on small screens, hidden on desktop
     ===================================================================== --}}
<div class="md:hidden fixed top-0 left-0 right-0 z-50 bg-slate-900 text-white flex items-center justify-between px-4 py-3 shadow-md">
    <div class="flex items-center gap-2.5">
        <div class="w-8 h-8 bg-blue-600 rounded-xl flex items-center justify-center font-black text-white shadow">G</div>
        <span class="font-bold text-sm tracking-tight truncate max-w-[180px]">{{ $currentTenant?->name ?? 'GoAfrica Connect' }}</span>
    </div>
    <button id="mobile-menu-btn" class="p-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white focus:outline-none transition-colors">
        <svg id="icon-hamburger" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

{{-- =====================================================================
     SEMI-TRANSPARENT OVERLAY — closes mobile sidebar when tapped
     ===================================================================== --}}
<div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/50 hidden md:hidden" onclick="closeSidebar()"></div>

{{-- =====================================================================
     SIDEBAR — fixed position, always stays in place while page scrolls
     CSS: position:fixed; top:0; left:0; height:100vh
     On mobile it slides in from left; on desktop it is always visible.
     ===================================================================== --}}
<aside id="sidebar" class="fixed top-0 left-0 z-40 h-screen w-64 bg-slate-900 text-slate-300 border-r border-slate-800 flex flex-col
                            -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">

    {{-- Brand --}}
    <div class="flex-shrink-0 p-5 border-b border-slate-800/80 flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
            </svg>
        </div>
        <div class="overflow-hidden">
            <h2 class="font-bold text-white text-sm tracking-tight truncate">{{ $currentTenant?->name ?? 'GoAfrica Connect' }}</h2>
            <span class="text-[11px] text-blue-400 font-medium flex items-center gap-1.5 mt-0.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                ISP Cloud Node
            </span>
        </div>
    </div>

    {{-- Navigation — scrollable area --}}
    <nav class="flex-1 overflow-y-auto p-4 space-y-1">
        @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('super.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('super.index') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Global SaaS Overview
            </a>
        @else
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3 pt-1 pb-1">Operations</p>

            <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.index') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Overview
            </a>

            <a href="{{ route('dashboard.customers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.customers.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Customers (CRM)
            </a>

            <a href="{{ route('dashboard.devices.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.devices.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                Routers & Devices
            </a>

            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3 pt-4 pb-1">Billing & Sales</p>

            <a href="{{ route('dashboard.offers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.offers.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Internet Plans
            </a>

            <a href="{{ route('dashboard.vouchers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.vouchers.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                Vouchers & QR
            </a>

            <a href="{{ route('dashboard.sessions.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.sessions.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                Active Sessions
            </a>

            <a href="{{ route('dashboard.reports.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.reports.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Reports & Revenue
            </a>

            <a href="{{ route('dashboard.wallet.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.wallet.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Wallet & Payouts
            </a>

            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3 pt-4 pb-1">Tools & Config</p>

            <a href="{{ route('dashboard.networks.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.networks.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                Networks
            </a>

            <a href="{{ route('dashboard.cmd') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.cmd') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Web Terminal
            </a>

            <a href="{{ route('dashboard.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.settings.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings & M-Pesa
            </a>

            <a href="{{ route('dashboard.docs') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard.docs') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Documentation
            </a>
        @endif
    </nav>

    {{-- User footer — pinned at bottom of sidebar --}}
    <div class="flex-shrink-0 p-4 border-t border-slate-800/80 bg-slate-950/40">
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-3 overflow-hidden min-w-0">
                <div class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center font-bold text-xs text-slate-300 flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                </div>
                <div class="truncate min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit" title="Log out" class="text-slate-400 hover:text-red-400 p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- =====================================================================
     MAIN CONTENT WRAPPER
     On desktop (md+): ml-64 pushes content right of the fixed sidebar.
     On mobile: pt-14 offsets content below the fixed top bar.
     ===================================================================== --}}
<div class="md:ml-64 sidebar-offset flex flex-col min-h-screen pt-14 md:pt-0">

    {{-- Sticky top header bar --}}
    <header class="sticky top-0 z-20 bg-white border-b border-slate-200/80 px-3 xs:px-4 md:px-6 3xl:px-10 py-2.5 md:py-3 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2 xs:gap-3 min-w-0 overflow-hidden">
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-slate-100 rounded-lg text-xs 3xl:text-sm font-semibold text-slate-600 whitespace-nowrap">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping flex-shrink-0"></span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 -ml-3 flex-shrink-0"></span>
                Auto-Billing Active
            </div>

            @if($currentTenant && $currentTenant->subscription_ends_at)
                @php $daysLeft = (int) now()->diffInDays($currentTenant->subscription_ends_at, false); @endphp
                @if($daysLeft >= 0)
                    <a href="{{ route('dashboard.subscribe.index') }}" class="inline-flex items-center gap-1.5 px-2 xs:px-2.5 py-1 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors whitespace-nowrap">
                        ⚡ {{ $daysLeft }}d Trial
                    </a>
                @else
                    <a href="{{ route('dashboard.subscribe.index') }}" class="inline-flex items-center gap-1.5 px-2 xs:px-2.5 py-1 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs font-bold animate-pulse hover:bg-red-100 whitespace-nowrap">
                        ⚠️ <span class="hidden xs:inline">Expired —</span> Renew KES 500
                    </a>
                @endif
            @endif
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('portal.index', ['network_slug' => $currentTenant?->networks?->first()?->slug ?? 'default']) }}"
               target="_blank"
               class="hidden sm:inline-flex items-center gap-1.5 text-xs 3xl:text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 rounded-xl transition-colors whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Test Portal
            </a>
        </div>
    </header>

    {{-- Page content --}}
    <main class="flex-1 p-3 xs:p-4 md:p-6 lg:p-8 3xl:p-12 4xl:p-16
                 max-w-7xl 3xl:max-w-screen-2xl 4xl:max-w-[2400px]
                 w-full mx-auto">

        {{-- Email verification reminder banner --}}
        @if(Auth::check() && !Auth::user()->isSuperAdmin() && !Auth::user()->hasVerifiedEmail())
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-amber-900">Verify your email ({{ Auth::user()->email }})</p>
                        <p class="text-xs text-amber-700">Click the link sent to your inbox to activate full access.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('verification.send') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="text-xs font-bold bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-xl transition-colors shadow-sm">
                        Resend Email
                    </button>
                </form>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('message'))
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-2xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-semibold">{{ session('message') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl mb-6">
                <ul class="list-disc list-inside space-y-1 text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

@else
    {{-- Guest pages (login, etc) --}}
    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        @yield('content')
    </div>
@endauth

<script>
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebar-overlay');
    const iconOpen = document.getElementById('icon-hamburger');
    const iconClose = document.getElementById('icon-close');

    document.getElementById('mobile-menu-btn')?.addEventListener('click', function () {
        if (sidebar.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    });

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        iconOpen.classList.add('hidden');
        iconClose.classList.remove('hidden');
        document.body.classList.add('sidebar-open');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        iconOpen.classList.remove('hidden');
        iconClose.classList.add('hidden');
        document.body.classList.remove('sidebar-open');
    }
</script>
</body>
</html>
