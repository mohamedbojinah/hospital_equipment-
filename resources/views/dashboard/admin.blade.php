@extends('layouts.app')

@section('title', 'لوحة تحكم المدير')
@section('header', 'نظرة عامة')

@section('content')
<div class="grid grid-cols-4">
    <div class="card" style="border-right: 4px solid var(--primary-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">إجمالي المستخدمين</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;">{{ $stats['total_users'] }}</div>
    </div>
    
    <div class="card" style="border-right: 4px solid var(--secondary-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">إجمالي الآلات</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;">{{ $stats['total_equipment'] }}</div>
    </div>
    
    <div class="card" style="border-right: 4px solid var(--secondary-dark);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">الآلات النشطة</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem; color: var(--secondary-dark);">{{ $stats['active_equipment'] }}</div>
    </div>
    
    <div class="card" style="border-right: 4px solid var(--warning-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">آلات في الصيانة</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem; color: var(--warning-color);">{{ $stats['maintenance_equipment'] }}</div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">آخر عمليات الصيانة</h3>
        <a href="{{ route('maintenance.index') }}" class="btn btn-outline btn-sm">عرض الكل</a>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الآلة</th>
                    <th>نوع الصيانة</th>
                    <th>القائم بالصيانة</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentMaintenance as $record)
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
                        @if($record->status == 'pending') <span class="badge badge-warning">قيد الانتظار</span>
                        @elseif($record->status == 'in_progress') <span class="badge badge-info">جاري العمل</span>
                        @elseif($record->status == 'completed') <span class="badge badge-success">مكتملة</span>
                        @else <span class="badge badge-success">معتمدة</span> @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">لا توجد سجلات صيانة حديثة.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
