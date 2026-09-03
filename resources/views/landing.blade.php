<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>goAfrica Connect — MikroTik Hotspot Billing &amp; M-Pesa Automation for Africa</title>

    {{-- SEO Meta Tags --}}
    <meta name="description" content="goAfrica Connect is a MikroTik hotspot billing SaaS for African ISPs. Automate M-Pesa payments, manage vouchers, captive portals, and keep 100% of your revenue. Start free today.">
    <meta name="keywords" content="MikroTik billing, hotspot billing Africa, M-Pesa ISP, captive portal Kenya, ISP billing system, RouterOS billing, Safaricom M-Pesa hotspot, goAfrica, internet service provider billing">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#2563eb">
    <link rel="canonical" href="https://goafrica.site/">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://goafrica.site/">
    <meta property="og:title" content="goAfrica Connect — MikroTik Hotspot Billing &amp; M-Pesa Automation for Africa">
    <meta property="og:description" content="Automate your ISP with M-Pesa payments, MikroTik provisioning, captive portals, and voucher management. Keep 100% of your revenue. Start free.">
    <meta property="og:image" content="https://goafrica.site/og-image.png">
    <meta property="og:site_name" content="goAfrica Connect">
    <meta property="og:locale" content="en_KE">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@goafricaconnect">
    <meta name="twitter:title" content="goAfrica Connect — MikroTik Hotspot Billing &amp; M-Pesa Automation for Africa">
    <meta name="twitter:description" content="Automate your ISP with M-Pesa payments, MikroTik provisioning, captive portals, and voucher management. Keep 100% of your revenue.">
    <meta name="twitter:image" content="https://goafrica.site/og-image.png">

    {{-- JSON-LD Structured Data --}}
    @verbatim
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "goAfrica Connect",
      "url": "https://goafrica.site/",
      "description": "MikroTik hotspot billing and M-Pesa automation SaaS platform for African ISPs.",
      "applicationCategory": "BusinessApplication",
      "operatingSystem": "Web",
      "offers": {
        "@type": "Offer",
        "price": "500",
        "priceCurrency": "KES",
        "description": "Monthly subscription — 3-day free trial included"
      },
      "publisher": {
        "@type": "Organization",
        "name": "goAfrica Connect",
        "url": "https://goafrica.site/",
        "logo": "https://goafrica.site/favicon.png",
        "contactPoint": {
          "@type": "ContactPoint",
          "telephone": "+254748717099",
          "contactType": "customer support",
          "areaServed": "KE",
          "availableLanguage": "English"
        }
      }
    }
    </script>
    @endverbatim

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'xs': '480px',
                        '3xl': '1600px',
                        '4xl': '1920px',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                    },
                    animation: {
                        'blob': 'blob 8s infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%':   { transform: 'translate(0px, 0px) scale(1)' },
                            '33%':  { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%':  { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%':      { transform: 'translateY(-10px)' },
                        },
                    },
                }
            }
        }
    </script>

    <style>
        html {
            font-size: clamp(13px, 1.5vw, 18px);
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Nav shadow on scroll */
        #main-nav {
            transition: box-shadow 0.3s ease, background-color 0.3s ease;
        }
        #main-nav.scrolled {
            box-shadow: 0 4px 24px 0 rgba(30, 64, 175, 0.10);
            background-color: rgba(255,255,255,0.98);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 50%, #0891b2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Feature card hover */
        .feature-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.12);
        }

        /* Chart bars */
        .bar-animate {
            animation: growUp 1.2s ease forwards;
            transform-origin: bottom;
        }
        @keyframes growUp {
            from { transform: scaleY(0); }
            to   { transform: scaleY(1); }
        }

        /* Mobile menu */
        #mobile-menu {
            transition: max-height 0.35s ease, opacity 0.35s ease;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }
        #mobile-menu.open {
            max-height: 500px;
            opacity: 1;
        }
    </style>
</head>

<body class="bg-white text-gray-900 antialiased">

<!-- ============================================================
     NAVIGATION
============================================================ -->
<nav id="main-nav" class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5 flex-shrink-0">
                <img src="/favicon.png" alt="goAfrica Connect logo" class="w-8 h-8 rounded-lg">
                <span class="font-extrabold text-lg text-gray-900 tracking-tight">goAfrica <span class="gradient-text">Connect</span></span>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden md:flex items-center gap-7 text-sm font-medium text-gray-600">
                <a href="#features"     class="hover:text-brand-600 transition-colors">Features</a>
                <a href="#how-it-works" class="hover:text-brand-600 transition-colors">How It Works</a>
                <a href="#pricing"      class="hover:text-brand-600 transition-colors">Pricing</a>
                <a href="#contact"      class="hover:text-brand-600 transition-colors">Contact</a>
            </div>

            {{-- Desktop CTA --}}
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-brand-600 transition-colors px-3 py-2">Sign In</a>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-md shadow-brand-200">
                    Start Free Trial
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            {{-- Mobile burger --}}
            <button id="burger-btn" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors" aria-label="Toggle menu">
                <svg id="icon-open"  class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg id="icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu">
            <div class="py-4 border-t border-gray-100 flex flex-col gap-1 text-sm font-medium">
                <a href="#features"     class="px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition-colors">Features</a>
                <a href="#how-it-works" class="px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition-colors">How It Works</a>
                <a href="#pricing"      class="px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition-colors">Pricing</a>
                <a href="#contact"      class="px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-brand-600 transition-colors">Contact</a>
                <div class="mt-2 flex flex-col gap-2 px-3">
                    <a href="{{ route('login') }}"    class="text-center py-2.5 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition-colors">Sign In</a>
                    <a href="{{ route('register') }}" class="text-center py-2.5 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition-colors">Start Free Trial</a>
                </div>
            </div>
        </div>
    </div>
</nav>


<!-- ============================================================
     HERO SECTION
============================================================ -->
<section class="relative pt-24 pb-20 md:pt-32 md:pb-28 overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">

    {{-- Animated blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-20 w-96 h-96 bg-blue-300/30 rounded-full blur-3xl animate-blob"></div>
        <div class="absolute top-1/3 -right-20 w-80 h-80 bg-violet-300/30 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-20 left-1/3 w-72 h-72 bg-cyan-300/25 rounded-full blur-3xl animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-14 items-center">

            {{-- Left copy --}}
            <div class="text-center lg:text-left">

                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 bg-brand-50 border border-brand-200 text-brand-700 text-xs font-semibold px-3.5 py-1.5 rounded-full mb-6">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-600"></span>
                    </span>
                    Now with Multi-Network &amp; Payout Support
                </div>

                {{-- H1 --}}
                <h1 class="text-4xl xs:text-5xl md:text-6xl font-extrabold leading-[1.1] tracking-tight text-gray-900 mb-6">
                    Run your ISP.<br>
                    <span class="gradient-text">Automate everything.</span>
                </h1>

                {{-- Subtext --}}
                <p class="text-base md:text-lg text-gray-600 leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                    Connect your <strong class="text-gray-800">MikroTik / RouterOS</strong> router, collect payments via
                    <strong class="text-gray-800">M-Pesa</strong>, and give customers a branded
                    <strong class="text-gray-800">captive portal</strong> — all from one dashboard.
                    Built for East African ISPs, no technical complexity required.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start mb-10">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 active:scale-95 text-white font-bold px-6 py-3.5 rounded-2xl transition-all shadow-lg shadow-brand-300/40 text-sm md:text-base">
                        🚀 Deploy Your Network Free
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 active:scale-95 text-gray-800 font-semibold border border-gray-200 px-6 py-3.5 rounded-2xl transition-all text-sm md:text-base">
                        See the Features
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Trust bar --}}
                <div class="flex flex-wrap gap-x-5 gap-y-2 justify-center lg:justify-start text-xs text-gray-500 font-medium">
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> 3-day free trial</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> 0% commission on revenue</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> No credit card needed</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Setup in 5 minutes</span>
                </div>
            </div>

            {{-- Right: Dashboard UI Mockup --}}
            <div class="hidden lg:block animate-float">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                    {{-- Mockup top bar --}}
                    <div class="flex items-center gap-1.5 px-4 py-3 bg-gray-50 border-b border-gray-100">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span class="w-3 h-3 rounded-full bg-green-400"></span>
                        <div class="ml-3 flex-1 bg-gray-200 rounded-full h-4 max-w-xs"></div>
                    </div>

                    {{-- Mockup body --}}
                    <div class="p-5 space-y-4">
                        {{-- Stat cards row --}}
                        <div class="grid grid-cols-3 gap-3">
                            <div class="bg-blue-50 rounded-xl p-3 text-center">
                                <div class="text-xs text-blue-600 font-semibold mb-1">Revenue Today</div>
                                <div class="text-lg font-extrabold text-blue-800">KES 4,820</div>
                            </div>
                            <div class="bg-green-50 rounded-xl p-3 text-center">
                                <div class="text-xs text-green-600 font-semibold mb-1">Active Sessions</div>
                                <div class="text-lg font-extrabold text-green-800">47</div>
                            </div>
                            <div class="bg-violet-50 rounded-xl p-3 text-center">
                                <div class="text-xs text-violet-600 font-semibold mb-1">Vouchers Sold</div>
                                <div class="text-lg font-extrabold text-violet-800">128</div>
                            </div>
                        </div>

                        {{-- Fake chart --}}
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="text-xs font-semibold text-gray-500 mb-3">Weekly Transactions</div>
                            <div class="flex items-end gap-2 h-20">
                                <div class="flex-1 bg-brand-200 rounded-t-md bar-animate" style="height:45%;animation-delay:0.1s"></div>
                                <div class="flex-1 bg-brand-300 rounded-t-md bar-animate" style="height:65%;animation-delay:0.2s"></div>
                                <div class="flex-1 bg-brand-400 rounded-t-md bar-animate" style="height:50%;animation-delay:0.3s"></div>
                                <div class="flex-1 bg-brand-500 rounded-t-md bar-animate" style="height:80%;animation-delay:0.4s"></div>
                                <div class="flex-1 bg-brand-400 rounded-t-md bar-animate" style="height:60%;animation-delay:0.5s"></div>
                                <div class="flex-1 bg-brand-600 rounded-t-md bar-animate" style="height:100%;animation-delay:0.6s"></div>
                                <div class="flex-1 bg-brand-500 rounded-t-md bar-animate" style="height:75%;animation-delay:0.7s"></div>
                            </div>
                            <div class="flex gap-2 mt-2">
                                @php $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']; @endphp
                                @foreach($days as $d)
                                    <div class="flex-1 text-center text-[10px] text-gray-400">{{ $d }}</div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Recent transactions --}}
                        <div>
                            <div class="text-xs font-semibold text-gray-500 mb-2">Recent Transactions</div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-xs font-bold">M</div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">John Kamau</div>
                                            <div class="text-[10px] text-gray-400">M-Pesa • 1hr Package</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-green-600">+KES 50</span>
                                </div>
                                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold">A</div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">Amina Wanjiru</div>
                                            <div class="text-[10px] text-gray-400">Voucher • Daily Package</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-green-600">+KES 100</span>
                                </div>
                                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-violet-100 flex items-center justify-center text-violet-600 text-xs font-bold">P</div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">Peter Odhiambo</div>
                                            <div class="text-[10px] text-gray-400">M-Pesa • Weekly Package</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-green-600">+KES 300</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ============================================================
     LOGO CLOUD
============================================================ -->
<section class="py-10 bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-xs font-semibold uppercase tracking-widest text-gray-400 mb-7">Powered by &amp; integrated with</p>
        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-14">

            {{-- MikroTik --}}
            <div class="flex items-center gap-2 grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all">
                <svg class="w-8 h-8 text-blue-700" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="40" height="40" rx="8" fill="#2563eb"/>
                    <path d="M8 28V12l7 8 5-8 5 8 7-8v16" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="text-base font-bold text-gray-700">MikroTik</span>
            </div>

            {{-- M-Pesa --}}
            <div class="flex items-center gap-2 grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all">
                <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="40" height="40" rx="8" fill="#40be49"/>
                    <text x="5" y="27" font-size="14" font-weight="bold" fill="white" font-family="Arial">M-P</text>
                </svg>
                <span class="text-base font-bold text-gray-700">M-Pesa</span>
            </div>

            {{-- RouterOS --}}
            <div class="flex items-center gap-2 grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all">
                <svg class="w-8 h-8 text-gray-700" viewBox="0 0 40 40" fill="none">
                    <rect width="40" height="40" rx="8" fill="#1f2937"/>
                    <circle cx="20" cy="20" r="8" stroke="white" stroke-width="2"/>
                    <path d="M20 8v4M20 28v4M8 20h4M28 20h4" stroke="white" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="text-base font-bold text-gray-700">RouterOS</span>
            </div>

            {{-- Safaricom --}}
            <div class="flex items-center gap-2 grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all">
                <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none">
                    <rect width="40" height="40" rx="8" fill="#40be49"/>
                    <path d="M12 20c0-4.4 3.6-8 8-8s8 3.6 8 8-3.6 8-8 8" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="20" cy="20" r="3" fill="white"/>
                </svg>
                <span class="text-base font-bold text-gray-700">Safaricom</span>
            </div>

        </div>
    </div>
</section>


<!-- ============================================================
     FEATURES SECTION
============================================================ -->
<section id="features" class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="text-center mb-14 reveal">
            <span class="inline-block bg-brand-50 text-brand-700 text-xs font-semibold uppercase tracking-widest px-3.5 py-1.5 rounded-full border border-brand-100 mb-4">Everything you need</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Powerful features built for African ISPs</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">From instant M-Pesa provisioning to full revenue analytics — every tool you need to run a professional hotspot business.</p>
        </div>

        @php
        $features = [
            [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
                'color' => 'bg-green-50 text-green-600',
                'title' => 'Instant M-Pesa Provisioning',
                'desc'  => 'Customer pays via M-Pesa STK push and is connected to your hotspot in under 5 seconds. Fully automated, zero manual work.',
            ],
            [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                'color' => 'bg-yellow-50 text-yellow-600',
                'title' => 'Keep 100% of Revenue',
                'desc'  => 'We charge a flat monthly fee, never a commission. Every shilling your customers pay goes directly to you.',
            ],
            [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>',
                'color' => 'bg-violet-50 text-violet-600',
                'title' => 'Voucher &amp; Scratch Cards',
                'desc'  => 'Generate bulk voucher codes and printable scratch cards for offline payment. Perfect for markets with limited M-Pesa penetration.',
            ],
            [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>',
                'color' => 'bg-blue-50 text-blue-600',
                'title' => 'Multi-Network Support',
                'desc'  => 'Manage multiple MikroTik routers and locations from a single dashboard. Scale from one hotspot to a city-wide network.',
            ],
            [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                'color' => 'bg-orange-50 text-orange-600',
                'title' => 'Revenue Analytics &amp; CSV Export',
                'desc'  => 'Visual charts showing daily, weekly, and monthly revenue. Export any report to CSV for your accounting software.',
            ],
            [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                'color' => 'bg-slate-50 text-slate-600',
                'title' => 'Web Terminal',
                'desc'  => 'Execute RouterOS commands directly from your browser without SSH. Diagnose issues and manage your router on the go.',
            ],
            [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
                'color' => 'bg-pink-50 text-pink-600',
                'title' => 'Customer CRM',
                'desc'  => 'Store customer profiles, session history, and contact details. Send notifications and track usage patterns per customer.',
            ],
            [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                'color' => 'bg-teal-50 text-teal-600',
                'title' => 'Wallet &amp; Payouts',
                'desc'  => 'Revenue accumulates in your goAfrica wallet. Request M-Pesa payouts anytime — funds land in minutes.',
            ],
            [
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                'color' => 'bg-indigo-50 text-indigo-600',
                'title' => 'Receipt &amp; Invoice Download',
                'desc'  => 'Every transaction generates a branded receipt. Customers can download PDF invoices; ISPs get full transaction records.',
            ],
        ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($features as $index => $feature)
            <div class="feature-card reveal bg-white rounded-2xl border border-gray-100 p-6 shadow-sm" style="transition-delay: {{ $index * 60 }}ms">
                <div class="inline-flex items-center justify-center w-11 h-11 rounded-xl {{ $feature['color'] }} mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $feature['icon'] !!}</svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">{!! $feature['title'] !!}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>


<!-- ============================================================
     HOW IT WORKS
============================================================ -->
<section id="how-it-works" class="py-20 md:py-28 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14 reveal">
            <span class="inline-block bg-brand-50 text-brand-700 text-xs font-semibold uppercase tracking-widest px-3.5 py-1.5 rounded-full border border-brand-100 mb-4">Simple setup</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Up and running in 4 steps</h2>
            <p class="text-gray-500 max-w-xl mx-auto">No complicated configuration. No server setup. Just connect, configure, and collect payments.</p>
        </div>

        <div class="relative">
            {{-- Connector line (desktop) --}}
            <div class="hidden md:block absolute top-10 left-[calc(12.5%+1.25rem)] right-[calc(12.5%+1.25rem)] h-0.5 bg-gradient-to-r from-brand-200 via-brand-400 to-brand-200"></div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                {{-- Step 1 --}}
                <div class="reveal text-center" style="transition-delay:0ms">
                    <div class="relative inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-brand-600 text-white shadow-lg shadow-brand-300/40 mb-5 mx-auto">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white text-brand-700 text-xs font-extrabold flex items-center justify-center shadow">1</span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">Create Account</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Sign up in under a minute. No credit card required — your 3-day trial starts immediately.</p>
                </div>

                {{-- Step 2 --}}
                <div class="reveal text-center" style="transition-delay:100ms">
                    <div class="relative inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-violet-600 text-white shadow-lg shadow-violet-300/40 mb-5 mx-auto">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white text-violet-700 text-xs font-extrabold flex items-center justify-center shadow">2</span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">Add Your Router</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Enter your MikroTik router's IP, API port, and credentials. We connect and verify instantly.</p>
                </div>

                {{-- Step 3 --}}
                <div class="reveal text-center" style="transition-delay:200ms">
                    <div class="relative inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-green-600 text-white shadow-lg shadow-green-300/40 mb-5 mx-auto">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white text-green-700 text-xs font-extrabold flex items-center justify-center shadow">3</span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">Set Up M-Pesa</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Link your Safaricom Daraja API credentials and configure your STK push shortcode. Done in 2 minutes.</p>
                </div>

                {{-- Step 4 --}}
                <div class="reveal text-center" style="transition-delay:300ms">
                    <div class="relative inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-orange-500 text-white shadow-lg shadow-orange-300/40 mb-5 mx-auto">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white text-orange-700 text-xs font-extrabold flex items-center justify-center shadow">4</span>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">Share Portal URL</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Share your branded captive portal link. Customers land, choose a package, pay via M-Pesa, and connect.</p>
                </div>

            </div>
        </div>

    </div>
</section>


<!-- ============================================================
     STATS BANNER
============================================================ -->
<section class="py-14 bg-gray-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">

            <div class="reveal">
                <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">KES 0</div>
                <div class="text-sm text-gray-400 font-medium">Commission on your revenue</div>
                <div class="text-xs text-gray-500 mt-1">We never take a cut — you keep everything</div>
            </div>

            <div class="reveal" style="transition-delay:100ms">
                <div class="text-4xl md:text-5xl font-extrabold text-brand-400 mb-2">&lt; 5s</div>
                <div class="text-sm text-gray-400 font-medium">Average provisioning time</div>
                <div class="text-xs text-gray-500 mt-1">From M-Pesa payment to internet access</div>
            </div>

            <div class="reveal" style="transition-delay:200ms">
                <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">KES 500</div>
                <div class="text-sm text-gray-400 font-medium">Flat monthly subscription</div>
                <div class="text-xs text-gray-500 mt-1">Unlimited routers, sessions &amp; customers</div>
            </div>

        </div>
    </div>
</section>


<!-- ============================================================
     PRICING SECTION
============================================================ -->
<section id="pricing" class="py-20 md:py-28 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12 reveal">
            <span class="inline-block bg-brand-50 text-brand-700 text-xs font-semibold uppercase tracking-widest px-3.5 py-1.5 rounded-full border border-brand-100 mb-4">Simple pricing</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">One plan. Everything included.</h2>
            <p class="text-gray-500 max-w-xl mx-auto">No hidden fees, no per-transaction cuts, no feature tiers. Just one honest flat rate.</p>
        </div>

        <div class="reveal max-w-md mx-auto">
            <div class="relative bg-gradient-to-br from-brand-600 to-brand-800 rounded-3xl p-8 shadow-2xl shadow-brand-300/30 text-white overflow-hidden">

                {{-- Background decoration --}}
                <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

                {{-- Free trial badge --}}
                <div class="absolute top-5 right-5">
                    <span class="inline-flex items-center gap-1 bg-yellow-400 text-yellow-900 text-xs font-extrabold px-3 py-1 rounded-full shadow">
                        ⚡ 3-Day Free Trial
                    </span>
                </div>

                {{-- Plan name --}}
                <div class="mb-6">
                    <div class="text-sm font-semibold text-brand-200 uppercase tracking-widest mb-1">Pro Plan</div>
                    <div class="flex items-end gap-1">
                        <span class="text-5xl font-extrabold">500</span>
                        <span class="text-xl font-bold text-brand-200 mb-1.5">KES</span>
                        <span class="text-brand-200 text-sm mb-1.5">/ month</span>
                    </div>
                    <p class="text-brand-200 text-sm mt-2">Everything you need to run a professional ISP business.</p>
                </div>

                {{-- Feature checklist --}}
                <ul class="space-y-3 mb-8">
                    @php
                    $planFeatures = [
                        'Unlimited MikroTik routers',
                        'Unlimited customer sessions',
                        'M-Pesa STK Push integration',
                        'Branded captive portal',
                        'Voucher &amp; scratch card generator',
                        'Revenue analytics &amp; CSV export',
                        'Customer CRM &amp; profiles',
                        'Wallet &amp; M-Pesa payouts',
                        'PDF receipts &amp; invoices',
                        'Web terminal (RouterOS CLI)',
                        'Email &amp; dashboard notifications',
                    ];
                    @endphp
                    @foreach($planFeatures as $item)
                    <li class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {!! $item !!}
                    </li>
                    @endforeach
                </ul>

                {{-- CTA --}}
                <a href="{{ route('register') }}" class="block text-center bg-white hover:bg-gray-50 active:scale-95 text-brand-700 font-extrabold text-base py-4 rounded-2xl transition-all shadow-lg">
                    Start My Free Trial →
                </a>
                <p class="text-center text-xs text-brand-200 mt-3">No credit card required · Cancel anytime</p>

            </div>
        </div>

    </div>
</section>


<!-- ============================================================
     CONTACT SECTION
============================================================ -->
<section id="contact" class="py-20 md:py-28 bg-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14 reveal">
            <span class="inline-block bg-brand-50 text-brand-700 text-xs font-semibold uppercase tracking-widest px-3.5 py-1.5 rounded-full border border-brand-100 mb-4">Get in touch</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Contact Us</h2>
            <p class="text-gray-500 max-w-xl mx-auto">Have a question, need technical help, or want a demo? Reach out — we're based in Kenya and respond fast.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- Left: contact info --}}
            <div class="space-y-5 reveal">

                {{-- Phone --}}
                <div class="flex items-start gap-4 bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Phone / WhatsApp</div>
                        <a href="tel:+254748717099" class="text-base font-bold text-gray-900 hover:text-brand-600 transition-colors">+254 748 717 099</a>
                        <p class="text-sm text-gray-500 mt-0.5">Available Mon–Sat, 8am–8pm EAT</p>
                    </div>
                </div>

                {{-- Email --}}
                <div class="flex items-start gap-4 bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Email</div>
                        <a href="mailto:support@goafrica.site" class="block text-base font-bold text-gray-900 hover:text-brand-600 transition-colors">support@goafrica.site</a>
                        <a href="mailto:noreply@goafrica.site" class="block text-sm text-gray-500 hover:text-brand-600 transition-colors mt-0.5">noreply@goafrica.site</a>
                    </div>
                </div>

                {{-- Location --}}
                <div class="flex items-start gap-4 bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Location</div>
                        <div class="text-base font-bold text-gray-900">Based in Kenya</div>
                        <p class="text-sm text-gray-500 mt-0.5">Serving East Africa &amp; beyond</p>
                    </div>
                </div>

                {{-- Dashboard note --}}
                <div class="flex items-start gap-3 bg-brand-50 rounded-2xl border border-brand-100 p-4">
                    <svg class="w-5 h-5 text-brand-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-brand-700">Registered ISPs will also receive a reply notification in their dashboard.</p>
                </div>

            </div>

            {{-- Right: contact form --}}
            <div class="reveal" style="transition-delay:100ms">
                <div class="bg-white rounded-2xl border border-gray-100 p-7 shadow-sm">

                    {{-- Success message --}}
                    @if(session('contact_success'))
                    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 mb-6">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-sm font-medium">{{ session('contact_success') }}</span>
                    </div>
                    @endif

                    {{-- Error message --}}
                    @if($errors->any())
                    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span class="text-sm font-medium">{{ $errors->first() }}</span>
                    </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5" for="contact_name">Your Name <span class="text-red-500">*</span></label>
                                <input
                                    type="text"
                                    id="contact_name"
                                    name="name"
                                    required
                                    placeholder="e.g. John Kamau"
                                    value="{{ old('name') }}"
                                    class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent placeholder-gray-400 transition-shadow"
                                >
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5" for="contact_email">Email Address <span class="text-red-500">*</span></label>
                                <input
                                    type="email"
                                    id="contact_email"
                                    name="email"
                                    required
                                    placeholder="you@example.com"
                                    value="{{ old('email') }}"
                                    class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent placeholder-gray-400 transition-shadow"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5" for="contact_subject">Subject <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="contact_subject"
                                name="subject"
                                required
                                placeholder="e.g. Integration help, billing question..."
                                value="{{ old('subject') }}"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent placeholder-gray-400 transition-shadow"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5" for="contact_message">Message <span class="text-red-500">*</span></label>
                            <textarea
                                id="contact_message"
                                name="message"
                                required
                                rows="5"
                                placeholder="Tell us how we can help..."
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent placeholder-gray-400 resize-none transition-shadow"
                            >{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 active:scale-95 text-white font-bold py-3.5 rounded-xl transition-all shadow-md shadow-brand-200 text-sm">
                            Send Message →
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</section>


<!-- ============================================================
     CTA BANNER
============================================================ -->
<section class="py-20 bg-gradient-to-br from-brand-600 via-brand-700 to-indigo-700 relative overflow-hidden">
    {{-- Decorations --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-white/5 rounded-full"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full"></div>
    </div>
    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight">
            Ready to automate your hotspot?
        </h2>
        <p class="text-brand-200 text-base md:text-lg mb-8 max-w-xl mx-auto">
            Join ISPs across East Africa who've ditched manual billing and started running smarter networks with goAfrica Connect.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 active:scale-95 text-brand-700 font-extrabold px-8 py-4 rounded-2xl transition-all shadow-xl text-base">
                🚀 Get Started Free
            </a>
            <a href="#contact" class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 active:scale-95 text-white font-semibold border border-white/20 px-8 py-4 rounded-2xl transition-all text-base">
                Talk to Us
            </a>
        </div>
        <p class="text-brand-300 text-xs mt-5">3-day free trial · No credit card · Cancel anytime</p>
    </div>
</section>


<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="bg-gray-950 text-gray-400 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-8 mb-10">

            {{-- Logo & tagline --}}
            <div class="text-center md:text-left">
                <a href="/" class="inline-flex items-center gap-2.5 mb-3">
                    <img src="/favicon.png" alt="goAfrica Connect" class="w-8 h-8 rounded-lg">
                    <span class="font-extrabold text-lg text-white tracking-tight">goAfrica <span class="text-brand-400">Connect</span></span>
                </a>
                <p class="text-sm text-gray-500 max-w-xs">MikroTik hotspot billing &amp; M-Pesa automation for African ISPs.</p>
            </div>

            {{-- Nav links --}}
            <nav class="flex flex-wrap justify-center md:justify-end gap-5 text-sm">
                <a href="#features"  class="hover:text-white transition-colors">Features</a>
                <a href="#pricing"   class="hover:text-white transition-colors">Pricing</a>
                <a href="#contact"   class="hover:text-white transition-colors">Contact</a>
                <a href="{{ route('login') }}"    class="hover:text-white transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="hover:text-white transition-colors">Register</a>
            </nav>

        </div>

        <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
            <p>&copy; {{ date('Y') }} goAfrica Connect. All rights reserved.</p>
            <p>Built with ❤️ in Kenya for Africa</p>
        </div>

    </div>
</footer>


<!-- ============================================================
     JAVASCRIPT
============================================================ -->
<script>
    // ── Sticky nav shadow on scroll ──────────────────────────────
    const nav = document.getElementById('main-nav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });


    // ── Mobile menu toggle ───────────────────────────────────────
    const burgerBtn  = document.getElementById('burger-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconOpen   = document.getElementById('icon-open');
    const iconClose  = document.getElementById('icon-close');

    burgerBtn.addEventListener('click', () => {
        const isOpen = mobileMenu.classList.toggle('open');
        iconOpen.classList.toggle('hidden', isOpen);
        iconClose.classList.toggle('hidden', !isOpen);
    });

    // Close mobile menu on anchor link click
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('open');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
        });
    });


    // ── Scroll reveal with IntersectionObserver ──────────────────
    const revealEls = document.querySelectorAll('.reveal');

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -40px 0px'
    });

    revealEls.forEach(el => revealObserver.observe(el));


    // ── Smooth scroll for anchor links ───────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                const offset = nav.offsetHeight + 8;
                const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });
</script>

</body>
</html>
