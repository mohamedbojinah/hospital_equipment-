@extends('layouts.app')

@section('title', 'التقرير المالي والتكاليف')
@section('header', 'التقرير المالي والتكاليف')

@section('content')
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h3 class="card-title">إجمالي تكاليف الصيانة في المستشفى</h3>
        <button onclick="window.print()" class="btn btn-outline">
            <i class="fa-solid fa-print"></i> طباعة
        </button>
    </div>
    <div style="font-size: 3rem; font-weight: 800; color: var(--danger-color); text-align: center;">
        {{ number_format($totalCost, 2) }} دينار
    </div>
</div>

<div class="grid grid-cols-2">
    <div class="card">
        <h3 class="card-title" style="margin-bottom: 1.5rem;">التكاليف حسب الأقسام</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>القسم</th>
                        <th>عدد الأجهزة</th>
                        <th>تكلفة الصيانة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($costsByDepartment as $dept)
                    <tr>
                        <td style="font-weight: bold;">{{ $dept->department }}</td>
                        <td>{{ $dept->total_equipment }}</td>
                        <td style="color: var(--danger-color); font-weight: bold;">{{ number_format($dept->total_maintenance_cost, 2) }} دينار</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">لا توجد بيانات متاحة.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title" style="margin-bottom: 1.5rem;">التكاليف حسب نوع الجهاز (الأعلى تكلفة)</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>نوع الجهاز</th>
                        <th>إجمالي التكلفة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($costsByType as $type)
                    <tr>
                        <td style="font-weight: bold;">{{ $type->name }}</td>
                        <td style="color: var(--danger-color); font-weight: bold;">{{ number_format($type->total_cost, 2) }} دينار</td>
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
