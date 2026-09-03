@extends('layouts.app')

@section('title', 'تعديل الآلة')
@section('header', 'تعديل الآلة: ' . $equipment->name)

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">تعديل بيانات الآلة</h3>
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

    <form action="{{ route('equipment.update', $equipment) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-2">
            <div class="form-group">
                <label for="name" class="form-label">اسم الآلة *</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $equipment->name) }}" required>
            </div>
            
            <div class="form-group">
                <label for="serial_number" class="form-label">الرقم التسلسلي *</label>
                <input type="text" name="serial_number" id="serial_number" class="form-control" value="{{ old('serial_number', $equipment->serial_number) }}" required>
            </div>
        </div>

        <div class="grid grid-cols-2">
            <div class="form-group">
                <label for="equipment_type_id" class="form-label">نوع الآلة *</label>
                <select name="equipment_type_id" id="equipment_type_id" class="form-control" required>
                    <option value="">-- اختر النوع --</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('equipment_type_id', $equipment->equipment_type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="status" class="form-label">الحالة *</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active" {{ old('status', $equipment->status) == 'active' ? 'selected' : '' }}>نشطة</option>
                    <option value="maintenance" {{ old('status', $equipment->status) == 'maintenance' ? 'selected' : '' }}>في الصيانة</option>
                    <option value="inactive" {{ old('status', $equipment->status) == 'inactive' ? 'selected' : '' }}>معطلة / خارج الخدمة</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2">
            <div class="form-group">
                <label for="department" class="form-label">القسم *</label>
                <input type="text" name="department" id="department" class="form-control" value="{{ old('department', $equipment->department) }}" required placeholder="مثال: قسم الأشعة">
            </div>
            
            <div class="form-group">
                <label for="location" class="form-label">الموقع بدقة *</label>
                <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $equipment->location) }}" required placeholder="مثال: الغرفة 102 - الطابق الأول">
            </div>
        </div>

        <div class="grid grid-cols-2">
            <div class="form-group">
                <label for="purchase_date" class="form-label">تاريخ الشراء (اختياري)</label>
                <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ old('purchase_date', $equipment->purchase_date ? $equipment->purchase_date->format('Y-m-d') : '') }}">
            </div>
            
            <div class="form-group">
                <label for="warranty_expiry" class="form-label">تاريخ انتهاء الضمان (اختياري)</label>
                <input type="date" name="warranty_expiry" id="warranty_expiry" class="form-control" value="{{ old('warranty_expiry', $equipment->warranty_expiry ? $equipment->warranty_expiry->format('Y-m-d') : '') }}">
            </div>
        </div>

        <div class="form-group">
            <label for="notes" class="form-label">ملاحظات إضافية</label>
            <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes', $equipment->notes) }}</textarea>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> حفظ التعديلات
            </button>
            <a href="{{ route('equipment.show', $equipment) }}" class="btn btn-outline">
                <i class="fa-solid fa-times"></i> إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
