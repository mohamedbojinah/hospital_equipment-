@extends('layouts.app')

@section('title', 'تاريخ الصيانة')
@section('header', 'تاريخ صيانة الجهاز: ' . $equipment->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">سجل الصيانة</h3>
        <a href="{{ route('maintenance.create', ['equipment_id' => $equipment->id]) }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> طلب صيانة جديد
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>م</th>
                    <th>النوع</th>
                    <th>تاريخ الطلب</th>
                    <th>تاريخ الانتهاء</th>
                    <th>القائم بالصيانة</th>
                    <th>التكلفة</th>
                    <th>الحالة</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($record->type == 'preventive') دورية 
                        @elseif($record->type == 'corrective') تصحيحية 
                        @else طارئة @endif
                    </td>
                    <td>{{ $record->created_at->format('Y-m-d') }}</td>
                    <td>{{ $record->completed_at ? $record->completed_at->format('Y-m-d') : '-' }}</td>
                    <td>{{ $record->performer->name ?? 'مجهول' }}</td>
                    <td>{{ $record->cost ? $record->cost . ' د.ل' : '-' }}</td>
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
                    <td colspan="8" class="text-center">لا توجد سجلات صيانة لهذه الجهاز.</td>
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
