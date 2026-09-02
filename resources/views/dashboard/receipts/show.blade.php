<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $transaction->id }} — {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <!-- Action buttons -->
    <div class="fixed top-4 right-4 flex gap-2 no-print z-50">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print / Save PDF
        </button>
        <a href="{{ url()->previous() }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2 rounded-lg text-sm font-bold shadow flex items-center gap-2 transition-colors">
            ← Back
        </a>
    </div>

    <!-- Receipt Card -->
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">

        <!-- Header -->
        <div class="bg-slate-900 px-8 py-6 text-white text-center">
            <div class="w-12 h-12 bg-gradient-to-tr from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center font-black text-2xl mx-auto mb-3 shadow-lg">G</div>
            <h1 class="font-black text-lg tracking-tight">{{ $tenant->name }}</h1>
            <p class="text-slate-400 text-xs mt-1">Internet Payment Receipt</p>
        </div>

        <!-- Status Banner -->
        <div class="px-8 py-4 {{ $transaction->status === 'success' ? 'bg-emerald-50 border-b border-emerald-100' : 'bg-red-50 border-b border-red-100' }}">
            <div class="flex items-center justify-center gap-2">
                @if($transaction->status === 'success')
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-bold text-emerald-700">Payment Confirmed</span>
                @else
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="font-bold text-red-700">{{ ucfirst($transaction->status) }}</span>
                @endif
            </div>
        </div>

        <!-- Amount -->
        <div class="text-center py-6 border-b border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Amount Paid</p>
            <h2 class="text-4xl font-black text-slate-900">
                {{ $transaction->currency }} {{ number_format($transaction->amount_minor / 100, 2) }}
            </h2>
        </div>

        <!-- Details Table -->
        <div class="px-8 py-5 space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium">Receipt No.</span>
                <span class="font-bold text-slate-800 font-mono text-xs">TXN-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium">Date</span>
                <span class="font-bold text-slate-800">{{ $transaction->created_at->format('d M Y, h:i A') }}</span>
            </div>
            @if($transaction->customer)
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium">Customer Phone</span>
                <span class="font-bold text-slate-800">{{ $transaction->customer->phone }}</span>
            </div>
            @endif
            @if($transaction->offer)
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium">Plan Purchased</span>
                <span class="font-bold text-slate-800">{{ $transaction->offer->name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium">Duration</span>
                <span class="font-bold text-slate-800">{{ $transaction->offer->duration_label }}</span>
            </div>
            @endif
            @if($transaction->network)
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium">Network / Hotspot</span>
                <span class="font-bold text-slate-800">{{ $transaction->network->name }}</span>
            </div>
            @endif
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium">Payment Method</span>
                <span class="font-bold text-slate-800 capitalize">{{ $transaction->gateway ?? 'M-Pesa' }}</span>
            </div>
            @if($transaction->gateway_ref && !str_starts_with($transaction->gateway_ref, 'PENDING_'))
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium">M-Pesa Ref</span>
                <span class="font-bold text-slate-800 font-mono text-xs">{{ $transaction->gateway_ref }}</span>
            </div>
            @endif
        </div>

        <!-- Divider dots -->
        <div class="px-8">
            <div class="border-t-2 border-dashed border-slate-200"></div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-5 text-center">
            <p class="text-xs text-slate-400">Thank you for using {{ $tenant->name }}.</p>
            <p class="text-xs text-slate-400 mt-1">This is a computer-generated receipt and requires no signature.</p>
            <p class="text-[10px] text-slate-300 mt-3">Powered by GoAfrica Connect</p>
        </div>
    </div>

</body>
</html>
