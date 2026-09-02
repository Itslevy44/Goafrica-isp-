<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>goAfrica Connect — The Modern ISP Billing Engine for Africa</title>
    <meta name="description" content="Automate your MikroTik hotspot billing with direct M-Pesa integration. Keep 100% of your revenue. Start your free trial today.">
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    animation: {
                        'blob': 'blob 8s infinite ease-in-out',
                        'float': 'float 6s ease-in-out infinite',
                        'fade-up': 'fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%,100%': { transform: 'translate(0,0) scale(1)' },
                            '33%': { transform: 'translate(30px,-40px) scale(1.08)' },
                            '66%': { transform: 'translate(-20px,20px) scale(0.94)' },
                        },
                        float: {
                            '0%,100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-12px)' },
                        },
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(24px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root { --blue: #2563eb; --sky: #0ea5e9; }
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }

        .glass-nav {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226,232,240,0.7);
        }

        .hero-gradient {
            background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(37,99,235,0.12) 0%, transparent 60%),
                        radial-gradient(ellipse 60% 40% at 80% 20%, rgba(14,165,233,0.08) 0%, transparent 50%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #0f172a 0%, #1e40af 60%, #0ea5e9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-hover {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.12);
        }

        .reveal { opacity: 0; }
        .revealed { animation: fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) forwards; }

        .glow-btn {
            box-shadow: 0 0 0 0 rgba(37,99,235,0.4);
            transition: box-shadow 0.3s ease, transform 0.2s ease;
        }
        .glow-btn:hover {
            box-shadow: 0 8px 30px rgba(37,99,235,0.35);
            transform: translateY(-1px);
        }

        .step-line::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, #cbd5e1, transparent);
        }

        @media (max-width: 768px) { .step-line::after { display: none; } }
    </style>
</head>
<body class="antialiased text-slate-800">

<!-- ======================== NAV ======================== -->
<nav class="fixed w-full z-50 glass-nav" id="navbar">
    <div class="max-w-7xl mx-auto px-5 lg:px-8">
        <div class="flex justify-between items-center h-18 py-4">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-sky-500 flex items-center justify-center shadow-lg shadow-blue-500/25">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="font-display font-bold text-xl text-slate-900 tracking-tight">goAfrica<span class="text-blue-600">.</span></span>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Features</a>
                <a href="#how-it-works" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">How It Works</a>
                <a href="#pricing" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Pricing</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="hidden md:inline text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="glow-btn bg-blue-600 text-white px-5 py-2.5 rounded-full text-sm font-bold">
                    Start Free Trial
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- ======================== HERO ======================== -->
<section class="hero-gradient relative pt-36 pb-24 lg:pt-52 lg:pb-36 overflow-hidden">

    <!-- Background blobs -->
    <div class="absolute top-20 left-1/4 w-80 h-80 bg-blue-400/15 rounded-full blur-3xl animate-blob pointer-events-none"></div>
    <div class="absolute top-32 right-1/4 w-96 h-96 bg-sky-300/15 rounded-full blur-3xl animate-blob pointer-events-none" style="animation-delay:2s"></div>
    <div class="absolute bottom-0 left-1/2 w-64 h-64 bg-indigo-400/10 rounded-full blur-3xl animate-blob pointer-events-none" style="animation-delay:4s"></div>

    <div class="max-w-5xl mx-auto px-5 lg:px-8 text-center relative z-10">

        <!-- Pill badge -->
        <div class="inline-flex items-center gap-2 bg-white border border-blue-200 text-blue-700 text-xs font-bold px-4 py-1.5 rounded-full shadow-sm mb-8 reveal">
            <span class="flex h-2 w-2">
                <span class="animate-ping absolute h-2 w-2 rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative h-2 w-2 rounded-full bg-blue-600"></span>
            </span>
            Now with Multi-Network & Payout Support
        </div>

        <h1 class="font-display text-5xl sm:text-6xl lg:text-8xl font-black tracking-tight mb-6 leading-[1.05] reveal" style="animation-delay:80ms">
            <span class="gradient-text">Run your ISP.</span><br>
            <span class="text-slate-900">Automate everything.</span>
        </h1>

        <p class="text-lg sm:text-xl text-slate-500 font-medium max-w-2xl mx-auto mb-10 reveal" style="animation-delay:160ms">
            The complete billing platform for MikroTik hotspot operators. Direct M-Pesa STK, instant provisioning, vouchers, and a captive portal — all in one dashboard.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center reveal" style="animation-delay:240ms">
            <a href="{{ route('register') }}" class="glow-btn bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-bold text-base flex items-center justify-center gap-2 group transition-colors">
                Deploy Your Network Free
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            <a href="#features" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-8 py-4 rounded-2xl font-semibold text-base flex items-center justify-center gap-2 shadow-sm transition-colors">
                See the Features
            </a>
        </div>

        <!-- Trust bar -->
        <div class="mt-10 flex flex-wrap justify-center items-center gap-6 text-xs text-slate-400 font-semibold reveal" style="animation-delay:320ms">
            <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> 3-Day Free Trial</span>
            <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> 0% Commission</span>
            <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> No credit card needed</span>
            <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Setup in 5 minutes</span>
        </div>
    </div>

    <!-- Dashboard mockup -->
    <div class="max-w-5xl mx-auto mt-20 px-5 reveal animate-float" style="animation-delay:400ms">
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl shadow-slate-300/30 overflow-hidden">
            <!-- Window chrome -->
            <div class="h-9 bg-slate-100 border-b border-slate-200 flex items-center px-4 gap-1.5">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                <div class="flex-1 mx-4">
                    <div class="h-4 w-48 bg-slate-200 rounded-full mx-auto"></div>
                </div>
            </div>
            <!-- UI skeleton -->
            <div class="grid grid-cols-12 h-72 sm:h-96">
                <div class="col-span-2 bg-slate-900 p-3 space-y-3">
                    <div class="w-7 h-7 bg-blue-600 rounded-lg mb-5"></div>
                    <div class="h-5 bg-slate-700 rounded w-full"></div>
                    <div class="h-5 bg-blue-600 rounded w-full"></div>
                    <div class="h-5 bg-slate-700 rounded w-5/6"></div>
                    <div class="h-5 bg-slate-700 rounded w-4/5"></div>
                    <div class="h-5 bg-slate-700 rounded w-5/6"></div>
                    <div class="h-5 bg-slate-700 rounded w-3/4"></div>
                </div>
                <div class="col-span-10 bg-slate-50 p-5">
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm">
                            <div class="h-2.5 w-16 bg-slate-200 rounded mb-2"></div>
                            <div class="h-6 w-24 bg-blue-600 rounded"></div>
                        </div>
                        <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm">
                            <div class="h-2.5 w-16 bg-slate-200 rounded mb-2"></div>
                            <div class="h-6 w-20 bg-emerald-500 rounded"></div>
                        </div>
                        <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm">
                            <div class="h-2.5 w-16 bg-slate-200 rounded mb-2"></div>
                            <div class="h-6 w-16 bg-slate-300 rounded"></div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl border border-slate-200 p-4 mb-3">
                        <!-- Chart bars -->
                        <div class="flex items-end gap-2 h-20">
                            @foreach([40,65,45,80,55,90,70] as $h)
                            <div class="flex-1 bg-gradient-to-t from-blue-600 to-sky-400 rounded-t opacity-80" style="height:{{ $h }}%"></div>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-7 bg-white border border-slate-100 rounded-lg shadow-sm"></div>
                        <div class="h-7 bg-white border border-slate-100 rounded-lg shadow-sm"></div>
                        <div class="h-7 bg-white border border-slate-100 rounded-lg shadow-sm"></div>
                    </div>
                </div>
            </div>
            <!-- Bottom fade -->
            <div class="absolute bottom-0 left-0 w-full h-20 bg-gradient-to-t from-white/90 to-transparent pointer-events-none"></div>
        </div>
    </div>
</section>

<!-- ======================== LOGO CLOUD ======================== -->
<div class="py-10 bg-white border-y border-slate-100">
    <div class="max-w-4xl mx-auto px-5 text-center">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Integrates Natively With</p>
        <div class="flex flex-wrap justify-center items-center gap-10 sm:gap-16">
            <span class="text-xl font-display font-bold text-slate-700 opacity-60">MikroTik</span>
            <span class="text-xl font-display font-bold text-[#46b245] opacity-70">M-Pesa</span>
            <span class="text-xl font-display font-bold text-slate-700 opacity-60">RouterOS</span>
            <span class="text-xl font-display font-bold text-slate-700 opacity-60">Safaricom</span>
        </div>
    </div>
</div>

<!-- ======================== FEATURES ======================== -->
<section id="features" class="py-28 bg-slate-50">
    <div class="max-w-7xl mx-auto px-5 lg:px-8">

        <div class="text-center mb-16 reveal">
            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">What's Included</p>
            <h2 class="font-display text-4xl sm:text-5xl font-black text-slate-900 tracking-tight mb-4">Everything your ISP needs.</h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">One platform to handle billing, routing, customer management, and payouts. No third-party tools needed.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

            @php
            $features = [
                ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'blue', 'title' => 'Instant M-Pesa Provisioning', 'desc' => 'Customer pays → STK push hits their phone → router grants access. All within seconds, fully automated.'],
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.956 11.956 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'color' => 'emerald', 'title' => 'Keep 100% of Revenue', 'desc' => 'Connect your own M-Pesa Till or Paybill. Money goes directly to you. We charge a flat monthly fee — nothing more.'],
                ['icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z', 'color' => 'purple', 'title' => 'Voucher & Scratch Cards', 'desc' => 'Generate printable voucher batches for resellers. Supports both time and balance-based vouchers with usage limits.'],
                ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'color' => 'indigo', 'title' => 'Multi-Network Support', 'desc' => 'Manage multiple hotspot locations from one account. Each gets its own captive portal URL, offers, and router.'],
                ['icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'orange', 'title' => 'Revenue Analytics & CSV Export', 'desc' => 'Filter transactions by date, status, or phone number. Export to CSV for your accountant with one click.'],
                ['icon' => 'M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'rose', 'title' => 'Web Terminal', 'desc' => 'Run RouterOS commands directly from your browser. Full command log with device and timestamp — great for debugging.'],
                ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'cyan', 'title' => 'Customer CRM', 'desc' => 'Full customer profiles with session history, spending, and ban control. View all active sessions and kick users remotely.'],
                ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'color' => 'teal', 'title' => 'Wallet & Payouts', 'desc' => 'Your earnings accumulate in a wallet. Request withdrawals to M-Pesa or bank at any time with full ledger history.'],
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'amber', 'title' => 'Receipt & Invoice Download', 'desc' => 'Every successful transaction generates a branded receipt. Customers can print or save as PDF for their records.'],
            ];
            @endphp

            @foreach($features as $i => $f)
            @php
            $colors = [
                'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-600'],
                'emerald'=> ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-600'],
                'purple' => ['bg' => 'bg-purple-50',  'icon' => 'text-purple-600'],
                'indigo' => ['bg' => 'bg-indigo-50',  'icon' => 'text-indigo-600'],
                'orange' => ['bg' => 'bg-orange-50',  'icon' => 'text-orange-600'],
                'rose'   => ['bg' => 'bg-rose-50',    'icon' => 'text-rose-600'],
                'cyan'   => ['bg' => 'bg-cyan-50',    'icon' => 'text-cyan-600'],
                'teal'   => ['bg' => 'bg-teal-50',    'icon' => 'text-teal-600'],
                'amber'  => ['bg' => 'bg-amber-50',   'icon' => 'text-amber-600'],
            ];
            $c = $colors[$f['color']];
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 p-6 card-hover reveal" style="animation-delay: {{ $i * 60 }}ms">
                <div class="w-11 h-11 {{ $c['bg'] }} {{ $c['icon'] }} rounded-xl flex items-center justify-center mb-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/></svg>
                </div>
                <h3 class="font-display font-bold text-slate-900 text-base mb-2">{{ $f['title'] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach

        </div>
    </div>
</section>

<!-- ======================== HOW IT WORKS ======================== -->
<section id="how-it-works" class="py-28 bg-white border-t border-slate-100">
    <div class="max-w-5xl mx-auto px-5 lg:px-8">

        <div class="text-center mb-16 reveal">
            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">Simple Setup</p>
            <h2 class="font-display text-4xl font-black text-slate-900 tracking-tight mb-4">Up and running in minutes.</h2>
            <p class="text-slate-500 font-medium max-w-xl mx-auto">No server setup. No complex configuration. Just sign up and connect.</p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            @php
            $steps = [
                ['n'=>'01','title'=>'Create Account','desc'=>'Sign up with your ISP name and email. Your 3-day trial starts immediately.'],
                ['n'=>'02','title'=>'Add Your Router','desc'=>'Enter your MikroTik IP, port, and credentials. We connect securely and instantly.'],
                ['n'=>'03','title'=>'Set Up M-Pesa','desc'=>'Add your Safaricom Daraja API keys. Your Till or Paybill — your money.'],
                ['n'=>'04','title'=>'Share Portal URL','desc'=>'Customers scan your WiFi, visit the captive portal, pay, and get instant access.'],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="text-center relative reveal" style="animation-delay: {{ $i * 100 }}ms">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-sky-500 text-white rounded-2xl flex items-center justify-center font-display font-black text-lg mx-auto mb-4 shadow-lg shadow-blue-500/25">
                    {{ $step['n'] }}
                </div>
                <h3 class="font-display font-bold text-slate-900 mb-2">{{ $step['title'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ======================== SOCIAL PROOF ======================== -->
<section class="py-20 bg-slate-900 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(37,99,235,0.15),transparent_60%)] pointer-events-none"></div>
    <div class="max-w-5xl mx-auto px-5 lg:px-8 relative z-10">
        <div class="grid md:grid-cols-3 gap-6">
            @php
            $stats = [
                ['value'=>'KES 0','label'=>'Commission on your sales','sub'=>'100% of every payment goes directly to your M-Pesa'],
                ['value'=>'< 5s','label'=>'Average provisioning time','sub'=>'From STK confirmation to live internet access'],
                ['value'=>'KES 500','label'=>'Flat monthly fee','sub'=>'Unlimited routers, users, and transactions included'],
            ];
            @endphp
            @foreach($stats as $stat)
            <div class="text-center reveal">
                <div class="font-display text-5xl font-black text-white mb-2">{{ $stat['value'] }}</div>
                <div class="text-blue-400 font-bold text-sm mb-1">{{ $stat['label'] }}</div>
                <div class="text-slate-400 text-xs">{{ $stat['sub'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ======================== PRICING ======================== -->
<section id="pricing" class="py-28 bg-white">
    <div class="max-w-4xl mx-auto px-5 lg:px-8 text-center">

        <div class="reveal">
            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">Simple Pricing</p>
            <h2 class="font-display text-4xl sm:text-5xl font-black text-slate-900 tracking-tight mb-4">One plan. Everything included.</h2>
            <p class="text-slate-500 font-medium max-w-xl mx-auto mb-12">No setup fees. No revenue cuts. No surprises. Cancel anytime.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden max-w-lg mx-auto reveal">
            <!-- Card top gradient band -->
            <div class="h-2 bg-gradient-to-r from-blue-600 to-sky-400"></div>
            <div class="p-10">
                <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full mb-6">
                    🎉 3 Days Free — No Card Required
                </div>
                <div class="flex justify-center items-end gap-2 mb-2">
                    <span class="font-display text-7xl font-black text-slate-900 leading-none">500</span>
                    <div class="text-left mb-2">
                        <div class="text-slate-600 font-bold">KES</div>
                        <div class="text-slate-400 text-sm">/ month</div>
                    </div>
                </div>
                <p class="text-slate-400 text-sm mb-8">After your free trial. Billed monthly.</p>

                <ul class="space-y-3.5 mb-8 text-left">
                    @php
                    $perks = [
                        'Unlimited MikroTik Routers',
                        'Unlimited Connected Customers',
                        'Multiple Hotspot Networks',
                        'Direct M-Pesa STK Integration',
                        '100% Revenue — Zero Commission',
                        'Customer CRM & Session Management',
                        'Voucher Generation & Printing',
                        'CSV Reports & PDF Receipts',
                        'Wallet & Payout System',
                        'Web Terminal for RouterOS',
                        'Email & Priority Support',
                    ];
                    @endphp
                    @foreach($perks as $perk)
                    <li class="flex items-center gap-3 text-sm font-medium text-slate-700">
                        <div class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        {{ $perk }}
                    </li>
                    @endforeach
                </ul>

                <a href="{{ route('register') }}" class="glow-btn block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl text-base text-center transition-colors">
                    Start My Free Trial →
                </a>
                <p class="text-slate-400 text-xs mt-4">No credit card required. Upgrade anytime via M-Pesa.</p>
            </div>
        </div>
    </div>
</section>

<!-- ======================== CTA BANNER ======================== -->
<section class="py-20 bg-gradient-to-br from-blue-700 via-blue-600 to-sky-500 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-3xl mx-auto px-5 text-center relative z-10 reveal">
        <h2 class="font-display text-4xl sm:text-5xl font-black text-white mb-5 tracking-tight">Ready to automate your hotspot?</h2>
        <p class="text-blue-100 text-lg font-medium mb-8">Join ISP operators across Africa using goAfrica Connect to run their networks on autopilot.</p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-blue-700 hover:bg-blue-50 px-8 py-4 rounded-2xl font-bold text-base shadow-xl transition-colors group">
            Get Started Free
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</section>

<!-- ======================== FOOTER ======================== -->
<footer class="bg-slate-900 text-slate-400 py-12">
    <div class="max-w-7xl mx-auto px-5 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-sky-500 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="font-display font-bold text-white text-lg">goAfrica Connect</span>
            </div>
            <div class="flex gap-8 text-sm font-semibold">
                <a href="#features" class="hover:text-white transition-colors">Features</a>
                <a href="#pricing" class="hover:text-white transition-colors">Pricing</a>
                <a href="{{ route('login') }}" class="hover:text-white transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 transition-colors">Register</a>
            </div>
            <p class="text-xs">© {{ date('Y') }} goAfrica Connect. Powering Africa's ISPs.</p>
        </div>
    </div>
</footer>

<script>
// Scroll reveal
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('revealed');
            e.target.style.opacity = '1';
            observer.unobserve(e.target);
        }
    });
}, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// Sticky nav shadow shadow
window.addEventListener('scroll', () => {
    document.getElementById('navbar').classList.toggle('shadow-md', window.scrollY > 20);
});
</script>
</body>
</html>
