@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-3xl">

    <div class="flex items-center gap-3">
        <a href="{{ route('super.tickets.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $ticket->subject }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">Ticket #{{ $ticket->id }} · {{ $ticket->created_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm font-semibold flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Submitter info --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-indigo-500 flex items-center justify-center font-black text-white text-sm">
                {{ strtoupper(substr($ticket->name, 0, 2)) }}
            </div>
            <div>
                <p class="font-bold text-slate-900">{{ $ticket->name }}</p>
                <p class="text-xs text-slate-500">{{ $ticket->email }}
                    @if($ticket->user)
                    <span class="ml-1 text-[10px] font-bold bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full">Registered ISP</span>
                    @else
                    <span class="ml-1 text-[10px] font-bold bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded-full">Guest / Not Registered</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $ticket->message }}</p>
        </div>
    </div>

    {{-- Previous reply --}}
    @if($ticket->admin_reply)
    <div class="bg-blue-50 rounded-2xl border border-blue-200 p-5">
        <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2">Your Previous Reply · {{ $ticket->replied_at?->format('M d, Y h:i A') }}</p>
        <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $ticket->admin_reply }}</p>
    </div>
    @endif

    {{-- Reply form --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800">{{ $ticket->admin_reply ? 'Update Reply' : 'Send Reply' }}</h3>
        </div>
        <form action="{{ route('super.tickets.reply', $ticket->id) }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Your Reply</label>
                <textarea name="admin_reply" rows="6" required
                          placeholder="Type your response here..."
                          class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 resize-none">{{ old('admin_reply', $ticket->admin_reply) }}</textarea>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Update Status</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="flex gap-2 mt-5">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Reply
                    </button>
                    <form action="{{ route('super.tickets.destroy', $ticket->id) }}" method="POST"
                          onsubmit="return confirm('Delete this ticket?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold px-4 py-2.5 rounded-xl text-sm border border-red-200 transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-xs text-slate-400">
                @if($ticket->user)
                    This ISP is registered — they will receive both an <strong>email</strong> and a <strong>dashboard notification</strong>.
                @else
                    This is a guest submission — they will receive a reply <strong>by email only</strong>.
                @endif
            </p>
        </form>
    </div>

</div>
@endsection
