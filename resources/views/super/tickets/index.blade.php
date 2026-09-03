@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Support Tickets</h2>
            <p class="text-sm text-slate-500 mt-1">Messages from ISPs and visitors submitted via the contact form.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold bg-red-100 text-red-700 px-3 py-1.5 rounded-full">{{ $openCount }} Open</span>
            <span class="text-xs font-bold bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full">{{ $totalCount }} Total</span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm font-semibold flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Status filter tabs --}}
    <div class="flex gap-2 flex-wrap">
        @foreach(['open' => 'Open', 'in_progress' => 'In Progress', 'resolved' => 'Resolved', 'closed' => 'Closed', 'all' => 'All'] as $val => $label)
        <a href="{{ route('super.tickets.index', ['status' => $val]) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $status === $val ? 'bg-blue-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($tickets->isEmpty())
        <div class="py-16 text-center text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            <p class="font-medium text-slate-500">No tickets found.</p>
        </div>
        @else
        <div class="divide-y divide-slate-100">
            @foreach($tickets as $ticket)
            <div class="px-5 py-4 flex items-start gap-4 hover:bg-slate-50 transition-colors">
                {{-- Status dot --}}
                <div class="w-2.5 h-2.5 rounded-full flex-shrink-0 mt-2
                    {{ $ticket->status === 'open' ? 'bg-red-500' :
                      ($ticket->status === 'in_progress' ? 'bg-amber-500' :
                      ($ticket->status === 'resolved' ? 'bg-emerald-500' : 'bg-slate-300')) }}">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-bold text-slate-900 text-sm">{{ $ticket->subject }}</span>
                        @if($ticket->user)
                        <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full">Registered ISP</span>
                        @else
                        <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded-full">Guest</span>
                        @endif
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full capitalize
                            {{ $ticket->status === 'open' ? 'bg-red-100 text-red-700' :
                              ($ticket->status === 'in_progress' ? 'bg-amber-100 text-amber-700' :
                              ($ticket->status === 'resolved' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500')) }}">
                            {{ str_replace('_', ' ', $ticket->status) }}
                        </span>
                    </div>
                    <div class="text-xs text-slate-500 mt-0.5">
                        <span class="font-medium text-slate-700">{{ $ticket->name }}</span> · {{ $ticket->email }} · {{ $ticket->created_at->diffForHumans() }}
                    </div>
                    <p class="text-xs text-slate-400 mt-1 truncate max-w-lg">{{ $ticket->message }}</p>
                </div>
                <a href="{{ route('super.tickets.show', $ticket->id) }}"
                   class="flex-shrink-0 text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors border border-blue-200">
                    {{ $ticket->admin_reply ? 'View' : 'Reply' }}
                </a>
            </div>
            @endforeach
        </div>
        @if($tickets->hasPages())
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">{{ $tickets->links() }}</div>
        @endif
        @endif
    </div>

</div>
@endsection
