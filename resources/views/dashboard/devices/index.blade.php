@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Network Devices</h2>
        <p class="text-sm text-slate-500 mt-1">Manage your MikroTik routers and network infrastructure.</p>
    </div>
    <button onclick="document.getElementById('add-device-modal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Router
    </button>
</div>

<!-- Devices Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Router Name</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">IP Address</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">API Port</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600">Status</th>
                    <th class="py-3 px-4 font-semibold text-sm text-slate-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($devices as $device)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-3 px-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm">{{ $device->name }}</div>
                                <div class="text-xs text-slate-500 uppercase tracking-wide">{{ $device->type }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-sm font-mono text-slate-600">{{ $device->ip_address }}</td>
                    <td class="py-3 px-4 text-sm text-slate-600">{{ $device->api_port }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-600 capitalize">
                            {{ $device->status }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <button onclick="openEditModal({{ $device->id }}, '{{ $device->name }}', '{{ $device->ip_address }}', '{{ $device->api_port }}')" class="text-blue-600 hover:text-blue-800 text-sm font-medium mr-3">Edit</button>
                        <form action="{{ route('dashboard.devices.destroy', $device) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium" onclick="return confirm('Remove this router? Users might lose connectivity.')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 px-4 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-slate-800 font-medium">No devices found</h3>
                        <p class="text-slate-500 text-sm mt-1">Add your MikroTik router to get started.</p>
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
</script>
@endsection
