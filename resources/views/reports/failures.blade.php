@extends('layouts.app')

@section('title', 'تقرير الأعطال المتكررة')
@section('header', 'تقرير الأعطال المتكررة (الطارئة والتصحيحية)')

@section('content')
<div class="grid grid-cols-2">
    <div class="card" style="border-right: 4px solid var(--danger-color);">
        <div class="card-header">
            <h3 class="card-title" style="color: var(--danger-color);">الأجهزة الأكثر عرضة للأعطال</h3>
            <button onclick="window.print()" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-print"></i>
            </button>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>الجهاز</th>
                        <th>القسم</th>
                        <th>عدد مرات التعطل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($frequentEquipment as $eq)
                    <tr>
                        <td style="font-weight: bold;">
                            <a href="{{ route('equipment.show', $eq) }}">{{ $eq->name }}</a>
                        </td>
                        <td>{{ $eq->department }}</td>
                        <td>
                            <span class="badge badge-danger">{{ $eq->failure_count }} مرة</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">لا توجد بيانات أعطال مسجلة حتى الآن.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="border-right: 4px solid var(--warning-color);">
        <div class="card-header">
            <h3 class="card-title" style="color: var(--warning-color);">أنواع الأجهزة الأكثر تعطلاً بشكل عام</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>نوع الجهاز</th>
                        <th>إجمالي مرات التعطل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($frequentTypes as $type)
                    <tr>
                        <td style="font-weight: bold;">{{ $type->name }}</td>
                        <td>
                            <span class="badge badge-warning">{{ $type->failure_count }} مرة</span>
                        </td>
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
