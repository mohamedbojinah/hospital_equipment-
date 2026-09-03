@extends('layouts.app')

@section('title', 'تفاصيل الصيانة')
@section('header', 'تفاصيل الصيانة للآلة: ' . ($maintenance->equipment->name ?? ''))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">معلومات الصيانة</h3>
        <span class="badge {{ $maintenance->status == 'pending' ? 'badge-warning' : ($maintenance->status == 'completed' ? 'badge-success' : 'badge-info') }}">
            {{ $maintenance->status == 'pending' ? 'قيد الانتظار' : ($maintenance->status == 'in_progress' ? 'جاري العمل' : ($maintenance->status == 'completed' ? 'مكتملة' : 'معتمدة')) }}
        </span>
    </div>

    <div class="grid grid-cols-2" style="margin-bottom: 2rem;">
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">نوع الصيانة</p>
            <p style="font-weight: 500;">
                @if($maintenance->type == 'preventive') دورية 
                @elseif($maintenance->type == 'corrective') تصحيحية 
                @else طارئة @endif
            </p>
        </div>
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">تكلفة الصيانة</p>
            <p style="font-weight: 500;">{{ $maintenance->cost ? $maintenance->cost . ' دينار' : 'غير محدد' }}</p>
        </div>
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">تم الطلب بواسطة</p>
            <p style="font-weight: 500;">{{ $maintenance->performer->name ?? 'مجهول' }}</p>
        </div>
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">تم الاعتماد بواسطة</p>
            <p style="font-weight: 500;">{{ $maintenance->approver->name ?? 'في الانتظار' }}</p>
        </div>
    </div>
    
    <div style="margin-bottom: 2rem;">
        <h4 style="margin-bottom: 0.5rem; color: var(--text-muted);">الوصف / ملاحظات الصيانة</h4>
        <div style="background-color: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            {{ $maintenance->description }}
        </div>
    </div>

    @if((auth()->user()->isAdmin() || auth()->user()->isSupervisor()) && $maintenance->status == 'pending')
    <div style="border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
        <h4 style="margin-bottom: 1rem;">اتخاذ إجراء</h4>
        <form action="{{ url('maintenance/'.$maintenance->id.'/approve') }}" method="POST" style="display: flex; gap: 1rem; align-items: center;">
            @csrf
            @method('PATCH')
            
            <select name="status" class="form-control" style="max-width: 200px;" required>
                <option value="completed">اعتماد كمكتملة</option>
                <option value="approved">اعتماد أولي</option>
            </select>
            
            <button type="submit" class="btn btn-success" style="background: var(--secondary-dark); color: white;">
                <i class="fa-solid fa-check"></i> حفظ الإجراء
            </button>
        </form>
    </div>
    @endif
    
    <div style="margin-top: 2rem;">
        <a href="{{ route('maintenance.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> عودة للقائمة
        </a>
    </div>
</div>
@endsection
