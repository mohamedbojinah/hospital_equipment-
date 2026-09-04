@extends('layouts.app')

@section('title', 'طلب صيانة جديد')
@section('header', 'تسجيل طلب صيانة لجهاز')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">طلب صيانة جديد</h3>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-right: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('maintenance.index') }}" method="POST">
        @csrf
        
        @if(isset($ticketId))
            <input type="hidden" name="ticket_id" value="{{ $ticketId }}">
            <div class="alert alert-info">
                <i class="fa-solid fa-circle-info"></i> أنت تقوم بتسجيل صيانة لإغلاق البلاغ رقم #{{ $ticketId }}
            </div>
        @endif

        <div class="form-group">
            <label for="equipment_id" class="form-label">الجهاز *</label>
            @if(isset($equipment))
                <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                <input type="text" class="form-control" value="{{ $equipment->name }} (SN: {{ $equipment->serial_number }})" readonly style="background-color: #f1f5f9;">
            @elseif(isset($selectedEquipmentId))
                <input type="hidden" name="equipment_id" value="{{ $selectedEquipmentId }}">
                <select class="form-control" disabled>
                    @foreach(\App\Models\Equipment::all() as $eq)
                        <option value="{{ $eq->id }}" {{ $eq->id == $selectedEquipmentId ? 'selected' : '' }}>
                            {{ $eq->name }} (SN: {{ $eq->serial_number }})
                        </option>
                    @endforeach
                </select>
            @else
                <select name="equipment_id" id="equipment_id" class="form-control" required>
                    <option value="">-- اختر الجهاز --</option>
                    @foreach(\App\Models\Equipment::all() as $eq)
                        <option value="{{ $eq->id }}" {{ old('equipment_id') == $eq->id ? 'selected' : '' }}>
                            {{ $eq->name }} (SN: {{ $eq->serial_number }})
                        </option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="form-group">
            <label for="type" class="form-label">نوع الصيانة *</label>
            <select name="type" id="type" class="form-control" required>
                <option value="preventive" {{ old('type') == 'preventive' ? 'selected' : '' }}>صيانة دورية (وقائية)</option>
                <option value="corrective" {{ old('type') == 'corrective' ? 'selected' : '' }}>صيانة تصحيحية (إصلاح عطل)</option>
                <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>صيانة طارئة</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">وصف المشكلة أو حجهاز الجهاز *</label>
            <textarea name="description" id="description" rows="5" class="form-control" required placeholder="يرجى كتابة تفاصيل المشكلة أو الفحص الذي تم...">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2">
            <div class="form-group">
                <label for="cost" class="form-label">التكلفة المتوقعة (إن وجدت)</label>
                <input type="number" step="0.01" name="cost" id="cost" class="form-control" value="{{ old('cost') }}">
            </div>
            
            <div class="form-group">
                <label for="next_maintenance_date" class="form-label">تاريخ الصيانة القادمة (اختياري)</label>
                <input type="date" name="next_maintenance_date" id="next_maintenance_date" class="form-control" value="{{ old('next_maintenance_date') }}">
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> تسجيل الطلب
            </button>
            <a href="javascript:history.back()" class="btn btn-outline">
                <i class="fa-solid fa-times"></i> إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
