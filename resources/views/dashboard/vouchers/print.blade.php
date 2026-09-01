<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Vouchers - {{ $network->name ?? 'GoAfrica Connect' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .voucher-card { break-inside: avoid; page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-slate-100 p-6">
    <!-- Print Bar Controls -->
    <div class="no-print max-w-4xl mx-auto mb-6 bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
        <div>
            <h1 class="font-bold text-slate-800 text-base">Print Batch: {{ $vouchers->count() }} Vouchers</h1>
            <p class="text-xs text-slate-500">Formatted for standard A4 sheets (8 tickets per page) or thermal receipt printers.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Now (Ctrl + P)
            </button>
        </div>
    </div>

    <!-- Voucher Print Grid -->
    <div class="max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($vouchers as $voucher)
        @php
            $connectUrl = url('/connect/' . ($network->slug ?? 'default') . '?voucher=' . $voucher->code);
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&margin=0&data=' . urlencode($connectUrl);
        @endphp
        <div class="voucher-card bg-white border-2 border-dashed border-slate-300 rounded-2xl p-4 flex flex-col justify-between relative overflow-hidden shadow-sm">
            <!-- Top branding -->
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-1.5">
                    <div class="w-5 h-5 bg-blue-600 rounded-lg flex items-center justify-center text-[10px] font-black text-white">
                        W
                    </div>
                    <span class="text-[11px] font-bold text-slate-800 tracking-tight truncate max-w-[110px]">
                        {{ $network->name ?? 'WiFi Pass' }}
                    </span>
                </div>
                <span class="text-[10px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-blue-50 text-blue-700">
                    @if($voucher->type == 'time')
                        @if($voucher->value >= 1440)
                            {{ round($voucher->value / 1440) }} Day(s)
                        @elseif($voucher->value >= 60)
                            {{ round($voucher->value / 60) }} Hour(s)
                        @else
                            {{ $voucher->value }} Mins
                        @endif
                    @else
                        KES {{ number_format($voucher->value / 100, 0) }}
                    @endif
                </span>
            </div>

            <!-- QR Code & Voucher Code -->
            <div class="my-3 flex flex-col items-center justify-center text-center">
                <div class="p-1.5 bg-white border border-slate-200 rounded-xl shadow-xs mb-2">
                    <img src="{{ $qrUrl }}" alt="QR Code" class="w-20 h-20 object-contain rounded-lg">
                </div>
                <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Scratch / Voucher Code</span>
                <div class="font-mono text-base font-black tracking-widest text-slate-900 bg-slate-100 border border-slate-200 px-3 py-1 rounded-lg mt-0.5 select-all">
                    {{ $voucher->code }}
                </div>
            </div>

            <!-- Footer instructions -->
            <div class="pt-2 border-t border-slate-100 text-center">
                <p class="text-[9px] text-slate-400 font-medium">Scan QR to connect or enter code on portal</p>
                <p class="text-[9px] text-slate-500 font-bold mt-0.5">Powered by GoAfrica Connect</p>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-slate-200">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            <p class="font-bold text-slate-700 text-sm">No unused vouchers found to print</p>
            <p class="text-slate-400 text-xs mt-1">Generate a new batch first from the dashboard.</p>
        </div>
        @endforelse
    </div>
</body>
</html>
