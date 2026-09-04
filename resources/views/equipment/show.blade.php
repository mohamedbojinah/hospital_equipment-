@extends('layouts.app')

@section('title', 'تفاصيل الجهاز')
@section('header', 'تفاصيل الجهاز: ' . $equipment->name)

@section('content')
<div class="grid grid-cols-2" style="margin-bottom: 2rem; gap: 2rem;">
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header">
            <h3 class="card-title">المعلومات الأساسية</h3>
            <span class="badge {{ $equipment->status == 'active' ? 'badge-success' : ($equipment->status == 'maintenance' ? 'badge-warning' : 'badge-danger') }}">
                {{ $equipment->status == 'active' ? 'نشطة' : ($equipment->status == 'maintenance' ? 'في الصيانة' : 'معطلة') }}
            </span>
        </div>
        
        <table class="table" style="margin-bottom: 0;">
            <tr>
                <th style="width: 40%; background: none;">الاسم</th>
                <td>{{ $equipment->name }}</td>
            </tr>
            <tr>
                <th style="background: none;">الرقم التسلسلي</th>
                <td>{{ $equipment->serial_number }}</td>
            </tr>
            <tr>
                <th style="background: none;">النوع</th>
                <td>{{ $equipment->type->name ?? '-' }}</td>
            </tr>
            <tr>
                <th style="background: none;">القسم</th>
                <td>{{ $equipment->department }}</td>
            </tr>
            <tr>
                <th style="background: none;">الموقع</th>
                <td>{{ $equipment->location }}</td>
            </tr>
        </table>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <div class="card-header">
            <h3 class="card-title">معلومات الشراء والصيانة</h3>
        </div>
        
        <table class="table" style="margin-bottom: 0;">
            <tr>
                <th style="width: 40%; background: none;">تاريخ الشراء</th>
                <td>{{ $equipment->purchase_date ? $equipment->purchase_date->format('Y-m-d') : 'غير محدد' }}</td>
            </tr>
            <tr>
                <th style="background: none;">انتهاء الضمان</th>
                <td>{{ $equipment->warranty_expiry ? $equipment->warranty_expiry->format('Y-m-d') : 'غير محدد' }}</td>
            </tr>
            <tr>
                <th style="background: none;">تمت الإضافة بواسطة</th>
                <td>{{ $equipment->creator->name ?? 'مجهول' }}</td>
            </tr>
            <tr>
                <th style="background: none;">تاريخ الإضافة</th>
                <td>{{ $equipment->created_at->format('Y-m-d') }}</td>
            </tr>
        </table>
        
        @if($equipment->notes)
        <div style="margin-top: 1.5rem;">
            <p style="color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 700;">ملاحظات إضافية:</p>
            <p style="background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md);">{{ $equipment->notes }}</p>
        </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">الإجراءات والعمليات</h3>
    </div>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('equipment.qr', $equipment) }}" class="btn btn-outline">
            <i class="fa-solid fa-qrcode"></i> عرض QR Code
        </a>
        <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-warning" style="background: var(--warning-light); color: #b45309; border:none;">
            <i class="fa-solid fa-edit"></i> تعديل بيانات الجهاز
        </a>
        <a href="{{ route('maintenance.create', ['equipment_id' => $equipment->id]) }}" class="btn btn-secondary">
            <i class="fa-solid fa-screwdriver-wrench"></i> طلب صيانة جديدة
        </a>
        <a href="{{ url('maintenance/'.$equipment->id.'/history') }}" class="btn btn-primary">
            <i class="fa-solid fa-clock-rotate-left"></i> سجل الصيانة الكامل
        </a>
    </div>
</div>
@endsection
