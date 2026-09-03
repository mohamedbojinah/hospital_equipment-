@extends('layouts.app')

@section('title', 'تقرير حالة الآلات')
@section('header', 'تقرير حالة الآلات والمعدات')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">نظرة عامة على حالة الآلات</h3>
        <button onclick="window.print()" class="btn btn-outline">
            <i class="fa-solid fa-print"></i> طباعة التقرير
        </button>
    </div>

    <div class="grid grid-cols-3" style="margin-bottom: 2rem;">
        <div class="card" style="border-right: 4px solid var(--secondary-dark); margin-bottom: 0;">
            <h4 style="color: var(--text-muted); font-size: 0.9rem;">الآلات النشطة</h4>
            <div style="font-size: 2rem; font-weight: 700; color: var(--secondary-dark);">{{ $active }}</div>
        </div>
        
        <div class="card" style="border-right: 4px solid var(--warning-color); margin-bottom: 0;">
            <h4 style="color: var(--text-muted); font-size: 0.9rem;">الآلات في الصيانة</h4>
            <div style="font-size: 2rem; font-weight: 700; color: var(--warning-color);">{{ $maintenance }}</div>
        </div>
        
        <div class="card" style="border-right: 4px solid var(--danger-color); margin-bottom: 0;">
            <h4 style="color: var(--text-muted); font-size: 0.9rem;">الآلات المعطلة</h4>
            <div style="font-size: 2rem; font-weight: 700; color: var(--danger-color);">{{ $inactive }}</div>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title" style="margin-bottom: 1rem;">توزيع الآلات حسب الأقسام</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>القسم</th>
                        <th>عدد الآلات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr>
                        <td>{{ $dept->department }}</td>
                        <td>{{ $dept->total }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center">لا توجد بيانات متاحة.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar, .topbar, .btn { display: none !important; }
        .main-content { margin: 0; padding: 0; }
        .card { box-shadow: none; border: 1px solid #ccc; }
    }
</style>
@endsection
