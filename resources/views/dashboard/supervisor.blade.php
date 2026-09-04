@extends('layouts.app')

@section('title', 'لوحة تحكم المشرف')
@section('header', 'المهام المعلقة')

@section('content')
<div class="grid grid-cols-2">
    <div class="card" style="border-right: 4px solid var(--primary-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">إجمالي الأجهزة</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;">{{ $stats['total_equipment'] }}</div>
    </div>
    
    <div class="card" style="border-right: 4px solid var(--warning-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">طلبات صيانة معلقة</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem; color: var(--warning-color);">{{ $stats['pending_maintenance'] }}</div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">طلبات بانتظار الاعتماد</h3>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الجهاز</th>
                    <th>نوع الصيانة</th>
                    <th>القائم بالصيانة</th>
                    <th>التاريخ</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingRecords as $record)
                <tr>
                    <td>{{ $record->equipment->name ?? 'محذوف' }}</td>
                    <td>
                        @if($record->type == 'preventive') دورية 
                        @elseif($record->type == 'corrective') تصحيحية 
                        @else طارئة @endif
                    </td>
                    <td>{{ $record->performer->name ?? 'مجهول' }}</td>
                    <td>{{ $record->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('maintenance.show', $record) }}" class="btn btn-primary btn-sm">مراجعة</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">لا توجد طلبات معلقة حالياً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
