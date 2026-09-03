@extends('layouts.app')

@section('title', 'QR Code للآلة')
@section('header', 'رمز الاستجابة السريعة (QR Code)')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto; text-align: center;">
    <div class="card-header" style="justify-content: center;">
        <h3 class="card-title">QR Code: {{ $equipment->name }}</h3>
    </div>
    
    <div style="margin: 2rem 0;">
        {!! $qrCode !!}
    </div>
    
    <p style="color: var(--text-muted); margin-bottom: 2rem;">قم بمسح هذا الرمز باستخدام كاميرا الهاتف للوصول السريع لصفحة الآلة.</p>
    
    <div style="display: flex; justify-content: center; gap: 1rem;">
        <a href="{{ route('equipment.print-qr', $equipment) }}" target="_blank" class="btn btn-primary">
            <i class="fa-solid fa-print"></i> طباعة الرمز
        </a>
        <a href="{{ route('equipment.show', $equipment) }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> عودة لتفاصيل الآلة
        </a>
    </div>
</div>
@endsection
