@extends('layouts.auth')

@section('content')
<div class="card auth-card">
    <div class="auth-logo">
        <i class="fa-solid fa-hospital"></i>
    </div>
    <h2 class="auth-title">تسجيل الدخول للنظام</h2>

    @if ($errors->any())
        <div class="alert alert-danger" style="text-align: right;">
            <ul style="margin: 0; padding-right: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" style="text-align: right;">
        @csrf
        
        <div class="form-group">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="name@hospital.com">
        </div>

        <div class="form-group">
            <label for="password" class="form-label">كلمة المرور</label>
            <input id="password" type="password" class="form-control" name="password" required>
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">تذكرني</label>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
            دخول <i class="fa-solid fa-arrow-left"></i>
        </button>
    </form>
</div>
@endsection
