@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">System Settings</h2>
    <p class="text-sm text-slate-500 mt-1">Configure your network identity and payment gateways.</p>
</div>

<form action="{{ route('dashboard.settings.update') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Network Settings -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center bg-slate-50">
                    <div class="w-8 h-8 rounded bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <h3 class="font-bold text-slate-800">Network Identity</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Network Name</label>
                        <input type="text" name="name" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" value="{{ old('name', $network->name ?? '') }}" placeholder="e.g., Downtown Plaza WiFi" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Captive Portal URL Slug</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-slate-300 bg-slate-50 text-slate-500 text-sm">
                                {{ url('/connect/') }}/
                            </span>
                            <input type="text" name="slug" id="slug-input" class="flex-1 px-3 py-2 border border-slate-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" value="{{ old('slug', $network->slug ?? '') }}" placeholder="downtown-plaza" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Region</label>
                            <select name="region_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                                <option value="">Select country...</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ (old('region_id', $network->region_id ?? '') == $region->id) ? 'selected' : '' }}>
                                        {{ $region->name }} ({{ $region->country_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Currency</label>
                            <select name="currency" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                                <option value="KES" {{ (old('currency', $network->currency ?? '') === 'KES') ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                                <option value="TZS" {{ (old('currency', $network->currency ?? '') === 'TZS') ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
                                <option value="UGX" {{ (old('currency', $network->currency ?? '') === 'UGX') ? 'selected' : '' }}>UGX - Ugandan Shilling</option>
                                <option value="RWF" {{ (old('currency', $network->currency ?? '') === 'RWF') ? 'selected' : '' }}>RWF - Rwandan Franc</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- M-Pesa Settings -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded bg-green-100 text-green-600 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="font-bold text-slate-800">M-Pesa API Credentials</h3>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-sm text-slate-500 mb-2">Direct all Captive Portal payments to your Till/Paybill.</p>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Environment</label>
                        <select name="mpesa_environment" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                            <option value="sandbox" {{ (old('mpesa_environment', $tenant->mpesa_environment ?? '') === 'sandbox') ? 'selected' : '' }}>Sandbox (Testing)</option>
                            <option value="production" {{ (old('mpesa_environment', $tenant->mpesa_environment ?? '') === 'production') ? 'selected' : '' }}>Production (Live)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Till / Paybill Shortcode</label>
                        <input type="text" name="mpesa_shortcode" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm" value="{{ old('mpesa_shortcode', $tenant->mpesa_shortcode ?? '') }}" placeholder="e.g. 174379">
                        <p class="text-xs text-slate-500 mt-2">The system will automatically use the master API credentials to route payments directly to this Till number. You keep 100% of the revenue.</p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg transition-colors shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Save All Settings
                </button>
            </div>
        </div>
        
    </div>
</form>
@endsection
