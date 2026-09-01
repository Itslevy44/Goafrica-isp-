<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>goAfrica Connect - The Modern ISP Billing Engine</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0ea5e9',
                        secondary: '#3b82f6',
                        surface: '#ffffff',
                        'surface-light': '#f8fafc',
                        border: '#e2e8f0',
                        slate: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc; /* slate-50 */
            color: #0f172a; /* slate-900 */
        }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8); /* slate-200 */
        }

        .gradient-text {
            background: linear-gradient(to right, #0f172a 30%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-text-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glass-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 1) 0%, rgba(248, 250, 252, 0.8) 100%);
            border: 1px solid rgba(226, 232, 240, 1);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(12px);
        }

        .feature-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .feature-card::before {
            content: "";
            position: absolute;
            inset: -1px;
            background: linear-gradient(to bottom right, #0ea5e9, #3b82f6);
            border-radius: inherit;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .mesh-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(at 0% 0%, rgba(14, 165, 233, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(59, 130, 246, 0.08) 0px, transparent 50%);
            z-index: -1;
            pointer-events: none;
        }

        .glow-button {
            position: relative;
        }
        
        .glow-button::after {
            content: '';
            position: absolute;
            top: -2px; left: -2px; right: -2px; bottom: -2px;
            background: linear-gradient(45deg, #0ea5e9, #3b82f6, #0ea5e9);
            z-index: -1;
            border-radius: 9999px;
            background-size: 200% 200%;
            animation: moveGradient 3s linear infinite;
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }
        
        .glow-button:hover::after {
            opacity: 0.8;
        }

        @keyframes moveGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .reveal {
            opacity: 0;
        }
    </style>
</head>
<body class="antialiased selection:bg-primary selection:text-white relative min-h-screen">

    <div class="mesh-bg"></div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3 cursor-pointer" onclick="window.scrollTo(0,0)">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="font-display font-bold text-xl tracking-tight text-slate-900">goAfrica</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-10">
                    <a href="#features" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Features</a>
                    <a href="#how-it-works" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">How it Works</a>
                    <a href="#pricing" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Pricing</a>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="hidden md:block text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Sign In</a>
                    <a href="{{ route('register') }}" class="bg-slate-900 text-white hover:bg-slate-800 px-5 py-2.5 rounded-full text-sm font-semibold transition-all shadow-sm">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        
        <!-- Animated Background Orbs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-sky-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-blob"></div>
        <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-blue-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center">
            
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-blue-200 bg-blue-50 text-blue-700 text-xs font-bold tracking-wide uppercase mb-8 reveal animate-fade-in-up shadow-sm">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                </span>
                ISP Management, Reimagined.
            </div>
            
            <h1 class="font-display text-5xl md:text-7xl lg:text-8xl font-bold tracking-tight mb-8 reveal animate-fade-in-up" style="animation-delay: 100ms;">
                <span class="gradient-text">Automate your ISP.</span><br />
                <span class="gradient-text-primary">Maximize Revenue.</span>
            </h1>
            
            <p class="mt-6 max-w-2xl text-lg md:text-xl text-slate-600 mx-auto mb-12 font-medium reveal animate-fade-in-up" style="animation-delay: 200ms;">
                The ultimate platform for MikroTik hotspot providers. Direct M-Pesa integration, automated provisioning, and zero commission fees.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-5 justify-center reveal animate-fade-in-up" style="animation-delay: 300ms;">
                <a href="{{ route('register') }}" class="glow-button bg-blue-600 text-white px-8 py-4 rounded-full font-bold text-base transition-all flex items-center justify-center gap-2 group shadow-lg shadow-blue-600/30">
                    Deploy Your Network
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="#features" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 px-8 py-4 rounded-full font-semibold text-base transition-all flex items-center justify-center shadow-sm">
                    Explore Features
                </a>
            </div>
        </div>

        <!-- Dashboard Preview Image/Mockup -->
        <div class="max-w-6xl mx-auto mt-24 px-6 reveal animate-fade-in-up" style="animation-delay: 500ms;">
            <div class="glass-card rounded-2xl p-2 ring-1 ring-slate-200 shadow-2xl overflow-hidden relative">
                <!-- Mac OS style window header -->
                <div class="h-10 bg-slate-100 border-b border-slate-200 flex items-center px-4 gap-2 absolute top-0 w-full left-0 z-20">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                </div>
                
                <div class="pt-10 bg-white rounded-xl overflow-hidden">
                    <div class="grid grid-cols-12 h-[500px]">
                        <!-- Sidebar Mockup -->
                        <div class="col-span-3 border-r border-slate-100 p-4 space-y-4 bg-slate-50">
                            <div class="h-8 w-8 bg-blue-600 rounded-lg mb-8 shadow-sm"></div>
                            <div class="h-6 w-3/4 bg-slate-200 rounded"></div>
                            <div class="h-6 w-full bg-slate-200 rounded"></div>
                            <div class="h-6 w-5/6 bg-slate-200 rounded"></div>
                            <div class="h-6 w-4/5 bg-slate-200 rounded"></div>
                        </div>
                        <!-- Main Content Mockup -->
                        <div class="col-span-9 p-8 bg-white">
                            <div class="flex justify-between items-end mb-8">
                                <div>
                                    <div class="h-4 w-32 bg-slate-200 rounded mb-2"></div>
                                    <div class="h-10 w-48 bg-gradient-to-r from-sky-400 to-blue-500 rounded shadow-sm"></div>
                                </div>
                                <div class="h-10 w-32 bg-slate-100 border border-slate-200 rounded-lg"></div>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-4 mb-8">
                                <div class="h-28 bg-white border border-slate-200 shadow-sm rounded-xl"></div>
                                <div class="h-28 bg-white border border-slate-200 shadow-sm rounded-xl"></div>
                                <div class="h-28 bg-white border border-slate-200 shadow-sm rounded-xl"></div>
                            </div>
                            
                            <div class="h-48 bg-slate-50 border border-slate-200 rounded-xl"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Fade out gradient at bottom of mockup -->
                <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-white to-transparent z-20"></div>
            </div>
        </div>
    </section>

    <!-- Logo Cloud -->
    <section class="py-12 border-y border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm font-bold text-slate-400 mb-6 uppercase tracking-widest">Built For Modern Technologies</p>
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-60 grayscale hover:grayscale-0 transition-all duration-500 text-slate-800">
                <span class="text-2xl font-bold font-display tracking-tight">MikroTik</span>
                <span class="text-2xl font-bold font-display tracking-tight text-[#46b245]">M-Pesa</span>
                <span class="text-2xl font-bold font-display tracking-tight">RouterOS</span>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-32 relative bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20 reveal">
                <h2 class="text-blue-600 font-bold tracking-widest uppercase text-sm mb-3">Power Features</h2>
                <h3 class="font-display text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">Designed for performance.</h3>
                <p class="text-lg text-slate-600 font-medium">Everything you need to automate billing, manage routers, and scale your customer base without touching code.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="feature-card rounded-2xl p-8 reveal">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Instant Provisioning</h4>
                    <p class="text-slate-600 leading-relaxed text-sm font-medium">Customers pay via M-Pesa and are instantly granted internet access. No manual intervention required.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card rounded-2xl p-8 reveal">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.956 11.956 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Keep 100% of Revenue</h4>
                    <p class="text-slate-600 leading-relaxed text-sm font-medium">We don't take a cut. Connect your own Till or Paybill and money goes directly to you instantly.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card rounded-2xl p-8 reveal">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Web Terminal</h4>
                    <p class="text-slate-600 leading-relaxed text-sm font-medium">Debug and run RouterOS commands directly from your dashboard using our built-in terminal emulator.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card rounded-2xl p-8 reveal">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Advanced Analytics</h4>
                    <p class="text-slate-600 leading-relaxed text-sm font-medium">Visualize your network's financial performance. See your top packages and track monthly revenue growth.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card rounded-2xl p-8 reveal">
                    <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Voucher Generation</h4>
                    <p class="text-slate-600 leading-relaxed text-sm font-medium">Selling offline? Generate batches of beautiful, printable scratch cards for your resellers instantly.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card rounded-2xl p-8 reveal">
                    <div class="w-12 h-12 bg-cyan-100 text-cyan-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Multi-Router Support</h4>
                    <p class="text-slate-600 leading-relaxed text-sm font-medium">Manage multiple hotspot locations from a single dashboard. Deploy internet plans across all routers at once.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-32 border-t border-slate-200 bg-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMTUsMjMsNDIsMC4wMikiLz48L3N2Zz4=')] opacity-50"></div>
        
        <div class="max-w-5xl mx-auto px-6 relative z-10 text-center reveal">
            <h2 class="font-display text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">Predictable pricing.</h2>
            <p class="text-xl text-slate-600 mb-16 max-w-2xl mx-auto font-medium">No hidden fees. No revenue sharing. Just one flat monthly rate for full access to the platform.</p>
            
            <div class="max-w-lg mx-auto bg-white rounded-3xl border border-slate-200 shadow-xl p-10 relative group">
                <!-- Glowing effect behind card -->
                <div class="absolute -inset-1 bg-gradient-to-r from-sky-400 to-blue-500 rounded-3xl blur opacity-10 group-hover:opacity-20 transition duration-1000 -z-10"></div>
                
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Pro License</h3>
                <p class="text-slate-500 mb-6 font-medium">Everything you need to run your ISP</p>
                
                <div class="flex justify-center items-baseline gap-2 mb-8 border-b border-slate-100 pb-8">
                    <span class="text-6xl font-display font-black text-slate-900 tracking-tighter">500</span>
                    <span class="text-slate-500 font-bold">KES / mo</span>
                </div>
                
                <ul class="text-left space-y-4 mb-10 text-slate-700 font-medium">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        Unlimited MikroTik Routers
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        Unlimited Connected Users
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        100% Revenue Retention
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                        Direct M-Pesa STK Integration
                    </li>
                </ul>
                
                <a href="{{ route('register') }}" class="block w-full bg-blue-600 text-white hover:bg-blue-700 px-8 py-4 rounded-xl font-bold text-lg transition-colors shadow-lg shadow-blue-600/20">
                    Start 14-Day Free Trial
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-12 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="font-display font-bold text-lg text-slate-900 tracking-tight">goAfrica Connect</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">
                &copy; {{ date('Y') }} goAfrica Connect. Powering Africa's ISPs.
            </p>
        </div>
    </footer>

    <script>
        // Scroll Reveal Animation (Intersection Observer)
        document.addEventListener('DOMContentLoaded', () => {
            const revealElements = document.querySelectorAll('.reveal:not(.animate-fade-in-up)');
            
            const revealCallback = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                        entry.target.style.opacity = '1';
                        observer.unobserve(entry.target);
                    }
                });
            };
            
            const revealObserver = new IntersectionObserver(revealCallback, {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
            });
            
            revealElements.forEach(el => revealObserver.observe(el));
        });

        // Navbar styling on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 20) {
                nav.classList.add('shadow-sm');
            } else {
                nav.classList.remove('shadow-sm');
            }
        });
    </script>
</body>
</html>
