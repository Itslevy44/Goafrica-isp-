@extends('layouts.app')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Settings</h2>
        <p class="text-sm text-slate-500 mt-1">Manage your profile, networks, and team members.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl text-sm font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- ================================================================
         SECTION 1 — PROFILE SETTINGS
         ================================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Profile Settings</h3>
                <p class="text-xs text-slate-500">Update your name, email and password.</p>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('dashboard.settings.profile') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                               class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Change Password <span class="font-normal normal-case text-slate-400">(leave blank to keep current)</span></p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Current Password</label>
                            <input type="password" name="current_password"
                                   class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">New Password</label>
                            <input type="password" name="password"
                                   class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Min. 8 characters">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Repeat new password">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================
         SECTION 2 — NETWORKS
         ================================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Hotspot Networks</h3>
                    <p class="text-xs text-slate-500">Each network gets its own captive portal URL.</p>
                </div>
            </div>
            <a href="{{ route('dashboard.networks.index') }}"
               class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Manage Networks
            </a>
        </div>

        @if($networks->isEmpty())
        <div class="p-8 text-center text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
            <p class="text-sm font-medium text-slate-500">No networks yet.</p>
            <a href="{{ route('dashboard.networks.index') }}" class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:underline">
                Add your first network →
            </a>
        </div>
        @else
        <div class="divide-y divide-slate-100">
            @foreach($networks as $network)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-900 text-sm">{{ $network->name }}</span>
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $network->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ ucfirst($network->status ?? 'active') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 mt-0.5">
                        <span class="text-xs text-slate-400 font-mono">/connect/{{ $network->slug }}</span>
                        <span class="text-xs text-slate-400">{{ $network->currency }}</span>
                        <span class="text-xs text-slate-400">{{ $network->region->name ?? '' }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ url('/connect/' . $network->slug) }}" target="_blank"
                       class="text-xs font-bold text-slate-500 hover:text-blue-600 bg-slate-50 hover:bg-blue-50 px-2.5 py-1.5 rounded-lg transition-colors border border-slate-200">
                        View Portal
                    </a>
                    <a href="{{ route('dashboard.networks.index') }}"
                       class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 px-2.5 py-1.5 rounded-lg transition-colors border border-slate-200">
                        Edit
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ================================================================
         SECTION 3 — STAFF ACCOUNTS
         ================================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Staff Accounts</h3>
                    <p class="text-xs text-slate-500">Add team members and assign them to specific networks.</p>
                </div>
            </div>
            <button onclick="document.getElementById('add-staff-modal').classList.remove('hidden')"
                    class="text-xs font-bold text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5 border border-emerald-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Staff
            </button>
        </div>

        {{-- Current user (always shown) --}}
        <div class="px-6 py-4 flex items-center gap-3 bg-blue-50/50 border-b border-slate-100">
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center font-bold text-white text-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-900 text-sm">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-700">You</span>
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-600 capitalize">{{ Auth::user()->role }}</span>
                </div>
                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }} — All Networks</p>
            </div>
        </div>

        {{-- Staff list --}}
        @forelse($staff as $member)
        <div class="px-6 py-4 flex items-center gap-3 border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
            <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600 text-sm flex-shrink-0">
                {{ strtoupper(substr($member->name, 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-bold text-slate-900 text-sm">{{ $member->name }}</span>
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $member->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-600' }} capitalize">
                        {{ $member->role }}
                    </span>
                    @if($member->network)
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600">
                        📡 {{ $member->network->name }}
                    </span>
                    @else
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-slate-50 text-slate-400">
                        All Networks
                    </span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 truncate">{{ $member->email }}</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button onclick="openEditStaff({{ $member->id }}, '{{ $member->role }}', '{{ $member->network_id ?? '' }}')"
                        class="text-xs font-bold text-slate-500 hover:text-slate-700 bg-slate-50 hover:bg-slate-100 px-2.5 py-1.5 rounded-lg transition-colors border border-slate-200">
                    Edit
                </button>
                <form action="{{ route('dashboard.settings.staff.destroy', $member->id) }}" method="POST"
                      onsubmit="return confirm('Remove {{ $member->name }} from the team?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-lg transition-colors border border-red-100">
                        Remove
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="px-6 py-8 text-center text-slate-400">
            <p class="text-sm">No staff members yet. Add your first team member above.</p>
        </div>
        @endforelse
    </div>

</div>

{{-- ======== ADD STAFF MODAL ======== --}}
<div id="add-staff-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Add Staff Member</h3>
            <button onclick="document.getElementById('add-staff-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('dashboard.settings.staff.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
                <input type="text" name="name" placeholder="Jane Doe" required
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                <input type="email" name="email" placeholder="jane@example.com" required
                       class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-slate-400 mt-1">A temporary password will be shown after creation.</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Role</label>
                <select name="role" required class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="staff">Staff — Limited access</option>
                    <option value="admin">Admin — Full access</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Assign to Network</label>
                <select name="network_id" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">All Networks (no restriction)</option>
                    @foreach($networks as $network)
                    <option value="{{ $network->id }}">{{ $network->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Leave blank to give access to all networks.</p>
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl text-sm shadow-sm transition-colors">
                Create Staff Account
            </button>
        </form>
    </div>
</div>

{{-- ======== EDIT STAFF MODAL ======== --}}
<div id="edit-staff-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Edit Staff Member</h3>
            <button onclick="document.getElementById('edit-staff-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="edit-staff-form" action="" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Role</label>
                <select name="role" id="edit-staff-role" required class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="staff">Staff — Limited access</option>
                    <option value="admin">Admin — Full access</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Assign to Network</label>
                <select name="network_id" id="edit-staff-network" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">All Networks</option>
                    @foreach($networks as $network)
                    <option value="{{ $network->id }}">{{ $network->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-sm shadow-sm transition-colors">
                Save Changes
            </button>
        </form>
    </div>
</div>

<script>
function openEditStaff(id, role, networkId) {
    document.getElementById('edit-staff-form').action = '/dashboard/settings/staff/' + id;
    document.getElementById('edit-staff-role').value = role;
    document.getElementById('edit-staff-network').value = networkId || '';
    document.getElementById('edit-staff-modal').classList.remove('hidden');
}
</script>
@endsection
