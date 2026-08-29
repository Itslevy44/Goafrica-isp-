@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Web Terminal</h2>
    <p class="text-sm text-slate-500 mt-1">Run administrative RouterOS commands directly on your connected devices.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Terminal Window -->
    <div class="lg:col-span-2">
        <div class="bg-slate-900 rounded-xl shadow-xl overflow-hidden border border-slate-700 flex flex-col h-[600px]">
            <!-- Terminal Header -->
            <div class="bg-slate-800 px-4 py-3 flex items-center justify-between border-b border-slate-700">
                <div class="flex space-x-2">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                </div>
                <div class="text-xs font-mono text-slate-400">root@mikrotik:~</div>
                <div class="w-16"></div> <!-- spacer for balance -->
            </div>
            
            <!-- Terminal Output Area -->
            <div class="flex-1 p-4 overflow-y-auto font-mono text-sm bg-slate-900 text-slate-300">
                <div class="mb-4">
                    <span class="text-green-400">goAfrica Connect</span> Web Terminal v1.0<br>
                    Type a command in the input box below. Example: <span class="text-blue-400">/ip/address/print</span>
                </div>
                
                @if(session('cmd_response'))
                    <div class="mb-2 text-slate-400">$ {{ session('cmd_executed') ?? 'Command executed' }}</div>
                    <pre class="text-green-300 whitespace-pre-wrap font-mono text-sm p-4 bg-black/30 rounded border border-slate-800">{{ is_array(session('cmd_response')) ? json_encode(session('cmd_response'), JSON_PRETTY_PRINT) : session('cmd_response') }}</pre>
                @endif
            </div>

            <!-- Terminal Input -->
            <div class="bg-slate-800 p-4 border-t border-slate-700">
                <form method="POST" action="{{ route('dashboard.runCmd') }}" class="flex space-x-3">
                    @csrf
                    <select name="device_id" class="bg-slate-900 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-3 py-2 font-mono" required>
                        @foreach($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->name }} ({{ $device->ip_address }})</option>
                        @endforeach
                    </select>
                    
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-500 font-mono">
                            $
                        </div>
                        <input type="text" name="command" class="bg-slate-900 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-8 p-2 font-mono" placeholder="/ip/hotspot/active/print" required autocomplete="off" autofocus>
                    </div>
                    
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-900 font-medium rounded-lg text-sm px-5 py-2 focus:outline-none transition-colors">
                        Run
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Command History -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="font-bold text-slate-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Command History
                </h3>
            </div>
            
            <div class="flex-1 overflow-y-auto p-0">
                <ul class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                    <li class="p-4 hover:bg-slate-50 transition-colors">
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">{{ $log->device->name ?? 'Unknown Device' }}</span>
                            <span class="text-xs text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <code class="block text-sm text-slate-800 bg-slate-100 px-2 py-1 rounded font-mono break-all my-2 border border-slate-200">
                            {{ $log->command }}
                        </code>
                        <div class="flex items-center text-xs text-slate-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ $log->user->name ?? 'System' }}
                        </div>
                    </li>
                    @empty
                    <li class="p-8 text-center text-slate-500 text-sm">
                        No commands logged yet.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
