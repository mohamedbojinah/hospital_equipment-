@extends('layouts.scan')

@section('content')
<div class="card">
    <div class="equipment-header">
        <i class="fa-solid fa-microscope equipment-icon"></i>
        <h2 style="margin-bottom: 0.5rem;">{{ $equipment->name }}</h2>
        <span class="badge {{ $equipment->status == 'active' ? 'badge-success' : ($equipment->status == 'maintenance' ? 'badge-warning' : 'badge-danger') }}">
            {{ $equipment->status == 'active' ? 'نشطة' : ($equipment->status == 'maintenance' ? 'في الصيانة' : 'معطلة') }}
        </span>
    </div>

    <div class="grid grid-cols-2" style="margin-bottom: 1.5rem; gap: 1rem;">
        <div>
            <p style="color: var(--text-muted); font-size: 0.85rem;">الرقم التسلسلي</p>
            <p style="font-weight: bold;">{{ $equipment->serial_number }}</p>
        </div>
        <div>
            <p style="color: var(--text-muted); font-size: 0.85rem;">القسم والموقع</p>
            <p style="font-weight: bold;">{{ $equipment->department }} - {{ $equipment->location }}</p>
        </div>
        <div>
            <p style="color: var(--text-muted); font-size: 0.85rem;">الشركة المصنعة والموديل</p>
            <p style="font-weight: bold;">{{ $equipment->manufacturer ?? 'غير محدد' }} ({{ $equipment->model_number ?? '-' }})</p>
        </div>
        <div>
            <p style="color: var(--text-muted); font-size: 0.85rem;">تاريخ التشغيل (العمر)</p>
            <p style="font-weight: bold;">
                @if($equipment->operating_date)
                    {{ $equipment->operating_date->format('Y-m-d') }} 
                    <span style="color:var(--primary-color);">({{ $equipment->operating_date->diffInYears(now()) }} سنوات)</span>
                @else
                    غير محدد
                @endif
            </p>
        </div>
        <div>
            <p style="color: var(--text-muted); font-size: 0.85rem;">درجة الخطورة</p>
            <p style="font-weight: bold;">
                @if($equipment->risk_level == 'high') <span class="badge badge-danger">عالي الخطورة</span>
                @elseif($equipment->risk_level == 'medium') <span class="badge badge-warning">متوسط الخطورة</span>
                @else <span class="badge badge-success">منخفض الخطورة</span> @endif
            </p>
        </div>
        <div>
            <p style="color: var(--text-muted); font-size: 0.85rem;">دليل الاستخدام (Manual)</p>
            @if($equipment->manual_file_path)
                <a href="{{ asset('storage/'.$equipment->manual_file_path) }}" target="_blank" class="btn btn-outline btn-sm">تحميل PDF</a>
            @else
                <span class="text-muted">غير متوفر</span>
            @endif
        </div>
    </div>

    <hr style="margin-bottom: 1.5rem;">
    
    <h3 style="margin-bottom: 1rem; color: var(--primary-dark);">سجل الصيانة المكتملة</h3>
    @if($equipment->maintenanceRecords->where('status', 'completed')->count() > 0)
        @foreach($equipment->maintenanceRecords->where('status', 'completed') as $record)
        <div style="background: var(--bg-color); padding: 1rem; border-radius: 6px; margin-bottom: 1rem; border-right: 3px solid var(--success-color);">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <strong>تاريخ الصيانة: {{ $record->completed_at ? $record->completed_at->format('Y-m-d') : '' }}</strong>
                <span class="badge badge-success">{{ $record->type == 'preventive' ? 'دورية' : 'تصحيحية' }}</span>
            </div>
            <p style="font-size: 0.9rem; margin-bottom: 0.5rem;"><strong>تقرير المهندس:</strong> {{ $record->engineer_report ?? 'لا يوجد تقرير' }}</p>
            <p style="font-size: 0.9rem;"><strong>القطع التي تم تغييرها:</strong> {{ $record->spare_parts_changed ?? 'لم يتم تغيير قطع' }}</p>
        </div>
        @endforeach
    @else
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">لا يوجد سجل صيانات سابقة.</p>
    @endif

    <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1.5rem;">
        <a href="{{ route('maintenance.create', ['equipment_id' => $equipment->id]) }}" class="btn btn-secondary" style="width: 100%;">
            <i class="fa-solid fa-screwdriver-wrench"></i> تقديم تقرير صيانة جديد
        </a>
        
        <a href="{{ route('tickets.create', ['equipment_id' => $equipment->id]) }}" class="btn btn-warning" style="width: 100%; border:none; background: #fbbf24; color: #92400e;">
            <i class="fa-solid fa-triangle-exclamation"></i> الإبلاغ عن عطل (Ticket)
        </a>
        
        <a href="{{ route('dashboard') }}" class="btn btn-outline" style="width: 100%;">
            <i class="fa-solid fa-house"></i> العودة للرئيسية
        </a>
    </div>
</div>
@endsection
