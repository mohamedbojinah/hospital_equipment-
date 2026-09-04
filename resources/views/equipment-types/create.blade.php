@extends('layouts.app')

@section('title', 'إضافة نوع جهاز')
@section('header', 'إضافة نوع جهاز جديد')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">إضافة نوع جديد</h3>
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

    <form action="{{ route('equipment-types.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name" class="form-label">اسم النوع *</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">الوصف</label>
            <textarea name="description" id="description" rows="3" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="icon" class="form-label">أيقونة (اختياري - من FontAwesome)</label>
            <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon') }}" placeholder="مثال: fa-microscope">
            <small style="color: var(--text-muted);">اكتب اسم الكلاس الخاص بالأيقونة من FontAwesome, مثال: fa-heart-pulse</small>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> حفظ وإضافة
            </button>
            <a href="{{ route('equipment-types.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-times"></i> إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
