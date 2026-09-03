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

    <div class="grid grid-cols-2" style="margin-bottom: 2rem;">
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">الرقم التسلسلي</p>
            <p style="font-weight: 500;">{{ $equipment->serial_number }}</p>
        </div>
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">الموقع</p>
            <p style="font-weight: 500;">{{ $equipment->location }}</p>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <a href="{{ route('maintenance.create', ['equipment_id' => $equipment->id]) }}" class="btn btn-secondary" style="width: 100%;">
            <i class="fa-solid fa-screwdriver-wrench"></i> تسجيل صيانة لهذه الآلة
        </a>
        
        <a href="{{ route('dashboard') }}" class="btn btn-outline" style="width: 100%;">
            <i class="fa-solid fa-house"></i> العودة للرئيسية
        </a>
    </div>
</div>
@endsection
