@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Networks</h2>
            <p class="text-sm text-slate-500 mt-1">Manage your hotspot locations. Each network has its own captive portal URL.</p>
        </div>
        <button onclick="document.getElementById('add-network-modal').classList.remove('hidden')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Network
        </button>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">{{ $errors->first() }}</div>
    @endif

    <!-- Networks Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($networks as $network)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="px-5 py-4 border-b border-slate-100 flex items-start justify-between">
                <div>
                    <h3 class="font-bold text-slate-900">{{ $network->name }}</h3>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">/connect/{{ $network->slug }}</p>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $network->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ ucfirst($network->status ?? 'active') }}
                </span>
            </div>
            <div class="px-5 py-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Region</span>
                    <span class="font-medium text-slate-800">{{ $network->region->name ?? '—' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Currency</span>
                    <span class="font-medium text-slate-800">{{ $network->currency }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Portal URL</span>
                    <a href="{{ url('/connect/' . $network->slug) }}" target="_blank"
                       class="text-blue-600 hover:underline text-xs font-mono truncate max-w-[160px]">
                        /connect/{{ $network->slug }}
                    </a>
                </div>
            </div>
            <div class="px-5 pb-4 flex gap-2">
                <button onclick="openEditModal({{ $network->id }}, '{{ $network->name }}', '{{ $network->slug }}', {{ $network->region_id }}, '{{ $network->currency }}')"
                        class="flex-1 text-center text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 rounded-lg transition-colors border border-slate-200">
                    Edit
                </button>
                <form action="{{ route('dashboard.networks.destroy', $network->id) }}" method="POST"
                      onsubmit="return confirm('Delete network {{ $network->name }}? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors border border-red-100">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="sm:col-span-2 xl:col-span-3">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                <h3 class="font-bold text-slate-700">No Networks Yet</h3>
                <p class="text-sm text-slate-400 mt-1">Add your first hotspot network to get started.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Network Modal -->
<div id="add-network-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Add New Network</h3>
            <button onclick="document.getElementById('add-network-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('dashboard.networks.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Network Name</label>
                <input type="text" name="name" placeholder="e.g. Westlands Hotspot" required
                       class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">URL Slug</label>
                <div class="flex items-center gap-1">
                    <span class="text-xs text-slate-400 whitespace-nowrap">/connect/</span>
                    <input type="text" name="slug" placeholder="westlands" required
                           pattern="[a-z0-9\-]+" title="Lowercase letters, numbers and hyphens only"
                           class="flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm font-mono focus:ring-2 focus:ring-blue-500">
                </div>
                <p class="text-xs text-slate-400 mt-1">Lowercase letters, numbers and hyphens only.</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Region</label>
                <select name="region_id" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Select region...</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Currency</label>
                <input type="text" name="currency" placeholder="KES" maxlength="3" required
                       class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 uppercase">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg text-sm shadow-sm transition-colors">
                Create Network
            </button>
        </form>
    </div>
</div>

<!-- Edit Network Modal -->
<div id="edit-network-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Edit Network</h3>
            <button onclick="document.getElementById('edit-network-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="edit-network-form" action="" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Network Name</label>
                <input type="text" name="name" id="edit-name" required
                       class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">URL Slug</label>
                <div class="flex items-center gap-1">
                    <span class="text-xs text-slate-400 whitespace-nowrap">/connect/</span>
                    <input type="text" name="slug" id="edit-slug" required
                           pattern="[a-z0-9\-]+"
                           class="flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm font-mono focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Region</label>
                <select name="region_id" id="edit-region" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Currency</label>
                <input type="text" name="currency" id="edit-currency" maxlength="3" required
                       class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 uppercase">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg text-sm shadow-sm transition-colors">
                Save Changes
            </button>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, slug, regionId, currency) {
    document.getElementById('edit-network-form').action = '/dashboard/networks/' + id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-slug').value = slug;
    document.getElementById('edit-region').value = regionId;
    document.getElementById('edit-currency').value = currency;
    document.getElementById('edit-network-modal').classList.remove('hidden');
}
</script>
@endsection
