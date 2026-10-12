@extends('layouts.app')

@section('title', 'مركز الإشعارات')
@section('header', 'مركز الإشعارات والتنبيهات')

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title"><i class="fa-regular fa-bell"></i> كافة التنبيهات</h3>
        @if($notifications->where('is_read', false)->count() > 0)
        <form action="{{ url('notifications/read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-check-double"></i> تحديد الكل كمقروء
            </button>
        </form>
        @endif
    </div>

    <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem;">
        @forelse($notifications as $note)
        @php
            $borderColor = match($note->type) {
                'danger' => 'var(--danger-color)',
                'warning' => 'var(--warning-color)',
                'success' => 'var(--success-color)',
                default => 'var(--primary-color)'
            };
            $icon = match($note->type) {
                'danger' => 'fa-solid fa-circle-exclamation',
                'warning' => 'fa-solid fa-triangle-exclamation',
                'success' => 'fa-solid fa-circle-check',
                default => 'fa-solid fa-bell'
            };
        @endphp
        <div style="padding: 1.25rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); border-right: 5px solid {{ $borderColor }}; {{ $note->is_read ? 'opacity: 0.75; background: #fafafa;' : 'background: #ffffff; box-shadow: var(--shadow-sm);' }}">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                <div style="display: flex; gap: 1rem; align-items: flex-start;">
                    <div style="color: {{ $borderColor }}; font-size: 1.4rem; margin-top: 0.2rem;">
                        <i class="{{ $icon }}"></i>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                            <h4 style="margin: 0; font-size: 1.05rem; color: var(--text-main);">{{ $note->title }}</h4>
                            @if(!$note->is_read)
                            <span class="badge badge-danger" style="font-size: 0.7rem; padding: 2px 6px;">جديد</span>
                            @endif
                        </div>
                        <p style="margin: 0; color: var(--text-color); font-size: 0.95rem; line-height: 1.5;">{{ $note->message }}</p>
                        
                        @if($note->equipment)
                        <div style="margin-top: 0.75rem;">
                            @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                            <a href="{{ route('equipment.show', $note->equipment) }}" class="btn btn-outline btn-sm" style="font-size: 0.8rem; padding: 0.25rem 0.6rem;">
                                <i class="fa-solid fa-microscope"></i> عرض الجهاز: {{ $note->equipment->name }}
                            </a>
                            @else
                            <span style="font-size: 0.85rem; color: var(--text-muted); background: var(--bg-color); padding: 3px 8px; border-radius: 4px;">
                                <i class="fa-solid fa-microscope"></i> الجهاز: {{ $note->equipment->name }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <div style="text-align: left; min-width: 110px;">
                    <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">
                        {{ $note->created_at->diffForHumans() }}
                    </span>
                    @if(!$note->is_read)
                    <form action="{{ url('notifications/'.$note->id.'/read') }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-outline btn-sm" style="font-size: 0.75rem; padding: 2px 8px;">
                            <i class="fa-solid fa-check"></i> كمقروء
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center" style="padding: 3rem; color: var(--text-muted);">
            <i class="fa-regular fa-bell-slash" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p style="font-size: 1.1rem; margin: 0;">لا توجد أي إشعارات حالياً.</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div style="margin-top: 1.5rem;">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
