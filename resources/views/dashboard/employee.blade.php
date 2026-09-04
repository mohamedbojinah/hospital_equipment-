@extends('layouts.app')

@section('title', 'لوحة تحكم المستخدم')
@section('header', 'مرحباً، ' . auth()->user()->name)

@section('content')
<div class="grid grid-cols-3">
    <div class="card" style="border-right: 4px solid var(--primary-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">إجمالي البلاغات المقدمة</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;">{{ $stats['total_tickets'] ?? 0 }}</div>
    </div>
    
    <div class="card" style="border-right: 4px solid var(--warning-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">بلاغات قيد المعالجة</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem; color: var(--warning-color);">{{ $stats['open_tickets'] ?? 0 }}</div>
    </div>

    <div class="card" style="border-right: 4px solid var(--success-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">بلاغات مغلقة (تم حلها)</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem; color: var(--success-color);">{{ $stats['resolved_tickets'] ?? 0 }}</div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title">بلاغاتي الأخيرة</h3>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> فتح بلاغ جديد</a>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>رقم البلاغ</th>
                    <th>الجهاز</th>
                    <th>العنوان</th>
                    <th>الأولوية</th>
                    <th>الحالة</th>
                    <th>تاريخ البلاغ</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myTickets as $ticket)
                <tr>
                    <td>#{{ $ticket->id }}</td>
                    <td>{{ $ticket->equipment->name ?? 'غير محدد' }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>
                        @if($ticket->priority == 'high') <span class="badge" style="background: var(--danger-color); color: white;">عالية</span>
                        @elseif($ticket->priority == 'medium') <span class="badge badge-warning">متوسطة</span>
                        @else <span class="badge badge-info">عادية</span> @endif
                    </td>
                    <td>
                        @if($ticket->status == 'open') <span class="badge" style="background: var(--danger-color); color: white;">مفتوحة</span>
                        @elseif($ticket->status == 'in_progress') <span class="badge badge-warning">قيد المعالجة</span>
                        @else <span class="badge badge-success">مغلقة</span> @endif
                    </td>
                    <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-outline btn-sm">التفاصيل</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">لم تقم بتقديم أي بلاغات بعد.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
