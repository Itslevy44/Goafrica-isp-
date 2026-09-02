@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Notifications</h2>
            <p class="text-sm text-slate-500 mt-1">Subscription alerts and system messages.</p>
        </div>
        @if(auth()->user()->unreadNotifications->count())
        <form action="{{ route('dashboard.notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-200 transition-colors">
                Mark All Read
            </button>
        </form>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($notifications->isEmpty())
        <div class="py-16 text-center text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <p class="font-medium text-slate-500">No notifications yet.</p>
        </div>
        @else
        <div class="divide-y divide-slate-100">
            @foreach($notifications as $notif)
            @php $data = $notif->data; $isUnread = is_null($notif->read_at); @endphp
            <div class="px-5 py-4 flex items-start gap-4 {{ $isUnread ? 'bg-blue-50/40' : '' }} hover:bg-slate-50 transition-colors">

                {{-- Icon --}}
                <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center mt-0.5
                    {{ ($data['type'] ?? 'info') === 'danger'  ? 'bg-red-100 text-red-600' :
                      (($data['type'] ?? 'info') === 'warning' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600') }}">
                    @if(($data['type'] ?? 'info') === 'danger')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif(($data['type'] ?? 'info') === 'warning')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-bold text-slate-900">{{ $data['title'] ?? 'Notification' }}</p>
                        @if($isUnread)
                        <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-600 mt-0.5">{{ $data['message'] ?? '' }}</p>
                    <div class="flex items-center gap-3 mt-1.5">
                        <span class="text-xs text-slate-400">{{ $notif->created_at->diffForHumans() }} · {{ $notif->created_at->format('M d, Y') }}</span>
                        @if(!empty($data['action_url']))
                        <a href="{{ $data['action_url'] }}" class="text-xs font-bold text-blue-600 hover:underline">Take Action →</a>
                        @endif
                    </div>
                </div>

                {{-- Mark read --}}
                @if($isUnread)
                <form action="{{ route('dashboard.notifications.read', $notif->id) }}" method="POST" class="flex-shrink-0">
                    @csrf
                    <button type="submit" title="Mark as read"
                            class="text-slate-300 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>

        @if($notifications->hasPages())
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
            {{ $notifications->links() }}
        </div>
        @endif
        @endif
    </div>

</div>
@endsection
