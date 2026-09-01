@extends('layouts.portal')

@section('content')
<div class="glass-panel rounded-3xl p-6 sm:p-8">
    
    <!-- Branding Header -->
    <div class="text-center mb-8">
        <div class="mx-auto w-16 h-16 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center mb-4 text-brand-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
        </div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $network->name }}</h1>
        <p class="text-slate-500 font-medium text-sm mt-1">High-speed wireless internet</p>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 p-4 mb-6 rounded-xl flex items-start animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-sm text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 p-4 mb-6 rounded-xl flex items-start animate-fade-in">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-sm text-red-800 font-medium">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="flex border-b border-slate-200 mb-8">
        <button id="tab-buy" class="tab-btn active flex-1 pb-4 text-sm font-bold transition-all border-b-2" onclick="switchTab('buy')">
            Buy Package
        </button>
        <button id="tab-redeem" class="tab-btn flex-1 pb-4 text-sm font-bold transition-all border-b-2" onclick="switchTab('redeem')">
            Use Voucher
        </button>
    </div>

    <!-- Tab Content: Buy via M-Pesa -->
    <div id="section-buy" class="block animate-fade-in">
        <form method="POST" action="{{ route('portal.purchase', $network->slug) }}" class="space-y-6" onsubmit="showLoader(this, 'Initiating payment...')">
            @csrf
            <input type="hidden" name="mac" value="{{ $mac ?? '00:00:00:00:00:00' }}">
            <input type="hidden" name="ip" value="{{ $ip ?? '127.0.0.1' }}">
            
            <!-- Dynamic Packages -->
            <div>
                <label class="block text-sm font-bold text-slate-800 mb-3">1. Select a Plan</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($offers as $offer)
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="offer_id" value="{{ $offer->id }}" class="package-radio peer sr-only" {{ $loop->first ? 'checked' : '' }} required>
                        <div class="package-card h-full rounded-2xl border-2 border-slate-200 bg-white p-4 relative overflow-hidden group-hover:border-slate-300">
                            
                            <!-- Active Checkmark -->
                            <div class="check-icon absolute top-3 right-3 opacity-0 transform scale-50 transition-all duration-300 text-brand-500 bg-brand-50 rounded-full p-0.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                            
                            <div class="font-black text-xl text-slate-900 tracking-tight">{{ $offer->currency }} {{ number_format($offer->price_minor / 100, 0) }}</div>
                            <div class="text-xs font-bold text-brand-600 mt-0.5 uppercase tracking-wider">{{ $offer->name }}</div>
                            <div class="text-xs text-slate-500 font-medium mt-1.5 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $offer->duration_minutes }} Mins
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Phone Input -->
            <div>
                <label for="phone" class="block text-sm font-bold text-slate-800 mb-2">2. Enter M-Pesa Number</label>
                <div class="relative flex items-center bg-white border border-slate-300 rounded-xl input-focus-ring overflow-hidden transition-shadow">
                    <div class="pl-4 pr-3 py-3 border-r border-slate-200 bg-slate-50 flex items-center space-x-1.5 select-none">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/1/15/M-PESA_LOGO-01.svg" class="h-4 object-contain" alt="M-Pesa">
                        <span class="text-slate-500 font-semibold text-sm">+254</span>
                    </div>
                    <input type="tel" id="phone" name="phone" 
                        class="flex-1 w-full px-3 py-3 text-slate-900 font-medium text-base outline-none bg-transparent placeholder-slate-400" 
                        placeholder="7XX XXX XXX" 
                        pattern="[0-9]{9,10}"
                        required 
                        value="{{ old('phone') }}">
                </div>
                <p class="text-[11px] text-slate-500 mt-2 font-medium flex items-center justify-end">
                    <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7z"></path></svg>
                    End-to-End Encrypted
                </p>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit-btn relative w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-mpesa hover:bg-[#3d9e3c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-mpesa transition-all transform hover:-translate-y-0.5 shadow-lg shadow-green-600/20 active:translate-y-0 overflow-hidden">
                <span class="btn-text flex items-center">
                    Pay with M-Pesa
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </span>
                <span class="btn-loader absolute inset-0 flex items-center justify-center opacity-0 transition-opacity bg-mpesa">
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Processing...
                </span>
            </button>
        </form>
    </div>

    <!-- Tab Content: Redeem Voucher -->
    <div id="section-redeem" class="hidden animate-fade-in">
        <form method="POST" action="{{ route('portal.redeem', $network->slug) }}" class="space-y-6" onsubmit="showLoader(this, 'Verifying voucher...')">
            @csrf
            <input type="hidden" name="mac" value="{{ $mac ?? '00:00:00:00:00:00' }}">
            <input type="hidden" name="ip" value="{{ $ip ?? '127.0.0.1' }}">
            
            <div>
                <label for="voucher_code" class="block text-sm font-bold text-slate-800 mb-2">Voucher Code</label>
                <input type="text" id="voucher_code" name="voucher_code" 
                    class="block w-full px-4 py-4 text-center tracking-[0.2em] font-mono text-xl font-bold bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-shadow outline-none placeholder-slate-300 uppercase" 
                    placeholder="XXXX-XXXX" 
                    required>
            </div>
            
            <div>
                <label for="voucher_phone" class="block text-sm font-bold text-slate-800 mb-2">Phone Number</label>
                <div class="relative flex items-center bg-white border border-slate-300 rounded-xl input-focus-ring overflow-hidden transition-shadow">
                    <div class="pl-4 pr-3 py-3 border-r border-slate-200 bg-slate-50 text-slate-500 font-semibold text-sm select-none">
                        +254
                    </div>
                    <input type="tel" id="voucher_phone" name="phone" 
                        class="flex-1 w-full px-3 py-3 text-slate-900 font-medium text-base outline-none bg-transparent placeholder-slate-400" 
                        placeholder="7XX XXX XXX" 
                        required 
                        value="{{ old('phone') }}">
                </div>
            </div>

            <button type="submit" class="submit-btn relative w-full flex justify-center items-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-slate-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-all transform hover:-translate-y-0.5 shadow-lg shadow-slate-900/20 active:translate-y-0 overflow-hidden">
                <span class="btn-text flex items-center">
                    Activate Internet
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </span>
                <span class="btn-loader absolute inset-0 flex items-center justify-center opacity-0 transition-opacity bg-slate-900">
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Connecting...
                </span>
            </button>
        </form>
    </div>
    
    <!-- Footer -->
    <div class="mt-8 text-center pt-2">
        <p class="text-xs text-slate-400 font-medium tracking-wide uppercase">Secured by <span class="font-bold text-slate-500">goAfrica Connect</span></p>
    </div>
</div>

<script>
    function switchTab(tab) {
        // Reset tabs
        document.getElementById('tab-buy').classList.remove('active');
        document.getElementById('tab-redeem').classList.remove('active');
        
        // Hide all sections
        document.getElementById('section-buy').classList.add('hidden');
        document.getElementById('section-redeem').classList.add('hidden');
        
        // Activate selected tab
        document.getElementById('tab-' + tab).classList.add('active');
        document.getElementById('section-' + tab).classList.remove('hidden');
    }

    function showLoader(form, loadingText) {
        const btn = form.querySelector('.submit-btn');
        const textSpan = btn.querySelector('.btn-text');
        const loaderSpan = btn.querySelector('.btn-loader');
        
        // Disable button
        btn.classList.add('opacity-90', 'cursor-not-allowed', 'pointer-events-none');
        btn.classList.remove('hover:-translate-y-0.5', 'hover:bg-[#3d9e3c]', 'hover:bg-black');
        
        // Swap text for loader
        textSpan.style.opacity = '0';
        loaderSpan.style.opacity = '1';
        
        // Optional: you can dynamically set the loader text if needed
        if(loadingText) {
            loaderSpan.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                ${loadingText}
            `;
        }
    }

    // Initialize tabs correctly based on active errors or URL param
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const voucherParam = urlParams.get('voucher');

        if (voucherParam) {
            const vInput = document.getElementById('voucher_code');
            if (vInput) {
                let formatted = voucherParam.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
                if (formatted.length > 4) {
                    formatted = formatted.substring(0, 4) + '-' + formatted.substring(4, 8);
                }
                vInput.value = formatted;
            }
            switchTab('redeem');
        } else if (@json($errors->has('voucher_error') || old('voucher_code'))) {
            switchTab('redeem');
        } else {
            switchTab('buy');
        }
        
        // Auto-format voucher input
        const voucherInput = document.getElementById('voucher_code');
        if (voucherInput) {
            voucherInput.addEventListener('input', function(e) {
                let val = e.target.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
                if (val.length > 4) {
                    val = val.substring(0, 4) + '-' + val.substring(4, 8);
                }
                e.target.value = val;
            });
        }
    });
</script>
@endsection
