@extends('layouts.app')

@section('title', 'إدارة المستخدمين')
@section('header', 'إدارة المستخدمين والصلاحيات')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">قائمة الموظفين</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> إضافة مستخدم جديد
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>م</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الدور (الصلاحية)</th>
                    <th>رقم الهاتف</th>
                    <th>الحجهاز</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="font-weight: 500;">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role == 'admin') <span class="badge badge-info">مدير نظام</span>
                        @elseif($user->role == 'supervisor') <span class="badge badge-warning">مشرف صيانة</span>
                        @else <span class="badge" style="background:#e2e8f0; color:#475569;">موظف</span> @endif
                    </td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>
                        @if($user->is_active) <span class="badge badge-success">مفعل</span>
                        @else <span class="badge badge-danger">موقوف</span> @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm" style="background: var(--warning-light); color: #b45309; border:none;">تعديل</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">لا يوجد مستخدمين.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
