@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-3xl">

    <div class="flex items-center gap-3">
        <a href="{{ route('super.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Bulk Email</h2>
            <p class="text-sm text-slate-500 mt-0.5">Send a message to all or selected ISP admins.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">{{ $errors->first() }}</div>
    @endif

    {{-- Audience stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 text-center">
            <div class="text-2xl font-black text-slate-900">{{ $totalAdmins }}</div>
            <div class="text-xs text-slate-500 font-medium mt-1">Total Admins</div>
        </div>
        <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm p-4 text-center">
            <div class="text-2xl font-black text-emerald-600">{{ $activeTenantAdmins }}</div>
            <div class="text-xs text-slate-500 font-medium mt-1">Active ISPs</div>
        </div>
        <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-4 text-center">
            <div class="text-2xl font-black text-red-500">{{ $expiredTenantAdmins }}</div>
            <div class="text-xs text-slate-500 font-medium mt-1">Expired / Suspended</div>
        </div>
    </div>

    {{-- Compose form --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="font-bold text-slate-800">Compose Message</h3>
        </div>

        <form action="{{ route('super.bulk-email.send') }}" method="POST" class="p-6 space-y-5">
            @csrf

            {{-- Audience --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Send To</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['all' => ['label' => 'All ISPs', 'count' => $totalAdmins, 'color' => 'blue'],
                               'active' => ['label' => 'Active Only', 'count' => $activeTenantAdmins, 'color' => 'emerald'],
                               'expired' => ['label' => 'Expired Only', 'count' => $expiredTenantAdmins, 'color' => 'red']] as $val => $opt)
                    <label class="cursor-pointer">
                        <input type="radio" name="audience" value="{{ $val }}" class="peer sr-only" {{ $val === 'all' ? 'checked' : '' }}>
                        <div class="border-2 border-slate-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 rounded-xl p-3 text-center transition-all">
                            <div class="font-black text-slate-900">{{ $opt['count'] }}</div>
                            <div class="text-xs font-bold text-slate-500 mt-0.5">{{ $opt['label'] }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Subject --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="255"
                       placeholder="e.g. Important update from goAfrica Connect"
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Body --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Message Body</label>
                <textarea name="body" required rows="7" maxlength="5000"
                          placeholder="Write your message here. Plain text, supports Markdown-style **bold**."
                          class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 resize-none">{{ old('body') }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Max 5,000 characters. This appears as the main email body.</p>
            </div>

            {{-- Optional CTA --}}
            <div class="border-t border-slate-100 pt-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Optional Call-to-Action Button</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Button Text</label>
                        <input type="text" name="action_text" value="{{ old('action_text') }}" maxlength="100"
                               placeholder="e.g. Renew Subscription"
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Button URL</label>
                        <input type="url" name="action_url" value="{{ old('action_url') }}" maxlength="255"
                               placeholder="https://goafrica.site/..."
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- Warning + Submit --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <p class="text-xs text-amber-800 font-medium">This will send a real email to all selected ISP admins and create a dashboard notification for each. This action cannot be undone.</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('super.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        onclick="return confirm('Send this email to all selected ISPs?')"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-sm shadow-sm transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Send Bulk Email
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
