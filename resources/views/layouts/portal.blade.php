<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>{{ $network->name ?? 'Connect to WiFi' }}</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'xs':  '320px',
                        'sm':  '480px',
                        'md':  '768px',
                        'lg':  '1024px',
                        'xl':  '1280px',
                        '2xl': '1536px',
                        '3xl': '1920px',
                        '4xl': '2560px',
                    },
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#f0f9ff', 100:'#e0f2fe', 500:'#0ea5e9', 600:'#0284c7', 900:'#0c4a6e' },
                        mpesa: '#46b245',
                    },
                    animation: {
                        'fade-in':    'fadeIn 0.4s ease-out',
                        'slide-up':   'slideUp 0.5s cubic-bezier(0.16,1,0.3,1)',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4,0,0.6,1) infinite',
                    },
                    keyframes: {
                        fadeIn:  { '0%':{ opacity:'0' },                                       '100%':{ opacity:'1' } },
                        slideUp: { '0%':{ transform:'translateY(20px)', opacity:'0' },         '100%':{ transform:'translateY(0)', opacity:'1' } },
                    }
                }
            }
        }
    </script>
    <style>
        /* ── Base ── */
        html { font-size: clamp(13px, 1.8vw, 18px); }
        body {
            background-color: #f8fafc;
            background-image:
                radial-gradient(at 40% 20%, #e0f2fe 0px, transparent 50%),
                radial-gradient(at 80% 0%,  #f0fdfa 0px, transparent 50%),
                radial-gradient(at 0%  50%, #eff6ff 0px, transparent 50%);
            background-attachment: fixed;
            -webkit-tap-highlight-color: transparent;
        }

        /* ── Glass panel ── */
        .glass-panel {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.05),
                        0 10px 15px -3px rgba(0,0,0,.03),
                        inset 0 0 0 1px rgba(255,255,255,.5);
        }

        /* ── Package cards ── */
        .package-radio:checked + div {
            border-color: #0ea5e9;
            background-color: #f0f9ff;
            box-shadow: 0 4px 12px rgba(14,165,233,.15);
            transform: translateY(-2px);
        }
        .package-radio:checked + div .check-icon { opacity:1; transform:scale(1); }
        .package-card { transition: all .25s cubic-bezier(.4,0,.2,1); }

        /* ── Input focus ring ── */
        .input-focus-ring:focus-within { box-shadow: 0 0 0 2px #bae6fd; border-color: #0ea5e9; }

        /* ── Tabs ── */
        .tab-btn.active        { color:#0f172a; border-bottom-color:#0f172a; }
        .tab-btn:not(.active)  { color:#64748b; border-bottom-color:transparent; }
        .tab-btn:not(.active):hover { color:#334155; border-bottom-color:#cbd5e1; }

        /* ── Smartwatch (<200px) ── */
        @media (max-width: 200px) {
            .watch-hide { display: none !important; }
            .glass-panel { border-radius: 0.75rem !important; padding: 0.5rem !important; }
            .tab-btn svg { display: none; }
            .tab-btn { font-size: 0.6rem; padding: 0.4rem 0; }
            input, select { font-size: 0.7rem !important; padding: 0.4rem !important; }
            button[type=submit] { padding: 0.5rem !important; font-size: 0.65rem !important; }
            h1 { font-size: 0.9rem !important; }
        }

        /* ── TV / 4K (>1920px) ── */
        @media (min-width: 1920px) {
            html { font-size: clamp(18px, 1.1vw, 24px); }
        }
    </style>
</head>
<body class="min-h-screen flex items-start justify-center
             py-3 px-2
             xs:py-4 xs:px-3
             sm:py-6 sm:px-4
             md:py-8 md:px-6
             lg:items-center
             3xl:py-16
             text-slate-900 font-sans">

    {{-- Outer wrapper — constrains width across all screens --}}
    <div class="w-full
                max-w-[260px]
                xs:max-w-sm
                sm:max-w-md
                lg:max-w-lg
                2xl:max-w-xl
                3xl:max-w-2xl
                animate-slide-up relative z-10">
        @yield('content')
    </div>

    {{-- Decorative background blobs — hidden on tiny watches --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0 watch-hide">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-400/10 blur-3xl animate-pulse-slow"></div>
        <div class="absolute top-[60%] -right-[10%] w-[60%] h-[60%] rounded-full bg-emerald-400/10 blur-3xl animate-pulse-slow" style="animation-delay:1s"></div>
    </div>

</body>
</html>
