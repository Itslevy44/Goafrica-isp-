@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Internet Plans</h2>
        <p class="text-sm text-slate-500 mt-1">Manage pricing and durations for your network packages.</p>
    </div>
    <button onclick="document.getElementById('add-offer-modal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Create Plan
    </button>
</div>

<!-- Offers Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Plan Name</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Price</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Duration</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Status</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($offers as $offer)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-3 px-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="font-semibold text-slate-800 text-sm">{{ $offer->name }}</div>
                        </div>
                    </td>
                    <td class="py-3 px-4 font-medium text-slate-800">{{ $network->currency }} {{ number_format($offer->price_minor / 100, 0) }}</td>
                    <td class="py-3 px-4 text-sm text-slate-600">{{ $offer->duration_minutes }} Mins <span class="text-xs text-slate-400">({{ round($offer->duration_minutes / 60, 1) }} Hrs)</span></td>
                    <td class="py-3 px-4">
                        <form action="{{ route('dashboard.offers.toggle', $offer) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $offer->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $offer->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $offer->is_active ? 'Active' : 'Disabled' }}
                            </button>
                        </form>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <button onclick="openEditModal({{ $offer->id }}, '{{ $offer->name }}', {{ $offer->price_minor / 100 }}, {{ $offer->duration_minutes }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium mr-3">Edit</button>
                        <form action="{{ route('dashboard.offers.destroy', $offer) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium" onclick="return confirm('Are you sure you want to delete this plan?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 px-4 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-slate-800 font-medium">No plans found</h3>
                        <p class="text-slate-500 text-sm mt-1">Create an internet plan to start selling.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Offer Modal -->
<div id="add-offer-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Create New Plan</h3>
            <button onclick="document.getElementById('add-offer-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <form action="{{ route('dashboard.offers.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Plan Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="e.g., 1 Hour Pass" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Price ({{ $network->currency }})</label>
                        <input type="number" step="0.01" name="price" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Duration (Mins)</label>
                        <input type="number" name="duration_minutes" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="60" required>
                    </div>
                </div>
                <p class="text-xs text-slate-500">Quick ref: 24 Hours = 1440 mins | 7 Days = 10080 mins</p>
                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors shadow-sm">
                        Save Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Offer Modal -->
<div id="edit-offer-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Edit Plan</h3>
            <button onclick="document.getElementById('edit-offer-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <form id="edit-offer-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Plan Name</label>
                    <input type="text" id="edit_name" name="name" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Price ({{ $network->currency }})</label>
                        <input type="number" step="0.01" id="edit_price" name="price" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Duration (Mins)</label>
                        <input type="number" id="edit_duration" name="duration_minutes" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id, name, price, duration) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_duration').value = duration;
    
    // Set form action dynamically
    document.getElementById('edit-offer-form').action = `/dashboard/offers/${id}`;
    
    document.getElementById('edit-offer-modal').classList.remove('hidden');
}
</script>
@endsection
