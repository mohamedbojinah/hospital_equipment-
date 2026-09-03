@extends('layouts.app')

@section('title', 'لوحة تحكم الموظف')
@section('header', 'سجل المهام الخاصة بي')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">آخر المهام التي قمت بها</h3>
        <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> تسجيل صيانة جديدة
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الآلة</th>
                    <th>نوع الصيانة</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                    <th>تفاصيل</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myRecords as $record)
                <tr>
                    <td>{{ $record->equipment->name ?? 'محذوف' }}</td>
                    <td>
                        @if($record->type == 'preventive') دورية 
                        @elseif($record->type == 'corrective') تصحيحية 
                        @else طارئة @endif
                    </td>
                    <td>{{ $record->created_at->format('Y-m-d') }}</td>
                    <td>
                        @if($record->status == 'pending') <span class="badge badge-warning">قيد الانتظار</span>
                        @elseif($record->status == 'in_progress') <span class="badge badge-info">جاري العمل</span>
                        @elseif($record->status == 'completed') <span class="badge badge-success">مكتملة</span>
                        @else <span class="badge badge-success">معتمدة</span> @endif
                    </td>
                    <td>
                        <a href="{{ route('maintenance.show', $record) }}" class="btn btn-outline btn-sm">عرض</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">لم تقم بتسجيل أي عمليات صيانة بعد.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
