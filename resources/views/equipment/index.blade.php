@extends('layouts.app')

@section('title', 'إدارة الأجهزة')
@section('header', 'إدارة الأجهزة والمعدات')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">قائمة الأجهزة</h3>
        <a href="{{ route('equipment.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> إضافة جهاز جديدة
        </a>
    </div>

    <div style="margin-bottom: 1.5rem;">
        <form action="{{ route('equipment.index') }}" method="GET" style="display: flex; gap: 1rem; max-width: 500px;">
            <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو الرقم التسلسلي..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">بحث</button>
        </form>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>م</th>
                    <th>اسم الجهاز</th>
                    <th>الرقم التسلسلي</th>
                    <th>النوع</th>
                    <th>القسم</th>
                    <th>حالة الجهاز</th>
                    <th>QR Code</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipment as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->serial_number }}</td>
                    <td>{{ $item->type->name ?? '-' }}</td>
                    <td>{{ $item->department }}</td>
                    <td>
                        @if($item->status == 'active') <span class="badge badge-success">نشطة</span>
                        @elseif($item->status == 'maintenance') <span class="badge badge-warning">في الصيانة</span>
                        @else <span class="badge badge-danger">معطلة</span> @endif
                    </td>
                    <td>
                        <a href="{{ route('equipment.qr', $item) }}" class="btn btn-outline btn-sm">
                            <i class="fa-solid fa-qrcode"></i> عرض
                        </a>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('equipment.show', $item) }}" class="btn btn-info btn-sm" style="background: var(--primary-light); color: var(--primary-dark); border:none;">عرض</a>
                            <a href="{{ route('equipment.edit', $item) }}" class="btn btn-warning btn-sm" style="background: var(--warning-light); color: #b45309; border:none;">تعديل</a>
                            <form action="{{ route('equipment.destroy', $item) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="background: var(--danger-light); color: var(--danger-color); border:none;">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">لا توجد أجهزة مضافة.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.5rem;">
        {{ $equipment->links() }}
    </div>
</div>
@endsection
