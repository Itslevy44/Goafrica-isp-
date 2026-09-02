<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            900: '#0c4a6e',
                        },
                        mpesa: '#46b245',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.4s ease-out',
                        'slide-up': 'slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1)',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc;
            background-image: radial-gradient(at 40% 20%, #e0f2fe 0px, transparent 50%),
                              radial-gradient(at 80% 0%, #f0fdfa 0px, transparent 50%),
                              radial-gradient(at 0% 50%, #eff6ff 0px, transparent 50%);
            background-attachment: fixed;
            -webkit-tap-highlight-color: transparent;
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.05),
                0 10px 15px -3px rgba(0, 0, 0, 0.03),
                inset 0 0 0 1px rgba(255, 255, 255, 0.5);
        }

        .package-radio:checked + div {
            border-color: #0ea5e9;
            background-color: #f0f9ff;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15);
            transform: translateY(-2px);
        }
        
        .package-radio:checked + div .check-icon {
            opacity: 1;
            transform: scale(1);
        }
        
        .package-card {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-focus-ring:focus-within {
            box-shadow: 0 0 0 2px #bae6fd;
            border-color: #0ea5e9;
        }

        .tab-btn.active {
            color: #0f172a;
            border-bottom-color: #0f172a;
        }
        
        .tab-btn:not(.active) {
            color: #64748b;
            border-bottom-color: transparent;
        }
        
        .tab-btn:not(.active):hover {
            color: #334155;
            border-bottom-color: #cbd5e1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 text-slate-900 font-sans">
    
    <div class="w-full max-w-md animate-slide-up relative z-10">
        @yield('content')
    </div>
    
    <!-- Abstract background shapes -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-400/10 blur-3xl animate-pulse-slow"></div>
        <div class="absolute top-[60%] -right-[10%] w-[60%] h-[60%] rounded-full bg-emerald-400/10 blur-3xl animate-pulse-slow" style="animation-delay: 1s"></div>
    </div>

</body>
</html>
