@extends('layouts.app')

@section('title', 'لوحة تحكم المشرف')
@section('header', 'المهام والبلاغات المعلقة')

@section('content')
<div class="grid grid-cols-2">
    <div class="card" style="border-right: 4px solid var(--primary-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">إجمالي الأجهزة</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;">{{ $stats['total_equipment'] ?? 0 }}</div>
    </div>
    
    <div class="card" style="border-right: 4px solid var(--warning-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">بلاغات قيد الانتظار</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem; color: var(--warning-color);">{{ $stats['pending_tickets'] ?? 0 }}</div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">بلاغات بانتظار الصيانة والتوجيه</h3>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>رقم البلاغ</th>
                    <th>الجهاز</th>
                    <th>مقدم البلاغ</th>
                    <th>الأولوية</th>
                    <th>تاريخ البلاغ</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingTickets as $ticket)
                <tr>
                    <td>#{{ $ticket->id }}</td>
                    <td>{{ $ticket->equipment->name ?? 'غير محدد' }}</td>
                    <td>{{ $ticket->reporter->name ?? 'مجهول' }}</td>
                    <td>
                        @if($ticket->priority == 'high') <span class="badge" style="background: var(--danger-color); color: white;">عالية</span>
                        @elseif($ticket->priority == 'medium') <span class="badge badge-warning">متوسطة</span>
                        @else <span class="badge badge-info">عادية</span> @endif
                    </td>
                    <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-primary btn-sm">فتح البلاغ للصيانة</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">لا توجد بلاغات معلقة حالياً. أحسنت!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
