@extends('layouts.app')

@section('title', 'إضافة آلة جديدة')
@section('header', 'إضافة آلة أو جهاز جديد')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">بيانات الجهاز (Asset Profile)</h3>
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

    <form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <h4 style="margin-top: 1rem; margin-bottom: 1rem; color: var(--primary-color);">المعلومات الأساسية والتصنيف</h4>
        <div class="grid grid-cols-2">
            <div class="form-group">
                <label for="name" class="form-label">اسم الآلة *</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            
            <div class="form-group">
                <label for="serial_number" class="form-label">الرقم التسلسلي *</label>
                <input type="text" name="serial_number" id="serial_number" class="form-control" value="{{ old('serial_number') }}" required>
            </div>
        </div>

        <div class="grid grid-cols-2">
            <div class="form-group">
                <label for="equipment_type_id" class="form-label">نوع الآلة *</label>
                <select name="equipment_type_id" id="equipment_type_id" class="form-control" required>
                    <option value="">-- اختر النوع --</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('equipment_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="risk_level" class="form-label">درجة الخطورة</label>
                <select name="risk_level" id="risk_level" class="form-control">
                    <option value="">-- غير محدد --</option>
                    <option value="low" {{ old('risk_level') == 'low' ? 'selected' : '' }}>منخفض الخطورة (Low)</option>
                    <option value="medium" {{ old('risk_level') == 'medium' ? 'selected' : '' }}>متوسط الخطورة (Medium)</option>
                    <option value="high" {{ old('risk_level') == 'high' ? 'selected' : '' }}>عالي الخطورة (High)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2">
            <div class="form-group">
                <label for="department" class="form-label">القسم *</label>
                <input type="text" name="department" id="department" class="form-control" value="{{ old('department') }}" required placeholder="مثال: قسم الأشعة">
            </div>
            <div class="form-group">
                <label for="location" class="form-label">الموقع بدقة *</label>
                <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}" required placeholder="مثال: الغرفة 102 - الطابق الأول">
            </div>
        </div>

        <hr style="margin: 2rem 0;">
        <h4 style="margin-bottom: 1rem; color: var(--primary-color);">بيانات التوريد والشركة المصنعة (Vendor & Procurement)</h4>
        <div class="grid grid-cols-2">
            <div class="form-group">
                <label for="manufacturer" class="form-label">الشركة المصنعة (Manufacturer)</label>
                <input type="text" name="manufacturer" id="manufacturer" class="form-control" value="{{ old('manufacturer') }}">
            </div>
            <div class="form-group">
                <label for="model_number" class="form-label">الموديل (Model)</label>
                <input type="text" name="model_number" id="model_number" class="form-control" value="{{ old('model_number') }}">
            </div>
            <div class="form-group">
                <label for="supplier" class="form-label">الشركة الموردة / الوكيل (Supplier)</label>
                <input type="text" name="supplier" id="supplier" class="form-control" value="{{ old('supplier') }}">
            </div>
            <div class="form-group">
                <label for="invoice_number" class="form-label">رقم الفاتورة / العقد</label>
                <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ old('invoice_number') }}">
            </div>
            <div class="form-group">
                <label for="purchase_price" class="form-label">سعر الشراء (Purchase Price)</label>
                <input type="number" step="0.01" name="purchase_price" id="purchase_price" class="form-control" value="{{ old('purchase_price') }}">
            </div>
            <div class="form-group">
                <label for="purchase_date" class="form-label">تاريخ الشراء</label>
                <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ old('purchase_date') }}">
            </div>
            <div class="form-group">
                <label for="warranty_expiry" class="form-label">تاريخ انتهاء الضمان</label>
                <input type="date" name="warranty_expiry" id="warranty_expiry" class="form-control" value="{{ old('warranty_expiry') }}">
            </div>
        </div>

        <hr style="margin: 2rem 0;">
        <h4 style="margin-bottom: 1rem; color: var(--primary-color);">البيانات التشغيلية والفنية (Operational Details)</h4>
        <div class="grid grid-cols-2">
            <div class="form-group">
                <label for="operating_date" class="form-label">تاريخ بدء التشغيل الفعلي (Operating Date)</label>
                <input type="date" name="operating_date" id="operating_date" class="form-control" value="{{ old('operating_date') }}">
            </div>
            <div class="form-group">
                <label for="expected_life_span" class="form-label">العمر الافتراضي بالسنوات (Expected Life Span)</label>
                <input type="number" name="expected_life_span" id="expected_life_span" class="form-control" value="{{ old('expected_life_span') }}">
            </div>
            <div class="form-group">
                <label for="operating_hours" class="form-label">ساعات التشغيل الحالية (Operating Hours)</label>
                <input type="number" name="operating_hours" id="operating_hours" class="form-control" value="{{ old('operating_hours') }}">
            </div>
            <div class="form-group">
                <label for="manual_file" class="form-label">كتالوج الجهاز / دليل الاستخدام (PDF)</label>
                <input type="file" name="manual_file" id="manual_file" class="form-control" accept=".pdf">
            </div>
        </div>

        <div class="form-group">
            <label for="notes" class="form-label">ملاحظات إضافية</label>
            <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> حفظ وإنشاء QR Code
            </button>
            <a href="{{ route('equipment.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-times"></i> إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
