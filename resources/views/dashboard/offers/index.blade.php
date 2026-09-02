@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Internet Plans</h1>
            <p class="text-sm text-slate-500 mt-1">Manage pricing, durations, and multi-device options for your packages.</p>
        </div>
        <button onclick="document.getElementById('add-offer-modal').classList.remove('hidden')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-colors flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Plan
        </button>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Plans Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-900 text-sm">All Plans ({{ $offers->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3 px-5">Plan Name</th>
                        <th class="py-3 px-5">Price</th>
                        <th class="py-3 px-5">Duration</th>
                        <th class="py-3 px-5">Devices</th>
                        <th class="py-3 px-5">Status</th>
                        <th class="py-3 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($offers as $offer)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-3 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl {{ $offer->is_multi_device ? 'bg-purple-100 text-purple-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center flex-shrink-0">
                                    @if($offer->is_multi_device)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">{{ $offer->name }}</div>
                                    @if($offer->data_cap_mb)
                                    <div class="text-xs text-slate-400">{{ $offer->data_cap_mb >= 1024 ? round($offer->data_cap_mb/1024, 1).'GB' : $offer->data_cap_mb.'MB' }} cap</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-5 font-black text-slate-900 whitespace-nowrap">
                            {{ $network->currency }} {{ number_format($offer->price_minor / 100, 0) }}
                        </td>
                        <td class="py-3 px-5 text-slate-600 whitespace-nowrap">
                            {{ $offer->duration_label }}
                            <div class="text-xs text-slate-400">{{ $offer->duration_minutes }} mins</div>
                        </td>
                        <td class="py-3 px-5">
                            @if($offer->is_multi_device)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    {{ $offer->max_devices }} Devices
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Single Device
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-5">
                            <form action="{{ route('dashboard.offers.toggle', $offer) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold transition-colors {{ $offer->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $offer->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $offer->is_active ? 'Active' : 'Disabled' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-5 text-right whitespace-nowrap">
                            <button onclick="openEditModal({{ $offer->id }}, '{{ addslashes($offer->name) }}', {{ $offer->price_minor / 100 }}, {{ $offer->duration_minutes }}, {{ $offer->is_multi_device ? 'true' : 'false' }}, {{ $offer->max_devices }})"
                                class="text-blue-600 hover:text-blue-800 text-xs font-bold mr-3">Edit</button>
                            <form action="{{ route('dashboard.offers.destroy', $offer) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold"
                                    onclick="return confirm('Delete this plan? This cannot be undone.')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 px-6 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                            </div>
                            <h3 class="font-bold text-slate-800 text-sm">No plans yet</h3>
                            <p class="text-slate-500 text-xs mt-1">Create your first internet plan to start selling access.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== ADD PLAN MODAL ===== -->
<div id="add-offer-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900">Create New Plan</h3>
            <button onclick="document.getElementById('add-offer-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <form action="{{ route('dashboard.offers.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Plan Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2.5 border border-slate-200 bg-slate-50 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white text-sm transition-all" placeholder="e.g., 1 Hour Hotspot Pass" required>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Price ({{ $network->currency }})</label>
                        <input type="number" step="1" name="price" class="w-full px-3 py-2.5 border border-slate-200 bg-slate-50 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm" placeholder="50" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration (Minutes)</label>
                        <input type="number" name="duration_minutes" class="w-full px-3 py-2.5 border border-slate-200 bg-slate-50 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm" placeholder="60" required>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400">Quick ref: 1 Hour = 60 mins · 1 Day = 1440 mins · 1 Week = 10080 mins</p>

                <!-- Multi-Device Checkbox -->
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" id="add_multi_device" name="is_multi_device" value="1"
                               class="mt-0.5 h-4 w-4 text-purple-600 rounded border-slate-300 focus:ring-purple-500"
                               onchange="toggleMaxDevices('add_max_devices_row', this)">
                        <div>
                            <span class="text-sm font-bold text-slate-800 block">Allow Multiple Devices</span>
                            <span class="text-xs text-slate-500">Customers get a shareable code for extra devices (e.g. KES 400 for 2 devices)</span>
                        </div>
                    </label>
                    <div id="add_max_devices_row" class="hidden mt-3 pl-7">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Max Devices Allowed</label>
                        <input type="number" name="max_devices" id="add_max_devices" min="2" max="10" value="2"
                               class="w-28 px-3 py-2 border border-purple-200 bg-white rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl transition-colors shadow-sm">
                    Create Plan
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ===== EDIT PLAN MODAL ===== -->
<div id="edit-offer-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900">Edit Plan</h3>
            <button onclick="document.getElementById('edit-offer-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <form id="edit-offer-form" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Plan Name</label>
                    <input type="text" id="edit_name" name="name" class="w-full px-3 py-2.5 border border-slate-200 bg-slate-50 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white text-sm" required>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Price ({{ $network->currency }})</label>
                        <input type="number" step="1" id="edit_price" name="price" class="w-full px-3 py-2.5 border border-slate-200 bg-slate-50 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration (Minutes)</label>
                        <input type="number" id="edit_duration" name="duration_minutes" class="w-full px-3 py-2.5 border border-slate-200 bg-slate-50 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>
                </div>
                
                <!-- Multi-Device Checkbox (Edit) -->
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" id="edit_multi_device" name="is_multi_device" value="1"
                               class="mt-0.5 h-4 w-4 text-purple-600 rounded border-slate-300 focus:ring-purple-500"
                               onchange="toggleMaxDevices('edit_max_devices_row', this)">
                        <div>
                            <span class="text-sm font-bold text-slate-800 block">Allow Multiple Devices</span>
                            <span class="text-xs text-slate-500">Customers get a shareable code for extra devices</span>
                        </div>
                    </label>
                    <div id="edit_max_devices_row" class="hidden mt-3 pl-7">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Max Devices Allowed</label>
                        <input type="number" name="max_devices" id="edit_max_devices" min="2" max="10" value="2"
                               class="w-28 px-3 py-2 border border-purple-200 bg-white rounded-xl text-sm focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl transition-colors">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleMaxDevices(rowId, checkbox) {
    const row = document.getElementById(rowId);
    if (checkbox.checked) {
        row.classList.remove('hidden');
    } else {
        row.classList.add('hidden');
    }
}

function openEditModal(id, name, price, duration, isMulti, maxDevices) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_duration').value = duration;

    const multiCb = document.getElementById('edit_multi_device');
    multiCb.checked = isMulti;
    toggleMaxDevices('edit_max_devices_row', multiCb);

    if (isMulti) {
        document.getElementById('edit_max_devices').value = maxDevices;
    }

    document.getElementById('edit-offer-form').action = `/dashboard/offers/${id}`;
    document.getElementById('edit-offer-modal').classList.remove('hidden');
}
</script>
@endsection
