@extends('layouts.scan')

@section('content')
<div class="card" style="text-align: center;">
    <div class="equipment-header">
        <i class="fa-solid fa-lock equipment-icon" style="color: var(--text-muted);"></i>
        <h2 style="margin-bottom: 1rem;">يجب تسجيل الدخول</h2>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">عذراً، يجب عليك تسجيل الدخول بحساب موظف المستشفى للوصول إلى تفاصيل الجهاز: <strong style="color: var(--text-main);">{{ $equipment->name }}</strong>.</p>
    </div>

    <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%;">
        <i class="fa-solid fa-right-to-bracket"></i> تسجيل الدخول
    </a>
</div>
@endsection
