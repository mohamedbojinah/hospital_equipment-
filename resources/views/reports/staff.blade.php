@extends('layouts.app')

@section('title', 'تقرير أداء الموظفين')
@section('header', 'تقرير أداء الموظفين والفنيين')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">معدلات إنجاز المهام حسب الموظف</h3>
        <button onclick="window.print()" class="btn btn-outline">
            <i class="fa-solid fa-print"></i> طباعة التقرير
        </button>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الموظف</th>
                    <th>الدور</th>
                    <th>إجمالي المهام المسجلة</th>
                    <th>المهام المكتملة</th>
                    <th>نسبة الإنجاز</th>
                    <th>التكلفة التي تعامل معها</th>
                </tr>
            </thead>
            <tbody>
                @forelse($performers as $user)
                <tr>
                    <td style="font-weight: bold;">{{ $user->name }}</td>
                    <td>
                        @if($user->role == 'admin') مدير نظام
                        @elseif($user->role == 'supervisor') مشرف صيانة
                        @else موظف مستشفى @endif
                    </td>
                    <td>{{ $user->total_tasks }}</td>
                    <td style="color: var(--secondary-dark); font-weight: bold;">{{ $user->completed_tasks }}</td>
                    <td>
                        @php
                            $percentage = $user->total_tasks > 0 ? round(($user->completed_tasks / $user->total_tasks) * 100) : 0;
                        @endphp
                        <span class="badge {{ $percentage == 100 ? 'badge-success' : ($percentage >= 50 ? 'badge-info' : 'badge-warning') }}">
                            {{ $percentage }}%
                        </span>
                    </td>
                    <td>{{ $user->total_cost ? number_format($user->total_cost, 2) . ' دينار' : '0.00 دينار' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">لا توجد بيانات مسجلة لموظفين قاموا بمهام صيانة.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
