@extends('layouts.app')

@section('title', 'تقرير الضمان والتقادم')
@section('header', 'تقرير الضمان والتقادم')

@section('content')
<div class="card" style="margin-bottom: 2rem; border-right: 4px solid var(--warning-color);">
    <div class="card-header">
        <h3 class="card-title" style="color: var(--warning-color);">
            <i class="fa-solid fa-clock"></i> ضمانات تقترب من الانتهاء (خلال 90 يوم)
        </h3>
        <button onclick="window.print()" class="btn btn-outline">
            <i class="fa-solid fa-print"></i> طباعة
        </button>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الآلة</th>
                    <th>القسم</th>
                    <th>تاريخ الشراء</th>
                    <th>تاريخ انتهاء الضمان</th>
                    <th>الأيام المتبقية</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expiringWarranties as $eq)
                <tr>
                    <td style="font-weight: bold;">
                        <a href="{{ route('equipment.show', $eq) }}">{{ $eq->name }}</a>
                    </td>
                    <td>{{ $eq->department }}</td>
                    <td>{{ $eq->purchase_date ? $eq->purchase_date->format('Y-m-d') : '-' }}</td>
                    <td style="color: var(--warning-color); font-weight: bold;">{{ $eq->warranty_expiry->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge badge-warning">{{ floor(now()->diffInDays($eq->warranty_expiry)) }} يوم</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">لا توجد ضمانات تقترب من الانتهاء قريباً.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-2">
    <div class="card" style="border-right: 4px solid var(--danger-color);">
        <h3 class="card-title" style="margin-bottom: 1.5rem; color: var(--danger-color);">
            <i class="fa-solid fa-circle-xmark"></i> ضمانات منتهية الصلاحية
        </h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>الآلة</th>
                        <th>تاريخ الانتهاء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expiredWarranties as $eq)
                    <tr>
                        <td style="font-weight: bold;"><a href="{{ route('equipment.show', $eq) }}">{{ $eq->name }}</a></td>
                        <td style="color: var(--danger-color);">{{ $eq->warranty_expiry->format('Y-m-d') }}</td>
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

    <div class="card" style="border-right: 4px solid var(--primary-color);">
        <h3 class="card-title" style="margin-bottom: 1.5rem;">
            <i class="fa-solid fa-hourglass-half"></i> أقدم الآلات في المستشفى
        </h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>الآلة</th>
                        <th>تاريخ الشراء</th>
                        <th>العمر (سنوات)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($oldestEquipment as $eq)
                    <tr>
                        <td style="font-weight: bold;"><a href="{{ route('equipment.show', $eq) }}">{{ $eq->name }}</a></td>
                        <td>{{ $eq->purchase_date->format('Y-m-d') }}</td>
                        <td>
                            <span class="badge badge-info">{{ floor($eq->purchase_date->diffInYears(now())) }} سنة</span>
                        </td>
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
</div>

<style>
    @media print {
        .sidebar, .topbar, .btn { display: none !important; }
        .main-content { margin: 0; padding: 0; }
        .card { box-shadow: none; border: 1px solid #ccc; }
    }
</style>
@endsection
