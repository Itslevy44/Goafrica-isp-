@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Active Sessions</h2>
            <p class="text-sm text-slate-500 mt-1">Monitor all hotspot connections in real time and kick users manually.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-center">
                <div class="text-2xl font-black text-emerald-600">{{ $activeCount }}</div>
                <div class="text-xs text-slate-400 font-medium">Live Now</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-black text-slate-400">{{ $expiredToday }}</div>
                <div class="text-xs text-slate-400 font-medium">Expired Today</div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">{{ $errors->first() }}</div>
    @endif

    <!-- Filter Bar -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <form method="GET" action="{{ route('dashboard.sessions.index') }}"
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="MAC or Phone..."
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-blue-500">
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Sessions</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Router</label>
                <select name="device_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-blue-500">
                    <option value="">All Routers</option>
                    @foreach($devices as $device)
                        <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                            {{ $device->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Network</label>
                <select name="network_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-blue-500">
                    <option value="">All Networks</option>
                    @foreach($networks as $network)
                        <option value="{{ $network->id }}" {{ request('network_id') == $network->id ? 'selected' : '' }}>
                            {{ $network->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-2 px-3 rounded-lg shadow-sm transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'device_id', 'network_id']) || $status !== 'active')
                <a href="{{ route('dashboard.sessions.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition-colors">✕</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Sessions Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="py-3 px-4 font-bold text-xs text-slate-500 uppercase tracking-wider">Customer</th>
                        <th class="py-3 px-4 font-bold text-xs text-slate-500 uppercase tracking-wider">MAC Address</th>
                        <th class="py-3 px-4 font-bold text-xs text-slate-500 uppercase tracking-wider">Router</th>
                        <th class="py-3 px-4 font-bold text-xs text-slate-500 uppercase tracking-wider">Started</th>
                        <th class="py-3 px-4 font-bold text-xs text-slate-500 uppercase tracking-wider">Expires</th>
                        <th class="py-3 px-4 font-bold text-xs text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 font-bold text-xs text-slate-500 uppercase tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sessions as $session)
                    @php $isLive = $session->status === 'active' && $session->ends_at > now(); @endphp
                    <tr class="hover:bg-slate-50 transition-colors {{ $isLive ? '' : 'opacity-60' }}">
                        <td class="py-3 px-4">
                            @if($session->customer)
                                <a href="{{ route('dashboard.customers.show', $session->customer->id) }}"
                                   class="font-bold text-blue-600 hover:underline text-sm">
                                    {{ $session->customer->phone }}
                                </a>
                            @else
                                <span class="text-slate-400 text-xs">Guest</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono text-xs text-slate-700 select-all">{{ $session->mac_address }}</td>
                        <td class="py-3 px-4 text-xs text-slate-600">{{ $session->device->name ?? '—' }}</td>
                        <td class="py-3 px-4 text-xs text-slate-500">{{ $session->started_at?->format('M d, h:i A') }}</td>
                        <td class="py-3 px-4 text-xs {{ $isLive ? 'text-emerald-700 font-bold' : 'text-slate-400' }}">
                            {{ $session->ends_at?->format('M d, h:i A') }}
                            @if($isLive)
                                <div class="text-[10px] font-medium text-emerald-500">{{ $session->ends_at->diffForHumans() }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($isLive)
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                    Live
                                </span>
                            @elseif($session->status === 'banned')
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Banned</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500">Expired</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if($isLive)
                            <form action="{{ route('dashboard.sessions.kick', $session->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Disconnect {{ $session->mac_address }} from the router?')">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold px-2 py-1 bg-red-50 hover:bg-red-100 rounded-md transition-colors">
                                    Kick
                                </button>
                            </form>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                            No sessions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sessions->hasPages())
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
