@extends('layouts.app')

@section('title', 'ملخص الصيانة')
@section('header', 'تقرير ملخص الصيانة')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">ملخص إحصائيات الصيانة</h3>
        <button onclick="window.print()" class="btn btn-outline">
            <i class="fa-solid fa-print"></i> طباعة التقرير
        </button>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>نوع الصيانة</th>
                    <th>إجمالي العمليات</th>
                    <th>إجمالي التكلفة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                <tr>
                    <td>
                        @if($rec->type == 'preventive') دورية 
                        @elseif($rec->type == 'corrective') تصحيحية 
                        @else طارئة @endif
                    </td>
                    <td>{{ $rec->total }}</td>
                    <td>{{ $rec->total_cost ? $rec->total_cost . ' دينار' : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">لا توجد بيانات متاحة.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <h3 class="card-title" style="margin-bottom: 1rem;">أحدث عمليات الصيانة المكتملة</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الآلة</th>
                    <th>نوع الصيانة</th>
                    <th>القائم بالصيانة</th>
                    <th>تاريخ الانتهاء</th>
                    <th>التكلفة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentCompleted as $rec)
                <tr>
                    <td>{{ $rec->equipment->name ?? '-' }}</td>
                    <td>
                        @if($rec->type == 'preventive') دورية 
                        @elseif($rec->type == 'corrective') تصحيحية 
                        @else طارئة @endif
                    </td>
                    <td>{{ $rec->performer->name ?? '-' }}</td>
                    <td>{{ $rec->completed_at ? $rec->completed_at->format('Y-m-d') : '-' }}</td>
                    <td>{{ $rec->cost ? $rec->cost . ' دينار' : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">لا توجد سجلات مكتملة حديثاً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
