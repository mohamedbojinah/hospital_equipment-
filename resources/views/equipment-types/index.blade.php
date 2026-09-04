@extends('layouts.app')

@section('title', 'أنواع الأجهزة')
@section('header', 'إدارة أنواع الأجهزة')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">قائمة الأنواع</h3>
        <a href="{{ route('equipment-types.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> إضافة نوع جديد
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>م</th>
                    <th>الأيقونة</th>
                    <th>اسم النوع</th>
                    <th>الوصف</th>
                    <th>عدد الأجهزة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $type)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($type->icon)
                        <i class="fa-solid {{ $type->icon }}" style="font-size: 1.5rem; color: var(--text-muted);"></i>
                        @else
                        -
                        @endif
                    </td>
                    <td style="font-weight: 500;">{{ $type->name }}</td>
                    <td>{{ Str::limit($type->description, 50) ?? '-' }}</td>
                    <td>
                        <span class="badge badge-info">{{ $type->equipment_count ?? 0 }}</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('equipment-types.edit', $type) }}" class="btn btn-warning btn-sm" style="background: var(--warning-light); color: #b45309; border:none;">تعديل</a>
                            <form action="{{ route('equipment-types.destroy', $type) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="background: var(--danger-light); color: var(--danger-color); border:none;" {{ ($type->equipment_count ?? 0) > 0 ? 'disabled' : '' }}>حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">لا توجد أنواع أجهزة مضافة.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
