@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-16 space-y-8">

    {{-- Header --}}
    <div class="text-center">
        <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full mb-4">
            📖 MikroTik Setup Guide
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-3">Beginner's Integration Guide</h1>
        <p class="text-slate-500 max-w-2xl mx-auto">Step-by-step instructions to connect your MikroTik router to goAfrica Connect. This guide covers RouterOS — the operating system that runs on all MikroTik devices.</p>

        {{-- Quick links --}}
        <div class="flex flex-wrap justify-center gap-2 mt-5">
            <a href="#topology" class="text-xs font-bold bg-slate-800 text-white px-3 py-1.5 rounded-full hover:bg-slate-700 transition-colors">🗺 Network Diagram</a>
            <a href="#quick-script" class="text-xs font-bold bg-emerald-600 text-white px-3 py-1.5 rounded-full hover:bg-emerald-700 transition-colors">⚡ Quick Auto-Script</a>
            <a href="#ddns" class="text-xs font-bold bg-blue-600 text-white px-3 py-1.5 rounded-full hover:bg-blue-700 transition-colors">🌐 Get Your IP (DDNS)</a>
            <a href="#walled-garden" class="text-xs font-bold bg-white border border-slate-200 text-slate-600 px-3 py-1.5 rounded-full hover:bg-slate-50 transition-colors">Walled Garden</a>
            <a href="#troubleshooting" class="text-xs font-bold bg-amber-100 border border-amber-200 text-amber-700 px-3 py-1.5 rounded-full hover:bg-amber-200 transition-colors">🔧 Troubleshooting</a>
        </div>
    </div>

    {{-- ============================================================
         NETWORK TOPOLOGY DIAGRAM
         ============================================================ --}}
    <div id="topology" class="bg-slate-900 rounded-2xl p-6 text-white shadow-xl">
        <h2 class="text-lg font-black mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            Recommended Network Setup (Topology)
        </h2>

        {{-- ASCII/visual topology --}}
        <div class="bg-black/40 rounded-xl p-5 font-mono text-sm mb-5 overflow-x-auto">
            <pre class="text-slate-300 leading-relaxed">
  ┌─────────────────────────────────────────┐
  │  INTERNET (Safaricom / Any ISP)         │
  └──────────────┬──────────────────────────┘
                 │
  ┌──────────────▼──────────────────────────┐
  │  HOME ROUTER  (e.g. Safaricom LTE box)  │
  │  192.168.1.1                            │
  └──────────────┬──────────────────────────┘
                 │  LAN port → Ethernet cable
                 │
  ┌──────────────▼──────────────────────────┐
  │  <span class="text-blue-400 font-bold">MIKROTIK ROUTER</span>  ← <span class="text-emerald-400">THIS IS YOUR MAIN DEVICE</span>  │
  │  ether1 = WAN (connects to home router) │
  │  ether2-5 = LAN (connects to switch)    │
  │  Runs: Hotspot + goAfrica Billing       │
  │  Cloud DDNS: abc123.sn.mynetname.net    │
  └──────────────┬──────────────────────────┘
                 │  ether2 → Ethernet cable
                 │
  ┌──────────────▼──────────────────────────┐
  │  NETWORK SWITCH  (8-port or 16-port)    │
  └──────┬──────────┬──────────┬────────────┘
         │          │          │
  ┌──────▼───┐ ┌────▼─────┐ ┌──▼───────┐
  │  WiFi AP │ │  WiFi AP │ │  WiFi AP │  ← Set as ACCESS POINT
  │  Zone 1  │ │  Zone 2  │ │  Zone 3  │     (NOT as router!)
  └──────────┘ └──────────┘ └──────────┘
         ↑
  Customers connect to any AP,
  all managed by one MikroTik hotspot
            </pre>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div class="bg-emerald-500/20 border border-emerald-500/30 rounded-xl p-4">
                <p class="text-emerald-300 font-bold text-sm mb-2">✅ Access Points — Configure as Bridge</p>
                <p class="text-slate-300 text-xs leading-relaxed">Your extension routers/APs must be in <strong>bridge/AP mode</strong> — disable their DHCP server and NAT. Connect their <strong>LAN port</strong> (not WAN) to the switch. The MikroTik sees all customers directly.</p>
            </div>
            <div class="bg-red-500/20 border border-red-500/30 rounded-xl p-4">
                <p class="text-red-300 font-bold text-sm mb-2">❌ Don't Set APs as Routers</p>
                <p class="text-slate-300 text-xs leading-relaxed">If the APs do NAT routing, the MikroTik will only see the AP's IP — not individual customer MAC addresses. Billing won't work correctly and customers won't be identified.</p>
            </div>
        </div>
    </div>

    {{-- ============================================================
         DDNS — GET YOUR PUBLIC HOSTNAME
         ============================================================ --}}
    <div id="ddns" class="bg-gradient-to-br from-blue-700 to-indigo-700 rounded-2xl p-6 text-white shadow-xl">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 text-2xl">🌐</div>
            <div class="flex-1">
                <h2 class="text-xl font-black mb-1">Step 0 — Get Your Router's Public Hostname (MikroTik Cloud DDNS)</h2>
                <p class="text-blue-100 text-sm mb-4">
                    Your MikroTik is behind your home router and doesn't have a public IP directly. Instead of struggling with port forwarding,
                    <strong>MikroTik Cloud</strong> gives your router a free permanent hostname that always works — even when your IP changes.
                </p>

                <div class="bg-black/30 rounded-xl p-4 mb-4">
                    <p class="text-blue-300 text-xs font-bold uppercase tracking-wider mb-2">Run this in Winbox → New Terminal</p>
                    <pre class="text-green-300 text-sm font-mono">/ip cloud set ddns-enabled=yes
/ip cloud print</pre>
                </div>

                <div class="bg-black/20 rounded-xl p-4 mb-4">
                    <p class="text-blue-200 text-xs font-bold uppercase tracking-wider mb-2">You'll see output like this:</p>
                    <pre class="text-yellow-300 text-sm font-mono">    ddns-enabled: yes
        dns-name: <span class="text-white font-bold">a1b2c3d4.sn.mynetname.net</span>  ← USE THIS!
  update-time: yes
  public-address: 41.72.145.200</pre>
                </div>

                <div class="bg-emerald-500/20 border border-emerald-400/30 rounded-xl p-4">
                    <p class="text-emerald-200 font-bold text-sm mb-1">✅ Use the <code class="bg-black/30 px-1.5 py-0.5 rounded">dns-name</code> value in your goAfrica dashboard</p>
                    <p class="text-emerald-100 text-xs">When adding your router in the dashboard, paste the <code>dns-name</code> (e.g. <code>a1b2c3d4.sn.mynetname.net</code>) into the IP Address field instead of a number. It will always work even if your internet IP changes.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         QUICK AUTO-SCRIPT
         ============================================================ --}}
    <div id="quick-script" class="bg-gradient-to-br from-emerald-600 to-teal-600 rounded-2xl p-6 text-white shadow-xl">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 text-2xl">⚡</div>
            <div class="flex-1">
                <h2 class="text-xl font-black mb-1">Full Auto-Configuration Script</h2>
                <p class="text-emerald-100 text-sm mb-4">
                    This script does <strong>everything</strong> — sets up the WAN/LAN bridge, DHCP, NAT, hotspot, walled garden, API user, and enables MikroTik Cloud DDNS so you get a free public hostname automatically.
                    <strong>Just run it and follow the output.</strong>
                </p>

                <div class="bg-black/30 rounded-xl p-4 mb-4">
                    <p class="text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2">Quick Paste — Run in Winbox → New Terminal</p>
                    <pre class="text-green-300 text-xs font-mono leading-relaxed overflow-x-auto whitespace-pre"># Enable API + create billing user
/ip service enable api
/ip service set api port=8728
/user add name=billing_api password=GoAfrica2024! group=full comment="goAfrica billing"

# Walled Garden
/ip hotspot walled-garden add dst-host=*.safaricom.co.ke action=allow
/ip hotspot walled-garden add dst-host=goafrica.site action=allow
/ip hotspot walled-garden add dst-host=*.goafrica.site action=allow

# Enable Cloud DDNS (get your free public hostname)
/ip cloud set ddns-enabled=yes
/ip cloud print</pre>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('dashboard.docs.script') }}" download="goafrica-full-setup.rsc"
                       class="inline-flex items-center gap-2 bg-white text-emerald-700 font-bold px-4 py-2.5 rounded-xl text-sm hover:bg-emerald-50 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download Full Script (.rsc)
                    </a>
                    <div class="bg-white/20 text-white/80 text-xs px-3 py-2 rounded-xl flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Import: Winbox → Files → Upload .rsc → then type: /import file-name=goafrica-full-setup.rsc
                    </div>
                </div>

                <div class="mt-4 bg-yellow-500/20 border border-yellow-400/30 rounded-xl p-3">
                    <p class="text-yellow-200 text-xs font-bold">⚠️ After running: Change the billing_api password!</p>
                    <p class="text-yellow-100 text-xs mt-0.5">Go to System → Users → billing_api → set a unique password, then update it in your goAfrica dashboard.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         WHAT YOU NEED
         ============================================================ --}}
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
        <h2 class="font-black text-blue-900 text-lg mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.956 11.956 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            What You Need Before Starting
        </h2>
        <div class="grid sm:grid-cols-2 gap-3">
            @foreach([
                ['✓', 'A MikroTik router (any model running RouterOS 6.x or 7.x)', 'emerald'],
                ['✓', 'Winbox installed on your computer (free from mikrotik.com)', 'emerald'],
                ['✓', 'Access to your router with admin credentials', 'emerald'],
                ['✓', 'A public IP address (or port forwarding set up)', 'emerald'],
                ['✓', 'M-Pesa Daraja API credentials from Safaricom', 'emerald'],
                ['ℹ', 'RouterOS 7+ is recommended for best compatibility', 'blue'],
            ] as [$icon, $text, $color])
            <div class="flex items-start gap-2.5 bg-white rounded-xl p-3 border border-blue-100">
                <span class="text-{{ $color }}-600 font-black text-sm flex-shrink-0">{{ $icon }}</span>
                <p class="text-sm text-slate-700">{{ $text }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ============================================================
         STEP 1 — Winbox Login
         ============================================================ --}}
    <div id="step1" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0">1</div>
                <div>
                    <h2 class="text-xl font-black text-slate-800">Log into Winbox</h2>
                    <p class="text-sm text-slate-500">Connect to your MikroTik router using Winbox software.</p>
                </div>
            </div>
            <div class="space-y-3 text-sm text-slate-600 leading-relaxed">
                <p>Download and open <strong>Winbox</strong> (free from <a href="https://mikrotik.com/download" target="_blank" class="text-blue-600 underline">mikrotik.com/download</a>). You can connect using your router's <strong>MAC address</strong> (easier, works on local network) or <strong>IP address</strong>.</p>
                <ol class="list-decimal list-inside space-y-2 text-slate-700 ml-2">
                    <li>Click the <strong>...</strong> button next to "Connect To" to browse for your router</li>
                    <li>Select your router from the list by clicking its MAC address</li>
                    <li>Enter username <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs">admin</code> and leave password blank (default), or use your existing password</li>
                    <li>Click <strong>Connect</strong></li>
                </ol>
            </div>
            <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm">
                <p class="text-amber-800 font-semibold">💡 First time logging in?</p>
                <p class="text-amber-700 mt-1">Default MikroTik credentials are username <code>admin</code> with an empty password. You should change this immediately via System > Users.</p>
            </div>
        </div>
    </div>

    {{-- ============================================================
         STEP 2 — Enable API
         ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0">2</div>
                <div>
                    <h2 class="text-xl font-black text-slate-800">Enable the API Service</h2>
                    <p class="text-sm text-slate-500">Allows the billing system to connect to your router.</p>
                </div>
            </div>
            <div class="text-sm text-slate-600 leading-relaxed space-y-3 mb-5">
                <p>The billing system communicates with your router via the <strong>RouterOS API</strong> on port 8728 to automatically grant and revoke internet access when customers pay.</p>
                <ol class="list-decimal list-inside space-y-2 text-slate-700 ml-2">
                    <li>In Winbox, click <strong>IP</strong> in the left menu</li>
                    <li>Click <strong>Services</strong></li>
                    <li>Find the row named <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs">api</code></li>
                    <li>Double-click it and make sure it is <strong>enabled</strong> (unchecked "Disabled")</li>
                    <li>Set Port to <strong>8728</strong> — click <strong>Apply → OK</strong></li>
                </ol>
            </div>
            <div class="bg-slate-900 rounded-xl p-4">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Terminal Alternative</p>
                <code class="text-green-400 text-xs font-mono">/ip service enable api<br>/ip service set api port=8728</code>
            </div>
        </div>
    </div>

    {{-- ============================================================
         STEP 3 — Create API User
         ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0">3</div>
                <div>
                    <h2 class="text-xl font-black text-slate-800">Create a Dedicated API User</h2>
                    <p class="text-sm text-slate-500">A separate login for the billing system — never use your admin password.</p>
                </div>
            </div>
            <div class="text-sm text-slate-600 leading-relaxed space-y-3 mb-5">
                <ol class="list-decimal list-inside space-y-2 text-slate-700 ml-2">
                    <li>Go to <strong>System → Users</strong></li>
                    <li>Click the <strong>+</strong> (plus) button</li>
                    <li>Name: <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs">billing_api</code></li>
                    <li>Group: Select <strong>full</strong> (or <strong>write</strong> if you want less permissions)</li>
                    <li>Password: Type a strong password and confirm it</li>
                    <li>Click <strong>Apply → OK</strong></li>
                </ol>
            </div>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
                <p class="text-blue-800 font-semibold text-sm">📝 Note this down!</p>
                <p class="text-blue-700 text-sm mt-1">You will enter this username and password into the dashboard when adding your router in Step 6.</p>
            </div>
            <div class="mt-4 bg-slate-900 rounded-xl p-4">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Terminal Alternative</p>
                <code class="text-green-400 text-xs font-mono">/user add name=billing_api password=YourPassword group=full</code>
            </div>
        </div>
    </div>

    {{-- ============================================================
         STEP 4 — Hotspot Setup
         ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0">4</div>
                <div>
                    <h2 class="text-xl font-black text-slate-800">Set Up the Hotspot</h2>
                    <p class="text-sm text-slate-500">Create the captive portal that customers will see when they connect.</p>
                </div>
            </div>
            <div class="text-sm text-slate-600 leading-relaxed space-y-3 mb-5">
                <p>The <strong>Hotspot</strong> is what intercepts customers when they join your WiFi and redirects them to your payment page.</p>
                <ol class="list-decimal list-inside space-y-2 text-slate-700 ml-2">
                    <li>Go to <strong>IP → Hotspot</strong></li>
                    <li>Click the <strong>Hotspot Setup</strong> button</li>
                    <li><strong>Hotspot Interface:</strong> Select the interface your customers connect to (usually <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs">bridge</code> or <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs">wlan1</code>)</li>
                    <li><strong>IP Address:</strong> Leave as default (usually 192.168.88.1/24)</li>
                    <li><strong>Address Pool:</strong> Click Next</li>
                    <li><strong>HTTPS Certificate:</strong> Select "none" and click Next</li>
                    <li><strong>DNS Name:</strong> Type something like <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs">wifi.local</code> or leave blank</li>
                    <li>Click <strong>Next → Finish</strong></li>
                </ol>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm">
                <p class="font-semibold text-amber-800 mb-1">⚠️ Not sure which interface to pick?</p>
                <p class="text-amber-700">If customers connect via WiFi, use <code>wlan1</code>. If they connect via cable or a switch connected to the router, use <code>bridge</code> or <code>ether2</code>. When in doubt, use <code>bridge</code>.</p>
            </div>
        </div>
    </div>

    {{-- ============================================================
         STEP 5 — Walled Garden
         ============================================================ --}}
    <div id="walled-garden" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0">5</div>
                <div>
                    <h2 class="text-xl font-black text-slate-800">Configure Walled Garden</h2>
                    <p class="text-sm text-slate-500">Allow customers to reach the payment page before they've paid.</p>
                </div>
            </div>
            <div class="text-sm text-slate-600 leading-relaxed space-y-3 mb-5">
                <p>Without the Walled Garden, customers can't reach the M-Pesa payment page because they're blocked by the hotspot. The Walled Garden allows specific sites through before payment.</p>
                <p class="font-semibold text-slate-700">Add these entries in IP → Hotspot → Walled Garden tab:</p>
                <div class="space-y-2">
                    @foreach([
                        ['*.safaricom.co.ke', 'Allow M-Pesa STK push to reach the customer'],
                        ['goafrica.site', 'Allow the payment portal to load'],
                        ['*.goafrica.site', 'Allow all goAfrica subdomains'],
                        ['fonts.googleapis.com', 'Allow portal fonts to load (optional)'],
                    ] as [$host, $desc])
                    <div class="flex items-start gap-3 bg-slate-50 rounded-lg px-3 py-2.5 border border-slate-100">
                        <code class="text-xs font-mono text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100 flex-shrink-0">{{ $host }}</code>
                        <span class="text-xs text-slate-500">{{ $desc }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-slate-900 rounded-xl p-4">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Terminal — Copy & Paste All at Once</p>
                <code class="text-green-400 text-xs font-mono block">
/ip hotspot walled-garden<br>
add dst-host=*.safaricom.co.ke action=allow<br>
add dst-host=goafrica.site action=allow<br>
add dst-host=*.goafrica.site action=allow<br>
add dst-host=fonts.googleapis.com action=allow
                </code>
            </div>
        </div>
    </div>

    {{-- ============================================================
         STEP 5b — Access Point Configuration
         ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 bg-purple-500 text-white rounded-full flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0">5b</div>
                <div>
                    <h2 class="text-xl font-black text-slate-800">Configure Extension Access Points</h2>
                    <p class="text-sm text-slate-500">If you're using other routers/APs to extend WiFi range via the switch.</p>
                </div>
            </div>
            <div class="text-sm text-slate-600 leading-relaxed space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="font-bold text-blue-900 mb-1">The key rule: Extension APs must be in Bridge/AP mode — NOT router mode.</p>
                    <p class="text-blue-800">If an AP does its own NAT/routing, the MikroTik can't see individual customer MACs — billing breaks. The MikroTik must be the only device doing routing.</p>
                </div>

                <div>
                    <p class="font-semibold text-slate-700 mb-2">For each TP-Link / Tenda / Netgear AP:</p>
                    <ol class="list-decimal list-inside space-y-1.5 ml-2">
                        <li>Log into the AP admin panel (usually 192.168.0.1)</li>
                        <li>Find a setting called <strong>"Operation Mode"</strong> or <strong>"Working Mode"</strong></li>
                        <li>Select <strong>"Access Point"</strong> or <strong>"Bridge"</strong> mode (not "Router" or "WISP")</li>
                        <li>Disable the AP's DHCP server if there is one</li>
                        <li>Connect an Ethernet cable from the AP's <strong>LAN port</strong> to your switch</li>
                        <li>Do <strong>NOT</strong> use the AP's WAN port</li>
                    </ol>
                </div>

                <div>
                    <p class="font-semibold text-slate-700 mb-2">For another MikroTik as an AP:</p>
                    <div class="bg-slate-900 rounded-xl p-4">
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Run on the secondary MikroTik</p>
                        <pre class="text-green-400 text-xs font-mono"># Disable DHCP server
/ip dhcp-server disable [find]

# Disable routing/NAT
/ip firewall nat disable [find]

# Bridge all ports
/interface bridge add name=bridge1
/interface bridge port add interface=ether1 bridge=bridge1
/interface bridge port add interface=ether2 bridge=bridge1
/interface bridge port add interface=wlan1 bridge=bridge1

# Set a management IP (optional, use a different subnet)
/ip address add address=192.168.88.2/24 interface=bridge1</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         STEP 6 — Add to Dashboard
         ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden border-t-4 border-t-emerald-500">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0">6</div>
                <div>
                    <h2 class="text-xl font-black text-slate-800">Add Router to Dashboard</h2>
                    <p class="text-sm text-slate-500">Connect your configured router to the billing system.</p>
                </div>
            </div>
            <div class="text-sm text-slate-600 leading-relaxed space-y-3 mb-6">
                <p>You're done with Winbox! Now link the router to goAfrica Connect.</p>
                <ol class="list-decimal list-inside space-y-2 text-slate-700 ml-2">
                    <li>Go to <a href="{{ route('dashboard.devices.index') }}" class="text-blue-600 hover:underline font-medium">Routers & Devices</a> in the sidebar</li>
                    <li>Click <strong>Add Router</strong></li>
                    <li>Enter your router's <strong>public IP address</strong> (or your local IP if on the same network)</li>
                    <li>Port: <strong>8728</strong></li>
                    <li>Username: <code>billing_api</code> (the user you created in Step 3)</li>
                    <li>Password: the password you set</li>
                    <li>Click <strong>Connect & Test</strong> — the system will verify the connection immediately</li>
                </ol>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard.devices.index') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    Go to Routers Page
                </a>
                <a href="{{ route('dashboard.setup.index') }}" class="inline-flex items-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold py-2.5 px-5 rounded-xl text-sm border border-blue-200 transition-colors">
                    🚀 Use Setup Wizard
                </a>
            </div>
        </div>
    </div>

    {{-- ============================================================
         TROUBLESHOOTING
         ============================================================ --}}
    <div id="troubleshooting" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-amber-50">
            <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                🔧 Troubleshooting — Common Problems & Fixes
            </h2>
        </div>
        <div class="divide-y divide-slate-100">
            @php
            $issues = [
                [
                    'q' => 'Connection failed: Unable to connect to device',
                    'a' => 'The most common cause is port 8728 is not reachable. Check: (1) Is the API service enabled in Winbox → IP → Services → api? (2) Is the IP address correct? (3) If behind NAT, have you set up port forwarding on port 8728? (4) Is there a firewall rule blocking the port? Run this in MikroTik terminal: /ip firewall filter print — look for any DROP rules on port 8728.',
                    'cmd' => '/ip service set api disabled=no port=8728',
                ],
                [
                    'q' => 'Error: Authentication failed',
                    'a' => 'The username or password you entered in the dashboard doesn\'t match what\'s on the router. Go to System → Users in Winbox and verify the billing_api user exists. Try resetting the password: System → Users → select billing_api → set a new password → update it in the dashboard.',
                    'cmd' => '/user set billing_api password=NewPassword123',
                ],
                [
                    'q' => 'Customers can\'t reach the payment page after connecting to WiFi',
                    'a' => 'Your Walled Garden is missing entries. Go to IP → Hotspot → Walled Garden and add: *.safaricom.co.ke, goafrica.site, *.goafrica.site. Without these, customers are blocked from reaching the payment portal before they\'ve paid.',
                    'cmd' => '/ip hotspot walled-garden add dst-host=*.safaricom.co.ke action=allow',
                ],
                [
                    'q' => 'M-Pesa STK push is not being sent',
                    'a' => 'This is an M-Pesa Daraja API issue, not a router issue. Check: (1) Are your Consumer Key and Consumer Secret correct in Wallet & Payouts? (2) Are you using the correct environment (sandbox vs production)? (3) Is your Safaricom shortcode correct? (4) Is your callback URL accessible from the internet?',
                    'cmd' => null,
                ],
                [
                    'q' => 'Customer paid but didn\'t get internet access',
                    'a' => 'Check the Active Sessions page in your dashboard. If the session was created but the router didn\'t grant access, it means the router connection worked but createHotspotUser failed. Check: (1) Is the hotspot running on the router? (2) Does the API user have "write" or "full" permissions? Run: /ip hotspot print — it should show your hotspot.',
                    'cmd' => '/ip hotspot print',
                ],
                [
                    'q' => 'My router\'s IP keeps changing (dynamic IP)',
                    'a' => 'Your ISP is giving you a new public IP address each time the router restarts. Solution: (1) Ask your ISP for a static IP (best option). (2) Set up Dynamic DNS (DDNS) — MikroTik supports this via IP → Cloud which gives you a free hostname like abc123.sn.mynetname.net. Use that hostname instead of an IP in the dashboard.',
                    'cmd' => '/ip cloud set ddns-enabled=yes',
                ],
                [
                    'q' => 'The router shows as "offline" in the dashboard',
                    'a' => 'This means the router health check command failed. Click the "Test" button on the Routers page to see the exact error. Common causes: router restarted, IP changed, port blocked. If you use a dynamic IP, consider setting up MikroTik Cloud DDNS.',
                    'cmd' => null,
                ],
            ];
            @endphp

            @foreach($issues as $i => $issue)
            <div x-data="{ open: false }" class="px-6 py-4">
                <button @click="open = !open" class="w-full flex items-start gap-3 text-left">
                    <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-black text-xs flex-shrink-0 mt-0.5">{{ $i+1 }}</span>
                    <span class="font-bold text-slate-800 text-sm flex-1">{{ $issue['q'] }}</span>
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-1 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition class="mt-3 ml-9 space-y-3">
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $issue['a'] }}</p>
                    @if($issue['cmd'])
                    <div class="bg-slate-900 rounded-lg px-3 py-2">
                        <code class="text-green-400 text-xs font-mono">{{ $issue['cmd'] }}</code>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Need more help --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white text-center">
        <h3 class="font-black text-lg mb-2">Still stuck? We're here to help.</h3>
        <p class="text-blue-100 text-sm mb-4">Contact us directly and we'll walk you through the setup step by step.</p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="tel:+254748717099" class="inline-flex items-center gap-2 bg-white text-blue-700 font-bold px-4 py-2.5 rounded-xl text-sm hover:bg-blue-50 transition-colors">
                📞 +254 748 717 099
            </a>
            <a href="{{ route('landing') }}#contact" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition-colors border border-white/20">
                💬 Submit a Support Ticket
            </a>
        </div>
    </div>

</div>
@endsection
