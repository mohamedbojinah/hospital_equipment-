@extends('layouts.app')

@section('title', 'سجلات الصيانة')
@section('header', 'سجلات طلبات الصيانة')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">كافة السجلات</h3>
        <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> طلب صيانة جديد
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>م</th>
                    <th>الجهاز</th>
                    <th>نوع الصيانة</th>
                    <th>القائم بالصيانة</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr>
                    <td>{{ $loop->iteration }}</td>
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
                    <td>
                        <a href="{{ route('maintenance.show', $record) }}" class="btn btn-outline btn-sm">عرض</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">لا توجد سجلات صيانة.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.5rem;">
        {{ $records->links() }}
    </div>
</div>
@endsection
