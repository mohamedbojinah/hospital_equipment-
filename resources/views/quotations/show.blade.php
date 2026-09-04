@extends('layouts.app')

@section('title', 'تفاصيل عرض السعر #' . $quotation->id)
@section('header', 'تفاصيل الاعتماد المالي')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title">عرض سعر / طلب شراء #{{ $quotation->id }}</h3>
        <div>
            @if($quotation->status == 'pending') <span class="badge badge-warning" style="font-size: 1rem;">بانتظار الاعتماد</span>
            @elseif($quotation->status == 'approved') <span class="badge badge-success" style="font-size: 1rem;">معتمد مالياً</span>
            @else <span class="badge badge-danger" style="font-size: 1rem;">مرفوض</span> @endif
        </div>
    </div>

    <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">{{ $quotation->title }}</h2>

    <div class="grid grid-cols-2" style="margin-bottom: 2rem; background: var(--bg-color); padding: 1rem; border-radius: 6px;">
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">المبلغ الإجمالي</p>
            <p style="font-size: 1.5rem; font-weight: bold; color: var(--danger-color);">{{ number_format($quotation->total_amount, 2) }} د.ل</p>
        </div>
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">مقدم الطلب</p>
            <p style="font-weight: 500; font-size: 1.1rem;">{{ $quotation->submitter->name ?? 'مجهول' }}</p>
            <p style="color: var(--text-muted); font-size: 0.85rem;">بتاريخ: {{ $quotation->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>
    
    <div class="grid grid-cols-2" style="margin-bottom: 2rem;">
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">الجهاز المرتبط</p>
            <p style="font-weight: 500;">
                <a href="{{ route('equipment.show', $quotation->equipment) }}" target="_blank" style="color: var(--primary-color);">
                    {{ $quotation->equipment->name ?? 'غير محدد' }}
                </a>
            </p>
        </div>
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">التذكرة المرتبطة</p>
            <p style="font-weight: 500;">
                @if($quotation->ticket)
                    <a href="{{ route('tickets.show', $quotation->ticket) }}" target="_blank" style="color: var(--primary-color);">
                        تذكرة #{{ $quotation->ticket->id }}: {{ $quotation->ticket->title }}
                    </a>
                @else
                    لا يوجد تذكرة مرتبطة
                @endif
            </p>
        </div>
    </div>

    <div style="margin-bottom: 2rem;">
        <h4 style="color: var(--text-muted); margin-bottom: 0.5rem;">المبررات والتفاصيل الفنية:</h4>
        <p style="line-height: 1.6; white-space: pre-line;">{{ $quotation->description }}</p>
    </div>

    @if($quotation->file_path)
    <div style="margin-bottom: 2rem;">
        <h4 style="color: var(--text-muted); margin-bottom: 0.5rem;">المرفقات:</h4>
        <a href="{{ asset('storage/' . $quotation->file_path) }}" target="_blank" class="btn btn-outline">
            <i class="fa-solid fa-file-pdf"></i> عرض ملف التسعيرة المرفق
        </a>
    </div>
    @endif

    @if(auth()->user()->isAdmin())
        <hr style="margin: 2rem 0;">
        <h3 style="margin-bottom: 1rem; color: var(--primary-dark);">قرار الإدارة</h3>
        
        <form action="{{ route('quotations.approve', $quotation) }}" method="POST" style="background: #f8fafc; padding: 1.5rem; border-radius: 6px; border: 1px solid #e2e8f0;">
            @csrf
            @method('PATCH')
            
            <div class="form-group">
                <label for="status" class="form-label">القرار النهائي</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending" {{ $quotation->status == 'pending' ? 'selected' : '' }}>إبقاء قيد المراجعة</option>
                    <option value="approved" {{ $quotation->status == 'approved' ? 'selected' : '' }}>اعتماد (موافقة مالية)</option>
                    <option value="rejected" {{ $quotation->status == 'rejected' ? 'selected' : '' }}>رفض</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">
                <i class="fa-solid fa-stamp"></i> حفظ القرار
            </button>
        </form>
    @elseif($quotation->status != 'pending')
        <hr style="margin: 2rem 0;">
        <div class="alert alert-info">
            تم اتخاذ قرار بشأن هذا العرض بواسطة: <strong>{{ $quotation->approver->name ?? 'الإدارة' }}</strong>
        </div>
    @endif
</div>
@endsection
