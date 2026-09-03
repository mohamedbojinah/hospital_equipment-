@extends('layouts.app')

@section('title', 'الإشعارات')
@section('header', 'كافة الإشعارات')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">إشعارات النظام</h3>
        <form action="{{ url('notifications/read-all') }}" method="POST">
            @csrf
            <button class="btn btn-outline">تحديد الكل كمقروء</button>
        </form>
    </div>

    <div style="display:flex; flex-direction: column; gap: 1rem;">
        @forelse($notifications as $note)
        <div style="padding: 1rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); {{ $note->is_read ? 'opacity: 0.7; background: var(--bg-color);' : 'border-right: 4px solid var(--primary-color); background: #fff;' }}">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <h4 style="margin: 0; color: var(--text-main);">{{ $note->title }}</h4>
                <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $note->created_at->diffForHumans() }}</span>
            </div>
            <p style="margin: 0; color: var(--text-muted);">{{ $note->message }}</p>
            
            @if(!$note->is_read)
            <form action="{{ url('notifications/'.$note->id.'/read') }}" method="POST" style="margin-top: 1rem; text-align: left;">
                @csrf @method('PATCH')
                <button class="btn btn-primary btn-sm" style="font-size: 0.8rem;">تحديد كمقروء</button>
            </form>
            @endif
        </div>
        @empty
        <div class="text-center" style="padding: 2rem; color: var(--text-muted);">
            لا توجد إشعارات حالياً.
        </div>
        @endforelse
    </div>
</div>
@endsection
