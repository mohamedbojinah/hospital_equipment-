@extends('layouts.app')

@section('title', 'عروض الأسعار وطلبات الشراء')
@section('header', 'إدارة عروض الأسعار (Quotations)')

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title">قائمة عروض الأسعار</h3>
        <a href="{{ route('quotations.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> تقديم عرض سعر جديد
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العنوان</th>
                    <th>الجهاز المرتبط</th>
                    <th>الإجمالي</th>
                    <th>الحالة</th>
                    <th>مقدم الطلب</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $quotation)
                <tr>
                    <td>#{{ $quotation->id }}</td>
                    <td>{{ $quotation->title }}</td>
                    <td>
                        @if($quotation->equipment)
                            <a href="{{ route('equipment.show', $quotation->equipment) }}">{{ $quotation->equipment->name }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td style="font-weight: bold; color: var(--danger-color);">{{ number_format($quotation->total_amount, 2) }} د.ل</td>
                    <td>
                        @if($quotation->status == 'pending') <span class="badge badge-warning">بانتظار الاعتماد</span>
                        @elseif($quotation->status == 'approved') <span class="badge badge-success">معتمد مالياً</span>
                        @else <span class="badge badge-danger">مرفوض</span> @endif
                    </td>
                    <td>{{ $quotation->submitter->name ?? '-' }}</td>
                    <td>{{ $quotation->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('quotations.show', $quotation) }}" class="btn btn-sm btn-outline">
                            <i class="fa-solid fa-eye"></i> تفاصيل
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">لا توجد عروض أسعار حالياً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $quotations->links() }}
    </div>
</div>
@endsection
