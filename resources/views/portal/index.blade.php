@extends('layouts.portal')

@section('content')

{{-- =====================================================================
     CAPTIVE PORTAL — Mobile-first, clean, conversion-optimised
     ===================================================================== --}}

<div class="space-y-4">

    {{-- ---- HEADER CARD ---- --}}
    <div class="glass-panel rounded-2xl xs:rounded-3xl px-4 xs:px-6 pt-5 xs:pt-7 pb-4 xs:pb-5 text-center">
        <div class="mx-auto w-12 h-12 xs:w-16 xs:h-16 bg-gradient-to-br from-blue-600 to-sky-400 rounded-xl xs:rounded-2xl shadow-lg shadow-blue-500/25 flex items-center justify-center mb-3 xs:mb-4">
            <svg class="w-6 h-6 xs:w-8 xs:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
            </svg>
        </div>
        <h1 class="font-display text-lg xs:text-xl sm:text-2xl 2xl:text-3xl font-black text-slate-900 tracking-tight">{{ $network->name }}</h1>
        <p class="text-slate-500 text-xs xs:text-sm 2xl:text-base font-medium mt-1 flex items-center justify-center gap-1.5 watch-hide">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse inline-block"></span>
            High-speed wireless internet
        </p>
    </div>

    {{-- ---- ALERTS ---- --}}
    @if(session('success'))
    <div class="glass-panel rounded-2xl p-4 border border-emerald-200 bg-emerald-50/80 animate-fade-in flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div>
            <p class="font-bold text-emerald-800 text-sm">Payment Initiated!</p>
            <p class="text-emerald-700 text-sm mt-0.5">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="glass-panel rounded-2xl p-4 border border-red-200 bg-red-50/80 animate-fade-in flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0 mt-0.5">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <div>
            <p class="font-bold text-red-800 text-sm">Something went wrong</p>
            @foreach($errors->all() as $error)
            <p class="text-red-700 text-sm mt-0.5">{{ $error }}</p>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ---- MAIN CARD ---- --}}
    <div class="glass-panel rounded-2xl xs:rounded-3xl overflow-hidden">

        {{-- Tab Bar --}}
        <div class="flex border-b border-slate-200/80">
            <button id="tab-buy"
                class="tab-btn flex-1 py-3 xs:py-4 text-xs xs:text-sm 2xl:text-base font-bold transition-all border-b-2 flex items-center justify-center gap-1.5 xs:gap-2"
                onclick="switchTab('buy')">
                <svg class="w-3.5 h-3.5 xs:w-4 xs:h-4 watch-hide" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Buy Package
            </button>
            <button id="tab-redeem"
                class="tab-btn flex-1 py-3 xs:py-4 text-xs xs:text-sm 2xl:text-base font-bold transition-all border-b-2 flex items-center justify-center gap-1.5 xs:gap-2"
                onclick="switchTab('redeem')">
                <svg class="w-3.5 h-3.5 xs:w-4 xs:h-4 watch-hide" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                Use Voucher
            </button>
        </div>

        {{-- ======== BUY TAB ======== --}}
        <div id="section-buy" class="p-4 xs:p-5 sm:p-6 2xl:p-8 animate-fade-in">
            <form method="POST" action="{{ route('portal.purchase', $network->slug) }}" class="space-y-4 xs:space-y-5" onsubmit="showLoader(this)">
                @csrf
                <input type="hidden" name="mac" value="{{ $mac ?? '00:00:00:00:00:00' }}">
                <input type="hidden" name="ip"  value="{{ $ip  ?? '127.0.0.1' }}">

                {{-- Plan Selector --}}
                <div>
                    <p class="text-[10px] xs:text-xs 2xl:text-sm font-bold text-slate-500 uppercase tracking-wider mb-2 xs:mb-3">Choose a Plan</p>

                    @if($offers->isEmpty())
                        <div class="text-center py-4 xs:py-6 text-slate-400 text-xs xs:text-sm">
                            No packages available yet. Please check back later.
                        </div>
                    @else
                    <div class="grid grid-cols-2 gap-2 xs:gap-2.5 2xl:gap-4
                                sm:grid-cols-2
                                lg:grid-cols-3">
                        @foreach($offers as $offer)
                        <label class="cursor-pointer group">
                            <input type="radio" name="offer_id" value="{{ $offer->id }}"
                                   class="package-radio peer sr-only"
                                   {{ $loop->first ? 'checked' : '' }} required>
                            <div class="package-card h-full rounded-xl xs:rounded-2xl border-2 border-slate-200 bg-white p-2.5 xs:p-3.5 2xl:p-5 relative overflow-hidden">

                                <div class="check-icon absolute top-2 right-2 xs:top-2.5 xs:right-2.5 w-4 h-4 xs:w-5 xs:h-5 bg-brand-500 rounded-full flex items-center justify-center opacity-0 scale-50 transition-all duration-200">
                                    <svg class="w-2.5 h-2.5 xs:w-3 xs:h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </div>

                                <div class="font-display font-black text-base xs:text-xl 2xl:text-2xl text-slate-900 leading-none">
                                    {{ number_format($offer->price_minor / 100, 0) }}
                                    <span class="text-[10px] xs:text-xs font-bold text-slate-400">{{ $offer->currency }}</span>
                                </div>
                                <div class="text-[10px] xs:text-xs 2xl:text-sm font-bold text-brand-600 mt-0.5 xs:mt-1 truncate">{{ $offer->name }}</div>
                                <div class="flex items-center gap-1 mt-1.5 xs:mt-2 text-slate-400">
                                    <svg class="w-3 h-3 xs:w-3.5 xs:h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-[10px] xs:text-xs 2xl:text-sm font-semibold">{{ $offer->duration_label }}</span>
                                </div>
                                @if($offer->is_multi_device)
                                <div class="mt-1 xs:mt-1.5 inline-flex items-center gap-0.5 xs:gap-1 bg-indigo-50 text-indigo-600 rounded-full px-1.5 xs:px-2 py-0.5">
                                    <svg class="w-2.5 h-2.5 xs:w-3 xs:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                                    <span class="text-[9px] xs:text-[10px] font-bold">{{ $offer->max_devices }} devices</span>
                                </div>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Phone Input --}}
                <div>
                    <label for="phone" class="text-[10px] xs:text-xs 2xl:text-sm font-bold text-slate-500 uppercase tracking-wider mb-1.5 xs:mb-2 block">M-Pesa Number</label>
                    <div class="flex items-center bg-white border-2 border-slate-200 rounded-xl xs:rounded-2xl overflow-hidden input-focus-ring transition-all">
                        <div class="px-2.5 xs:px-3.5 py-2.5 xs:py-3 border-r border-slate-200 bg-slate-50 flex items-center gap-1.5 xs:gap-2 select-none flex-shrink-0">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/1/15/M-PESA_LOGO-01.svg" class="h-3 xs:h-4 object-contain watch-hide" alt="M-Pesa" onerror="this.style.display='none'">
                            <span class="text-slate-600 font-bold text-xs xs:text-sm">+254</span>
                        </div>
                        <input type="tel" id="phone" name="phone"
                               class="flex-1 px-2 xs:px-3 py-2.5 xs:py-3.5 text-slate-900 font-semibold text-sm xs:text-base outline-none bg-transparent placeholder-slate-300 min-w-0"
                               placeholder="712 345 678"
                               pattern="[0-9]{9,10}" required
                               value="{{ old('phone') }}"
                               inputmode="numeric">
                    </div>
                    <p class="text-[10px] xs:text-[11px] text-slate-400 mt-1 xs:mt-1.5 flex items-center gap-1 watch-hide">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        You'll receive an M-Pesa push notification to enter your PIN
                    </p>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="submit-btn w-full relative flex items-center justify-center gap-2 xs:gap-2.5 py-3 xs:py-4 2xl:py-5 px-4 xs:px-6 rounded-xl xs:rounded-2xl font-bold text-white text-xs xs:text-sm 2xl:text-base bg-mpesa hover:bg-[#3d9e3c] shadow-lg shadow-green-600/25 transition-all active:scale-[0.98] overflow-hidden">
                    <span class="btn-text flex items-center gap-1.5 xs:gap-2">
                        <svg class="w-4 h-4 xs:w-5 xs:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2v-1a2 2 0 00-2-2H8a2 2 0 00-2 2v1a2 2 0 002 2zM12 3v1m0 0a9 9 0 019 9v1a9 9 0 01-18 0v-1a9 9 0 019-9z"/></svg>
                        Pay & Connect via M-Pesa
                    </span>
                    <span class="btn-loader absolute inset-0 flex items-center justify-center opacity-0 bg-mpesa">
                        <svg class="animate-spin h-4 w-4 xs:h-5 xs:w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span class="ml-2 text-xs xs:text-sm">Sending request...</span>
                    </span>
                </button>

            </form>
        </div>

        {{-- ======== VOUCHER TAB ======== --}}
        <div id="section-redeem" class="hidden p-4 xs:p-5 sm:p-6 2xl:p-8 animate-fade-in">
            <form method="POST" action="{{ route('portal.redeem', $network->slug) }}" class="space-y-4 xs:space-y-5" onsubmit="showLoader(this)">
                @csrf
                <input type="hidden" name="mac" value="{{ $mac ?? '00:00:00:00:00:00' }}">
                <input type="hidden" name="ip"  value="{{ $ip  ?? '127.0.0.1' }}">

                <div>
                    <label for="voucher_code" class="text-[10px] xs:text-xs 2xl:text-sm font-bold text-slate-500 uppercase tracking-wider mb-1.5 xs:mb-2 block">Scratch Card Code</label>
                    <input type="text" id="voucher_code" name="voucher_code"
                           class="block w-full px-3 xs:px-4 py-3 xs:py-4 text-center tracking-[0.2em] xs:tracking-[0.25em] font-mono font-black text-lg xs:text-2xl 2xl:text-3xl bg-slate-50 border-2 border-slate-200 rounded-xl xs:rounded-2xl focus:ring-0 focus:border-slate-900 outline-none placeholder-slate-200 uppercase transition-colors"
                           placeholder="XXXX-XXXX" required maxlength="9"
                           autocomplete="off" autocorrect="off" autocapitalize="characters" spellcheck="false">
                    <p class="text-[10px] xs:text-xs text-slate-400 text-center mt-1 xs:mt-1.5">Enter the code printed on your scratch card</p>
                </div>

                <div>
                    <label for="voucher_phone" class="text-[10px] xs:text-xs 2xl:text-sm font-bold text-slate-500 uppercase tracking-wider mb-1.5 xs:mb-2 block">Your Phone Number</label>
                    <div class="flex items-center bg-white border-2 border-slate-200 rounded-xl xs:rounded-2xl overflow-hidden input-focus-ring transition-all">
                        <div class="px-2.5 xs:px-3.5 py-2.5 xs:py-3 border-r border-slate-200 bg-slate-50 text-slate-600 font-bold text-xs xs:text-sm select-none flex-shrink-0">
                            +254
                        </div>
                        <input type="tel" id="voucher_phone" name="phone"
                               class="flex-1 px-2 xs:px-3 py-2.5 xs:py-3.5 text-slate-900 font-semibold text-sm xs:text-base outline-none bg-transparent placeholder-slate-300 min-w-0"
                               placeholder="712 345 678"
                               required value="{{ old('phone') }}"
                               inputmode="numeric">
                    </div>
                </div>

                <button type="submit"
                        class="submit-btn w-full relative flex items-center justify-center gap-2 xs:gap-2.5 py-3 xs:py-4 2xl:py-5 px-4 xs:px-6 rounded-xl xs:rounded-2xl font-bold text-white text-xs xs:text-sm 2xl:text-base bg-slate-900 hover:bg-slate-800 shadow-lg shadow-slate-900/20 transition-all active:scale-[0.98] overflow-hidden">
                    <span class="btn-text flex items-center gap-1.5 xs:gap-2">
                        <svg class="w-4 h-4 xs:w-5 xs:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Activate Internet Access
                    </span>
                    <span class="btn-loader absolute inset-0 flex items-center justify-center opacity-0 bg-slate-900">
                        <svg class="animate-spin h-4 w-4 xs:h-5 xs:w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span class="ml-2 text-xs xs:text-sm">Verifying...</span>
                    </span>
                </button>

            </form>
        </div>

        {{-- Footer --}}
        <div class="px-4 xs:px-6 pb-4 xs:pb-5 pt-1 border-t border-slate-100 text-center watch-hide">
            <p class="text-[10px] xs:text-xs text-slate-400 font-medium">
                Secured by <span class="font-bold text-slate-500">goAfrica Connect</span>
            </p>
        </div>

    </div>

    {{-- How it works hint --}}
    <div class="text-center text-[10px] xs:text-xs text-slate-400 font-medium pb-4 space-y-1 watch-hide">
        <p>💡 <strong class="text-slate-600">How it works:</strong> Select a plan → Enter your M-Pesa number → Enter PIN → Connected.</p>
    </div>

</div>{{-- /space-y-4 --}}

<script>
function switchTab(tab) {
    ['buy','redeem'].forEach(t => {
        document.getElementById('tab-' + t).classList.toggle('active', t === tab);
        document.getElementById('section-' + t).classList.toggle('hidden', t !== tab);
    });
}

function showLoader(form) {
    const btn = form.querySelector('.submit-btn');
    if (!btn) return;
    btn.querySelector('.btn-text').style.opacity = '0';
    btn.querySelector('.btn-loader').style.opacity = '1';
    btn.disabled = true;
    btn.classList.add('opacity-90', 'cursor-not-allowed', 'pointer-events-none');
}

document.addEventListener('DOMContentLoaded', function () {
    // Determine which tab to open
    const params = new URLSearchParams(window.location.search);
    const hasVoucherError = @json($errors->has('voucher_error') || !empty(old('voucher_code')));

    if (params.get('voucher') || hasVoucherError) {
        switchTab('redeem');
        const v = params.get('voucher');
        if (v) {
            let clean = v.replace(/[^A-Za-z0-9]/g,'').toUpperCase().substring(0,8);
            if (clean.length > 4) clean = clean.substring(0,4) + '-' + clean.substring(4);
            const el = document.getElementById('voucher_code');
            if (el) el.value = clean;
        }
    } else {
        switchTab('buy');
    }

    // Auto-format voucher code input
    const vInput = document.getElementById('voucher_code');
    if (vInput) {
        vInput.addEventListener('input', function (e) {
            let v = e.target.value.replace(/[^A-Za-z0-9]/g,'').toUpperCase().substring(0,8);
            if (v.length > 4) v = v.substring(0,4) + '-' + v.substring(4);
            e.target.value = v;
        });
    }
});
</script>
@endsection
