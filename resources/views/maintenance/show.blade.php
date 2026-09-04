@extends('layouts.app')

@section('title', 'تفاصيل الصيانة')
@section('header', 'تفاصيل تقرير الصيانة: ' . ($maintenance->equipment->name ?? ''))

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title">معلومات الصيانة</h3>
        <span class="badge {{ $maintenance->status == 'pending' ? 'badge-warning' : ($maintenance->status == 'completed' ? 'badge-success' : 'badge-info') }}" style="font-size: 1rem;">
            {{ $maintenance->status == 'pending' ? 'قيد الانتظار' : ($maintenance->status == 'in_progress' ? 'جاري العمل' : ($maintenance->status == 'completed' ? 'مكتملة' : 'معتمدة')) }}
        </span>
    </div>

    <div class="grid grid-cols-2" style="margin-bottom: 2rem;">
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">الجهاز</p>
            <p style="font-weight: 500;">
                <a href="{{ route('equipment.show', $maintenance->equipment) }}" target="_blank" style="color: var(--primary-color);">
                    {{ $maintenance->equipment->name ?? 'غير محدد' }} ({{ $maintenance->equipment->serial_number ?? '' }})
                </a>
            </p>
        </div>
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">نوع الصيانة</p>
            <p style="font-weight: 500; color: {{ $maintenance->type == 'preventive' ? 'var(--success-color)' : 'var(--danger-color)' }};">
                @if($maintenance->type == 'preventive') دورية (PPM)
                @elseif($maintenance->type == 'corrective') تصحيحية (Corrective)
                @else طارئة (Emergency) @endif
            </p>
        </div>
    </div>
    
    <div class="grid grid-cols-2" style="margin-bottom: 2rem;">
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">تم الطلب بواسطة</p>
            <p style="font-weight: 500;">{{ $maintenance->performer->name ?? 'مجهول' }}</p>
            <p style="color: var(--text-muted); font-size: 0.85rem;">{{ $maintenance->created_at->format('Y-m-d H:i') }}</p>
        </div>
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">تم الاعتماد بواسطة</p>
            <p style="font-weight: 500;">{{ $maintenance->approver->name ?? 'في الانتظار' }}</p>
            <p style="color: var(--text-muted); font-size: 0.85rem;">{{ $maintenance->completed_at ? $maintenance->completed_at->format('Y-m-d H:i') : '-' }}</p>
        </div>
    </div>
    
    <div style="margin-bottom: 2rem; background-color: var(--bg-color); padding: 1.5rem; border-radius: 6px;">
        <h4 style="margin-bottom: 0.5rem; color: var(--text-color);">وصف العطل أو طلب الصيانة الأساسي:</h4>
        <p style="line-height: 1.6; white-space: pre-line;">{{ $maintenance->description }}</p>
    </div>

    @if($maintenance->engineer_report || $maintenance->spare_parts_changed || $maintenance->cost)
        <hr style="margin: 2rem 0;">
        <h3 style="margin-bottom: 1rem; color: var(--primary-dark);">التقرير الهندسي النهائي</h3>
        
        @if($maintenance->engineer_report)
        <div style="margin-bottom: 1.5rem;">
            <h4 style="color: var(--text-muted); margin-bottom: 0.5rem;">تقرير المهندس بعد الصيانة:</h4>
            <div style="padding: 1rem; border-right: 4px solid var(--info-color); background: #f8fafc;">
                <p style="line-height: 1.6; white-space: pre-line;">{{ $maintenance->engineer_report }}</p>
            </div>
        </div>
        @endif
        
        <div class="grid grid-cols-2">
            @if($maintenance->spare_parts_changed)
            <div>
                <h4 style="color: var(--text-muted); margin-bottom: 0.5rem;">القطع التي تم تغييرها:</h4>
                <p style="font-weight: bold;">{{ $maintenance->spare_parts_changed }}</p>
            </div>
            @endif
            
            @if($maintenance->cost)
            <div>
                <h4 style="color: var(--text-muted); margin-bottom: 0.5rem;">التكلفة الإجمالية للصيانة:</h4>
                <p style="font-weight: bold; color: var(--danger-color); font-size: 1.2rem;">{{ number_format($maintenance->cost, 2) }} د.ل</p>
            </div>
            @endif
        </div>
    @endif

    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
        <hr style="margin: 2rem 0;">
        <h3 style="margin-bottom: 1rem; color: var(--primary-dark);">الإدارة الهندسية وإغلاق التقرير</h3>
        
        <form action="{{ url('maintenance/'.$maintenance->id.'/approve') }}" method="POST" style="background: #f8fafc; padding: 1.5rem; border-radius: 6px; border: 1px solid #e2e8f0;">
            @csrf
            @method('PATCH')
            
            <div class="form-group">
                <label for="engineer_report" class="form-label">التقرير الهندسي (ما تم إنجازه)</label>
                <textarea name="engineer_report" id="engineer_report" rows="4" class="form-control" placeholder="اكتب تقرير الصيانة النهائي...">{{ $maintenance->engineer_report }}</textarea>
            </div>
            
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label for="spare_parts_changed" class="form-label">قطع الغيار المستبدلة</label>
                    <input type="text" name="spare_parts_changed" id="spare_parts_changed" class="form-control" value="{{ $maintenance->spare_parts_changed }}" placeholder="مثال: بوردة شاشة، حساس حرارة">
                </div>
                
                <div class="form-group">
                    <label for="cost" class="form-label">التكلفة (إن وجدت)</label>
                    <input type="number" step="0.01" name="cost" id="cost" class="form-control" value="{{ $maintenance->cost }}">
                </div>
            </div>
            
            <div class="form-group" style="margin-top: 1rem;">
                <label for="status" class="form-label">حجهاز التقرير *</label>
                <select name="status" class="form-control" required>
                    <option value="completed" {{ $maintenance->status == 'completed' ? 'selected' : '' }}>مكتملة (تم إنهاء العمل)</option>
                    <option value="approved" {{ $maintenance->status == 'approved' ? 'selected' : '' }}>اعتماد فقط</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success" style="margin-top: 1rem; background: var(--secondary-dark); color: white;">
                <i class="fa-solid fa-check-circle"></i> حفظ وإغلاق التقرير الهندسي
            </button>
        </form>
    @endif
    
    <div style="margin-top: 2rem;">
        <a href="{{ route('maintenance.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> عودة لسجل الصيانة
        </a>
    </div>
</div>
@endsection
