@extends('layouts.app')

@section('title', 'نظام البلاغات (Tickets)')
@section('header', 'نظام البلاغات والبلاغات')

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title">جميع البلاغات</h3>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> إنشاء بلاغ جديدة
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>رقم البلاغ</th>
                    <th>الجهاز</th>
                    <th>عنوان البلاغ</th>
                    <th>الأولوية</th>
                    <th>الحجهاز</th>
                    <th>مقدم البلاغ</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td>#{{ $ticket->id }}</td>
                    <td>{{ $ticket->equipment->name ?? '-' }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>
                        @if($ticket->priority == 'critical') <span class="badge badge-danger">حرجة</span>
                        @elseif($ticket->priority == 'high') <span class="badge badge-warning">عالية</span>
                        @elseif($ticket->priority == 'medium') <span class="badge badge-info">متوسطة</span>
                        @else <span class="badge badge-success">منخفضة</span> @endif
                    </td>
                    <td>
                        @if($ticket->status == 'open') <span class="badge badge-warning">مفتوحة</span>
                        @elseif($ticket->status == 'in_progress') <span class="badge badge-primary">قيد المعالجة</span>
                        @elseif($ticket->status == 'resolved') <span class="badge badge-success">تم الحل</span>
                        @else <span class="badge badge-secondary">مغلقة</span> @endif
                    </td>
                    <td>{{ $ticket->reporter->name ?? '-' }}</td>
                    <td>{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline">
                            <i class="fa-solid fa-eye"></i> عرض
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">لا توجد بلاغات حالياً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
