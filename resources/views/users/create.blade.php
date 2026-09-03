@extends('layouts.app')

@section('title', 'إضافة مستخدم جديد')
@section('header', 'إضافة مستخدم جديد للنظام')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">إضافة مستخدم</h3>
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

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name" class="form-label">الاسم الكامل *</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">البريد الإلكتروني *</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="role" class="form-label">الدور (الصلاحية) *</label>
            <select name="role" id="role" class="form-control" required>
                <option value="">-- اختر الدور --</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>مدير نظام</option>
                <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>مشرف صيانة</option>
                <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>موظف مستشفى</option>
            </select>
        </div>

        <div class="form-group">
            <label for="phone" class="form-label">رقم الهاتف</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
        </div>

        <div class="form-group">
            <label for="password" class="form-label">كلمة المرور *</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور *</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group" style="display: flex; gap: 0.5rem; align-items: center; margin-top: 1rem;">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width:1.2rem; height:1.2rem;">
            <label for="is_active" class="form-label" style="margin:0;">الحساب مفعل (يمكنه تسجيل الدخول فوراً)</label>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save"></i> حفظ وإضافة
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-times"></i> إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
