<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Renew Subscription — GoAfrica Connect</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { font-size: clamp(13px, 1.5vw, 16px); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 flex items-center justify-center p-4">

    {{-- Background decoration --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-500/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2.5">
                <img src="/favicon.png" alt="goAfrica Connect" class="w-10 h-10 rounded-xl shadow-lg">
                <span class="font-black text-xl text-white tracking-tight">goAfrica <span class="text-blue-400">Connect</span></span>
            </a>
        </div>

        {{-- Main Card --}}
        <div class="bg-white/[0.06] backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-2xl">

            {{-- Top status banner --}}
            @if(is_null($tenant->subscription_ends_at))
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-center">
                <p class="text-white text-sm font-semibold">🎉 Welcome! Activate your subscription to get started.</p>
            </div>
            @else
            <div class="bg-gradient-to-r from-red-600/80 to-rose-600/80 px-6 py-3 text-center">
                <p class="text-white text-sm font-semibold">⏰ Subscription expired on {{ $tenant->subscription_ends_at->format('M d, Y') }}</p>
            </div>
            @endif

            <div class="p-6 sm:p-8">

                {{-- Lock icon --}}
                <div class="flex justify-center mb-5">
                    <div class="w-16 h-16 rounded-2xl bg-red-500/15 border border-red-500/20 flex items-center justify-center">
                        <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 11V7a5 5 0 0110 0v4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <h1 class="text-xl font-black text-white text-center mb-2 tracking-tight">Dashboard Locked</h1>
                <p class="text-slate-400 text-sm text-center leading-relaxed mb-6">
                    @if(is_null($tenant->subscription_ends_at))
                        Activate your monthly subscription to start accepting payments and managing your hotspot network.
                    @else
                        Your subscription ended on <span class="text-white font-bold">{{ $tenant->subscription_ends_at->format('M d, Y') }}</span>.
                        Renew now to restore access to your dashboard and network settings.
                    @endif
                </p>

                {{-- Price display --}}
                <div class="bg-white/5 border border-white/10 rounded-2xl p-5 mb-6 text-center">
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-widest mb-2">Monthly Subscription</p>
                    <div class="flex items-end justify-center gap-1">
                        <span class="text-5xl font-black text-white leading-none">500</span>
                        <div class="text-left mb-1.5">
                            <div class="text-slate-300 font-bold text-sm">KES</div>
                            <div class="text-slate-500 text-xs">/ month</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-4 mt-3 pt-3 border-t border-white/10">
                        <span class="flex items-center gap-1.5 text-xs text-slate-400">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Unlimited routers
                        </span>
                        <span class="flex items-center gap-1.5 text-xs text-slate-400">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            0% commission
                        </span>
                        <span class="flex items-center gap-1.5 text-xs text-slate-400">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Full access
                        </span>
                    </div>
                </div>

                {{-- Success state --}}
                @if(session('success'))
                <div class="bg-emerald-500/15 border border-emerald-500/30 rounded-2xl p-4 mb-5">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-emerald-300 text-sm font-semibold">STK Push Sent!</p>
                            <p class="text-emerald-400/80 text-xs mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button onclick="window.location.reload()"
                            class="mt-3 w-full bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 text-emerald-300 font-bold text-sm py-2.5 rounded-xl transition-colors">
                        ✓ I've entered my PIN — Check Access
                    </button>
                </div>
                @endif

                {{-- Error state --}}
                @if($errors->any())
                <div class="bg-red-500/15 border border-red-500/30 rounded-2xl p-4 mb-5 flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-red-300 text-sm font-medium">{{ $errors->first() }}</p>
                </div>
                @endif

                {{-- Payment form --}}
                <form action="{{ route('dashboard.subscribe.pay') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">M-Pesa Phone Number</label>
                        <div class="flex items-center bg-white/5 border border-white/10 rounded-2xl overflow-hidden focus-within:border-blue-500/50 focus-within:ring-1 focus-within:ring-blue-500/30 transition-all">
                            <div class="px-4 py-3.5 border-r border-white/10 bg-white/5 flex items-center gap-1.5 select-none flex-shrink-0">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/1/15/M-PESA_LOGO-01.svg"
                                     class="h-4 object-contain" alt="M-Pesa"
                                     onerror="this.style.display='none'">
                                <span class="text-slate-300 font-bold text-sm">+254</span>
                            </div>
                            <input type="tel" name="phone"
                                   class="flex-1 bg-transparent px-4 py-3.5 text-white placeholder-slate-500 text-sm font-medium outline-none min-w-0"
                                   placeholder="712 345 678"
                                   inputmode="numeric"
                                   pattern="[0-9]{9,10}"
                                   value="{{ old('phone') }}"
                                   required>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            You'll receive a KES 500 M-Pesa PIN prompt
                        </p>
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-[0.98] text-white font-bold py-4 rounded-2xl text-sm transition-all shadow-lg shadow-blue-500/25 flex items-center justify-center gap-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/1/15/M-PESA_LOGO-01.svg"
                             class="h-4 object-contain brightness-0 invert" alt="" onerror="this.remove()">
                        Pay KES 500 via M-Pesa
                    </button>
                </form>

                {{-- Footer actions --}}
                <div class="mt-6 pt-5 border-t border-white/10 flex items-center justify-between">
                    <a href="/" class="text-xs text-slate-500 hover:text-slate-300 transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back to home
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-slate-500 hover:text-red-400 transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Sign Out
                        </button>
                    </form>
                </div>

            </div>
        </div>

        {{-- Help text --}}
        <p class="text-center text-xs text-slate-600 mt-5">
            Need help?
            <a href="tel:+254748717099" class="text-slate-400 hover:text-white transition-colors">+254 748 717 099</a>
            ·
            <a href="mailto:support@goafrica.site" class="text-slate-400 hover:text-white transition-colors">support@goafrica.site</a>
        </p>

    </div>

</body>
</html>
