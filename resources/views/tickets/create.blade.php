@extends('layouts.app')

@section('title', 'فتح تذكرة جديدة')
@section('header', 'الإبلاغ عن عطل (تذكرة صيانة)')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">نموذج الإبلاغ عن عطل</h3>
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

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="equipment_id" class="form-label">الجهاز المعطل *</label>
            <select name="equipment_id" id="equipment_id" class="form-control" required>
                <option value="">-- اختر الجهاز --</option>
                @foreach($equipment as $eq)
                    <option value="{{ $eq->id }}" {{ (old('equipment_id') == $eq->id || $selected_equipment_id == $eq->id) ? 'selected' : '' }}>
                        {{ $eq->name }} ({{ $eq->serial_number }}) - {{ $eq->department }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="title" class="form-label">عنوان البلاغ (مختصر) *</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required placeholder="مثال: توقف الشاشة عن العمل">
        </div>

        <div class="form-group">
            <label for="priority" class="form-label">الأولوية (مدى التأثير على العمل) *</label>
            <select name="priority" id="priority" class="form-control" required>
                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>منخفضة (لا يعيق العمل)</option>
                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>متوسطة (تأثير جزئي)</option>
                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>عالية (الجهاز متوقف)</option>
                <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>حرجة (تأثير على حياة مريض / توقف قسم)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">تفاصيل العطل بدقة *</label>
            <textarea name="description" id="description" rows="5" class="form-control" required placeholder="اشرح ما حدث بالضبط ومتى بدأ العطل...">{{ old('description') }}</textarea>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-paper-plane"></i> إرسال البلاغ
            </button>
            <a href="{{ route('tickets.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-times"></i> إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
