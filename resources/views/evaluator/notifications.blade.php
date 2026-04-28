@extends('layouts.evaluator')

@section('page_title', 'Notifications')

@section('content')
<div class="card">
    <div class="section-header" style="margin-bottom: 24px;">
        <div>
            <h2 class="section-title">Inbox</h2>
            <small>All your notifications</small>
        </div>
        <form action="{{ route('evaluator.notifications.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm">Mark all as read</button>
        </form>
    </div>

    @if($notifications->isEmpty())
        <div style="text-align: center; padding: 40px; color: var(--muted);">
            You don't have any notifications right now.
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 12px;">
            @foreach($notifications as $notif)
                <div style="display: flex; gap: 16px; padding: 16px; border: 1.5px solid {{ $notif->is_read ? 'var(--border)' : 'var(--primary-light)' }}; border-radius: 12px; background: {{ $notif->is_read ? 'white' : 'var(--page-bg)' }}; position: relative;">
                    @if(!$notif->is_read)
                        <div style="position: absolute; left: 0; top: 12px; bottom: 12px; width: 4px; background: var(--primary); border-radius: 0 4px 4px 0;"></div>
                    @endif
                    
                    <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(15,76,92,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                        @if(str_contains(strtolower($notif->title), 'assigned'))
                            📋
                        @elseif(str_contains(strtolower($notif->title), 'deadline'))
                            ⏳
                        @else
                            🔔
                        @endif
                    </div>
                    
                    <div style="flex: 1;">
                        <div style="font-size: 14px; font-weight: 700; color: var(--ink); margin-bottom: 4px;">{{ $notif->title }}</div>
                        <div style="font-size: 13px; color: var(--slate); margin-bottom: 8px;">{{ $notif->body }}</div>
                        <div style="font-size: 11px; color: var(--muted); display: flex; gap: 8px; align-items: center;">
                            <span>🕒 {{ $notif->created_at->diffForHumans() }}</span>
                            @if(!$notif->is_read)
                                <span class="badge teal" style="font-size: 9px; padding: 2px 6px;">NEW</span>
                            @endif
                        </div>
                    </div>
                    
                    @if(!$notif->is_read)
                        <div style="display: flex; align-items: center;">
                            <form action="{{ route('evaluator.notifications.markRead', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-ghost btn-sm" title="Mark as read">✓</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
