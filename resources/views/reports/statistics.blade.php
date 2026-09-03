@extends('layouts.app')

@section('title', 'الإحصائيات العامة')
@section('header', 'الإحصائيات العامة')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">إحصائيات الصيانة الشهرية</h3>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>الشهر / السنة</th>
                    <th>عدد عمليات الصيانة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monthlyMaintenance as $stat)
                <tr>
                    <td>{{ $stat->year }} - {{ str_pad($stat->month, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $stat->total }}</td>
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
@endsection
