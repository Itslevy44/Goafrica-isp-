@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="text-center">
        <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full mb-4">
            🚀 Getting Started Wizard
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Set Up Your Hotspot Network</h2>
        <p class="text-sm text-slate-500 mt-1">Follow these 4 steps to get your hotspot live and accepting M-Pesa payments.</p>
    </div>

    {{-- Progress bar --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            @php
            $steps = ['Network', 'Router', 'Plans', 'Go Live'];
            @endphp
            @foreach($steps as $i => $label)
            @php $stepNum = $i + 1; @endphp
            <div class="flex flex-col items-center gap-1 flex-1">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-sm transition-all
                    {{ $currentStep > $stepNum ? 'bg-emerald-500 text-white' :
                      ($currentStep === $stepNum ? 'bg-blue-600 text-white ring-4 ring-blue-100' : 'bg-slate-100 text-slate-400') }}">
                    @if($currentStep > $stepNum)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    @else
                        {{ $stepNum }}
                    @endif
                </div>
                <span class="text-[10px] font-bold {{ $currentStep >= $stepNum ? 'text-slate-700' : 'text-slate-400' }} hidden xs:block">{{ $label }}</span>
            </div>
            @if($i < 3)
            <div class="flex-1 h-0.5 {{ $currentStep > $stepNum ? 'bg-emerald-400' : 'bg-slate-200' }} -mt-4 mx-1"></div>
            @endif
            @endforeach
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3 text-sm font-semibold">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('warning'))
    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-2xl flex items-center gap-3 text-sm font-semibold">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        {{ session('warning') }}
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm font-medium">{{ $errors->first() }}</div>
    @endif

    {{-- ===========================
         STEP 1 — Network Identity
         =========================== --}}
    @if($currentStep === 1)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-blue-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-black text-sm">1</div>
            <div>
                <h3 class="font-bold text-slate-800">Create Your Network</h3>
                <p class="text-xs text-slate-500">Give your hotspot a name and a URL slug.</p>
            </div>
        </div>
        <form action="{{ route('dashboard.setup.network') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Network / Hotspot Name</label>
                <input type="text" name="name" placeholder="e.g. Westlands WiFi, CBD Hotspot" required
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-slate-400 mt-1">This is what customers will see on the captive portal.</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Portal URL Slug</label>
                <div class="flex items-center gap-1">
                    <span class="text-xs text-slate-400 whitespace-nowrap bg-slate-50 border border-slate-200 rounded-l-xl px-3 py-2.5">goafrica.site/connect/</span>
                    <input type="text" name="slug" id="slug-input" placeholder="westlands" required
                           pattern="[a-z0-9\-]+" title="Lowercase letters, numbers, hyphens only"
                           class="flex-1 px-3 py-2.5 border border-slate-200 rounded-r-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 min-w-0">
                </div>
                <p class="text-xs text-slate-400 mt-1">Lowercase, no spaces. Customers use this URL to connect.</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Country / Region</label>
                    <select name="region_id" required class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Select...</option>
                        @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }} ({{ $region->country_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Currency</label>
                    <select name="currency" required class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="KES">KES — Kenyan Shilling</option>
                        <option value="TZS">TZS — Tanzanian Shilling</option>
                        <option value="UGX">UGX — Ugandan Shilling</option>
                        <option value="RWF">RWF — Rwandan Franc</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm shadow-sm transition-colors flex items-center justify-center gap-2">
                Save & Continue
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>
    @endif

    {{-- ===========================
         STEP 2 — Add Router
         =========================== --}}
    @if($currentStep === 2)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-blue-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-black text-sm">2</div>
            <div>
                <h3 class="font-bold text-slate-800">Connect Your MikroTik Router</h3>
                <p class="text-xs text-slate-500">Enter your router's connection details.</p>
            </div>
        </div>

        {{-- Pre-requisite checklist --}}
        <div class="mx-6 mt-5 bg-amber-50 border border-amber-200 rounded-xl p-4">
            <p class="text-xs font-bold text-amber-800 mb-2">Before you continue, make sure you have:</p>
            <ul class="space-y-1.5 text-xs text-amber-700">
                <li class="flex items-center gap-2"><span class="w-4 h-4 rounded-full border border-amber-400 flex items-center justify-center text-[9px] font-bold flex-shrink-0">1</span> Enabled the API service on port 8728 in Winbox (IP > Services > api)</li>
                <li class="flex items-center gap-2"><span class="w-4 h-4 rounded-full border border-amber-400 flex items-center justify-center text-[9px] font-bold flex-shrink-0">2</span> Created a <code>billing_api</code> user with write permissions (System > Users)</li>
                <li class="flex items-center gap-2"><span class="w-4 h-4 rounded-full border border-amber-400 flex items-center justify-center text-[9px] font-bold flex-shrink-0">3</span> Set up the Hotspot on your router (IP > Hotspot > Hotspot Setup)</li>
                <li class="flex items-center gap-2"><span class="w-4 h-4 rounded-full border border-amber-400 flex items-center justify-center text-[9px] font-bold flex-shrink-0">4</span> Your router is accessible on a public IP (or port-forwarded)</li>
            </ul>
            <a href="{{ route('dashboard.docs') }}" target="_blank" class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-amber-700 hover:text-amber-900 underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                View full setup guide →
            </a>
        </div>

        <form action="{{ route('dashboard.setup.router') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Router Name</label>
                <input type="text" name="name" placeholder="e.g. Main Router, Branch 1" required
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Router Public IP</label>
                    <input type="text" name="ip_address" placeholder="e.g. 41.72.145.200" required
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-slate-400 mt-1">Must be reachable from the internet.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">API Port</label>
                    <input type="number" name="api_port" value="8728" required
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-slate-400 mt-1">Default is 8728.</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">API Username</label>
                    <input type="text" name="username" placeholder="billing_api" required
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">API Password</label>
                    <input type="password" name="password" placeholder="••••••••" required
                           class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm shadow-sm transition-colors flex items-center justify-center gap-2">
                Connect & Test Router
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>
    @endif

    {{-- ===========================
         STEP 3 — Internet Plans
         =========================== --}}
    @if($currentStep === 3)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-blue-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-black text-sm">3</div>
            <div>
                <h3 class="font-bold text-slate-800">Create Internet Plans</h3>
                <p class="text-xs text-slate-500">These are the packages customers will buy on your portal.</p>
            </div>
        </div>
        <div class="p-6">
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-5">
                <p class="text-sm font-bold text-emerald-800 mb-1">✓ We'll create 4 starter plans for you:</p>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    @foreach([['1 Hour','60 mins','KES 50'],['Daily','24 hours','KES 100'],['Weekly','7 days','KES 500'],['Monthly','30 days','KES 1,500']] as $p)
                    <div class="bg-white rounded-lg px-3 py-2 border border-emerald-100 text-xs">
                        <span class="font-bold text-slate-800">{{ $p[0] }}</span>
                        <span class="text-slate-400 mx-1">·</span>
                        <span class="text-slate-500">{{ $p[1] }}</span>
                        <span class="text-emerald-600 font-bold ml-1">{{ $p[2] }}</span>
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-slate-500 mt-2">You can edit or add more plans later from the Internet Plans page.</p>
            </div>
            <form action="{{ route('dashboard.setup.offers') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm shadow-sm transition-colors flex items-center justify-center gap-2">
                    Create Starter Plans & Continue
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- ===========================
         STEP 4 — Go Live!
         =========================== --}}
    @if($currentStep === 4)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-5 text-center">
            <div class="text-4xl mb-2">🎉</div>
            <h3 class="font-black text-white text-xl">You're All Set!</h3>
            <p class="text-emerald-100 text-sm mt-1">Your hotspot network is live and ready to accept M-Pesa payments.</p>
        </div>
        <div class="p-6 space-y-4">

            {{-- Portal URL --}}
            @if($network)
            <div class="bg-slate-50 rounded-xl border border-slate-200 p-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Your Captive Portal URL</p>
                <div class="flex items-center gap-2">
                    <code class="text-sm font-mono text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 flex-1 truncate">
                        {{ url('/connect/' . $network->slug) }}
                    </code>
                    <button onclick="navigator.clipboard.writeText('{{ url('/connect/' . $network->slug) }}').then(()=>this.textContent='✓')"
                            class="text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg transition-colors">
                        Copy
                    </button>
                </div>
                <p class="text-xs text-slate-400 mt-2">Share this link — or set it as your MikroTik hotspot login page URL — so customers can pay and connect.</p>
            </div>
            @endif

            {{-- Next steps --}}
            <div class="space-y-2">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Next Steps</p>
                <a href="{{ route('dashboard.settings.index') }}" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-blue-50 rounded-xl border border-slate-200 hover:border-blue-200 transition-colors group">
                    <div class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Add M-Pesa Credentials</p>
                        <p class="text-xs text-slate-500">Connect your Safaricom Daraja API to start collecting payments</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('dashboard.wallet.index') }}" class="flex items-center gap-3 p-3 bg-slate-50 hover:bg-blue-50 rounded-xl border border-slate-200 hover:border-blue-200 transition-colors group">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Set Up Payout Account</p>
                        <p class="text-xs text-slate-500">Enter where you want your earnings sent</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <form action="{{ route('dashboard.setup.complete') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold py-3 rounded-xl text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    Go to Dashboard
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Skip wizard link --}}
    <p class="text-center text-xs text-slate-400">
        Already set up? <a href="{{ route('dashboard.index') }}" class="text-blue-600 hover:underline">Skip wizard and go to dashboard →</a>
    </p>

</div>

<script>
// Auto-generate slug from network name
const nameInput = document.querySelector('input[name="name"]');
const slugInput = document.getElementById('slug-input');
if (nameInput && slugInput) {
    nameInput.addEventListener('input', function() {
        slugInput.value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .substring(0, 50);
    });
}
</script>
@endsection
