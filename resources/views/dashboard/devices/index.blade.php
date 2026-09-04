@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Network Devices</h2>
        <p class="text-sm text-slate-500 mt-1">Manage your MikroTik routers and network infrastructure.</p>
    </div>
    <button onclick="document.getElementById('add-device-modal').classList.remove('hidden')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Router
    </button>
</div>

@if(session('success'))
<div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3 text-sm font-semibold">
    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif

<!-- Devices Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Router Name</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">IP Address</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Port</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Status</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($devices as $device)
                <tr class="hover:bg-slate-50/50 transition-colors" id="device-row-{{ $device->id }}">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm">{{ $device->name }}</div>
                                <div class="text-xs text-slate-400 uppercase tracking-wide">{{ $device->type }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-sm font-mono text-slate-600">{{ $device->ip_address }}</td>
                    <td class="py-3 px-4 text-sm text-slate-600">{{ $device->api_port }}</td>
                    <td class="py-3 px-4">
                        <span id="status-badge-{{ $device->id }}" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-bold
                            {{ $device->status === 'active' ? 'bg-emerald-100 text-emerald-700' :
                              ($device->status === 'offline' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-500') }}">
                            <span class="w-1.5 h-1.5 rounded-full
                                {{ $device->status === 'active' ? 'bg-emerald-500 animate-pulse' :
                                  ($device->status === 'offline' ? 'bg-red-500' : 'bg-slate-400') }}"></span>
                            {{ ucfirst($device->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            {{-- Test Connection --}}
                            <button onclick="testConnection({{ $device->id }})"
                                    id="test-btn-{{ $device->id }}"
                                    class="text-xs font-bold text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1.5 rounded-lg transition-colors border border-emerald-200">
                                Test
                            </button>
                            <button onclick="openEditModal({{ $device->id }}, '{{ $device->name }}', '{{ $device->ip_address }}', '{{ $device->api_port }}')"
                                    class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition-colors border border-blue-200">
                                Edit
                            </button>
                            <form action="{{ route('dashboard.devices.destroy', $device) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Remove this router? Users might lose connectivity.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-lg transition-colors border border-red-100">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 px-4 text-center">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <h3 class="text-slate-700 font-bold">No routers yet</h3>
                        <p class="text-slate-400 text-sm mt-1">Add your MikroTik router to start accepting payments.</p>
                        <a href="{{ route('dashboard.setup.index') }}" class="inline-flex items-center gap-1.5 mt-3 text-sm font-bold text-blue-600 hover:underline">
                            Use Setup Wizard →
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Device Modal -->
<div id="add-device-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Add Router</h3>
            <button onclick="document.getElementById('add-device-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <form action="{{ route('dashboard.devices.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Router Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="e.g., Downtown Core" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Public IP Address</label>
                        <input type="text" name="ip_address" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="192.168.88.1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">API Port</label>
                        <input type="number" name="api_port" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" value="8728" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">API Username</label>
                    <input type="text" name="username" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">API Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors shadow-sm">
                        Connect Router
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Device Modal -->
<div id="edit-device-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Edit Router</h3>
            <button onclick="document.getElementById('edit-device-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <form id="edit-device-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Router Name</label>
                    <input type="text" id="edit_name" name="name" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">IP Address</label>
                        <input type="text" id="edit_ip" name="ip_address" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">API Port</label>
                        <input type="number" id="edit_port" name="api_port" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-xs text-slate-500 mb-3">Leave credentials blank to keep existing ones.</p>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">New API Username</label>
                        <input type="text" name="username" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">New API Password</label>
                        <input type="password" name="password" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
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
function openEditModal(id, name, ip, port) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_ip').value = ip;
    document.getElementById('edit_port').value = port;
    
    // Set form action dynamically
    document.getElementById('edit-device-form').action = `/dashboard/devices/${id}`;
    
    document.getElementById('edit-device-modal').classList.remove('hidden');
}

async function testConnection(deviceId) {
    const btn = document.getElementById('test-btn-' + deviceId);
    const badge = document.getElementById('status-badge-' + deviceId);

    btn.textContent = 'Testing...';
    btn.disabled = true;
    btn.classList.add('opacity-60', 'cursor-not-allowed');

    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.content
            || document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1]?.replace(/%3D/g,'=') || '';

        const res  = await fetch(`/dashboard/devices/${deviceId}/test`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': decodeURIComponent(token),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });
        const data = await res.json();

        // Update status badge
        const isOnline = data.status === 'active';
        badge.innerHTML = `
            <span class="w-1.5 h-1.5 rounded-full ${isOnline ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'}"></span>
            ${isOnline ? 'Active' : 'Offline'}
        `;
        badge.className = `inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-bold ${
            isOnline ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'
        }`;

        // Show result toast
        showToast(data.message, isOnline ? 'success' : 'error');

    } catch(e) {
        showToast('Network error. Could not reach server.', 'error');
    } finally {
        btn.textContent = 'Test';
        btn.disabled = false;
        btn.classList.remove('opacity-60', 'cursor-not-allowed');
    }
}

function showToast(message, type) {
    const existing = document.getElementById('test-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'test-toast';
    toast.className = `fixed bottom-5 right-5 z-50 px-4 py-3 rounded-xl text-sm font-semibold shadow-xl max-w-sm flex items-start gap-2 transition-all
        ${type === 'success' ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white'}`;
    toast.innerHTML = `
        <span class="flex-1">${message}</span>
        <button onclick="this.parentElement.remove()" class="text-white/70 hover:text-white flex-shrink-0 mt-0.5">✕</button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast?.remove(), 7000);
}
</script>
@endsection
