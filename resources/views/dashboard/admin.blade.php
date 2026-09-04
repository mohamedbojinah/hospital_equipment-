@extends('layouts.app')

@section('title', 'لوحة تحكم المدير')
@section('header', 'لوحة التحكم والإحصائيات (CMMS Dashboard)')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="grid grid-cols-4">
    <div class="card" style="border-right: 4px solid var(--primary-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">إجمالي المستخدمين</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;">{{ $stats['total_users'] }}</div>
    </div>
    
    <div class="card" style="border-right: 4px solid var(--secondary-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">إجمالي الأجهزة</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem;">{{ $stats['total_equipment'] }}</div>
    </div>
    
    <div class="card" style="border-right: 4px solid var(--secondary-dark);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">الأجهزة النشطة</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem; color: var(--secondary-dark);">{{ $stats['active_equipment'] }}</div>
    </div>
    
    <div class="card" style="border-right: 4px solid var(--warning-color);">
        <h4 style="color: var(--text-muted); font-size: 0.9rem;">أجهزة في الصيانة</h4>
        <div style="font-size: 2rem; font-weight: 700; margin-top: 0.5rem; color: var(--warning-color);">{{ $stats['maintenance_equipment'] }}</div>
    </div>
</div>

<div class="grid grid-cols-2" style="margin-top: 2rem;">
    <!-- Equipment Status Donut Chart -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">حجهاز الأجهزة</h3>
        </div>
        <div style="height: 300px; display: flex; justify-content: center;">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <!-- Equipment by Department Bar Chart -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">توزيع الأجهزة حسب القسم</h3>
        </div>
        <div style="height: 300px; display: flex; justify-content: center;">
            <canvas id="departmentChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-2" style="margin-top: 2rem;">
    <!-- Top 5 Costly Equipment -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">أعلى 5 أجهزة تكلفة في الصيانة</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>الجهاز</th>
                        <th>الرقم التسلسلي</th>
                        <th>التكلفة الإجمالية</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topCostEquipment as $record)
                    <tr>
                        <td>{{ $record->equipment->name ?? 'محذوف' }}</td>
                        <td>{{ $record->equipment->serial_number ?? '-' }}</td>
                        <td style="color: var(--danger-color); font-weight: bold;">{{ number_format($record->total_cost, 2) }} د.ل</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">لا توجد سجلات صيانة مكلفة.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Maintenance -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">آخر عمليات الصيانة</h3>
            <a href="{{ route('maintenance.index') }}" class="btn btn-outline btn-sm">عرض الكل</a>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>الجهاز</th>
                        <th>القائم بالصيانة</th>
                        <th>الحجهاز</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentMaintenance as $record)
                    <tr>
                        <td>{{ $record->equipment->name ?? 'محذوف' }}</td>
                        <td>{{ $record->performer->name ?? 'مجهول' }}</td>
                        <td>
                            @if($record->status == 'pending') <span class="badge badge-warning">قيد الانتظار</span>
                            @elseif($record->status == 'in_progress') <span class="badge badge-info">جاري العمل</span>
                            @elseif($record->status == 'completed') <span class="badge badge-success">مكتملة</span>
                            @else <span class="badge badge-success">معتمدة</span> @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">لا توجد سجلات صيانة حديثة.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Chart (Donut)
    var ctxStatus = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['نشطة', 'في الصيانة', 'غير نشطة'],
            datasets: [{
                data: [{{ $stats['active_equipment'] }}, {{ $stats['maintenance_equipment'] }}, {{ $stats['inactive_equipment'] }}],
                backgroundColor: ['#2e7d32', '#f57c00', '#c62828'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // Department Chart (Bar)
    var ctxDept = document.getElementById('departmentChart').getContext('2d');
    var deptLabels = {!! json_encode($departments->pluck('department')) !!};
    var deptData = {!! json_encode($departments->pluck('count')) !!};
    
    var departmentChart = new Chart(ctxDept, {
        type: 'bar',
        data: {
            labels: deptLabels,
            datasets: [{
                label: 'عدد الأجهزة',
                data: deptData,
                backgroundColor: '#1976d2',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
});
</script>
@endsection
