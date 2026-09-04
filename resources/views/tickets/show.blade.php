@extends('layouts.app')

@section('title', 'تفاصيل التذكرة #' . $ticket->id)
@section('header', 'تفاصيل البلاغ')

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title">تذكرة #{{ $ticket->id }}: {{ $ticket->title }}</h3>
        <div>
            @if($ticket->status == 'open') <span class="badge badge-warning" style="font-size: 1rem;">مفتوحة</span>
            @elseif($ticket->status == 'in_progress') <span class="badge badge-primary" style="font-size: 1rem;">قيد المعالجة</span>
            @elseif($ticket->status == 'resolved') <span class="badge badge-success" style="font-size: 1rem;">تم الحل</span>
            @else <span class="badge badge-secondary" style="font-size: 1rem;">مغلقة</span> @endif
        </div>
    </div>

    <div class="grid grid-cols-2" style="margin-bottom: 2rem;">
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">الجهاز المعطل</p>
            <p style="font-weight: 500;">
                <a href="{{ route('equipment.show', $ticket->equipment) }}" target="_blank" style="color: var(--primary-color);">
                    {{ $ticket->equipment->name ?? 'غير محدد' }} ({{ $ticket->equipment->serial_number ?? '' }})
                </a>
            </p>
        </div>
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">القسم / الموقع</p>
            <p style="font-weight: 500;">{{ $ticket->equipment->department ?? '-' }} - {{ $ticket->equipment->location ?? '-' }}</p>
        </div>
    </div>
    
    <div class="grid grid-cols-3" style="margin-bottom: 2rem;">
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">مقدم البلاغ</p>
            <p style="font-weight: 500;">{{ $ticket->reporter->name ?? 'مجهول' }}</p>
        </div>
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">تاريخ البلاغ</p>
            <p style="font-weight: 500;">{{ $ticket->created_at->format('Y-m-d H:i') }}</p>
        </div>
        <div>
            <p style="color: var(--text-muted); margin-bottom: 0.25rem;">الأولوية</p>
            <p style="font-weight: 500;">
                @if($ticket->priority == 'critical') <span style="color: var(--danger-color); font-weight: bold;">حرجة</span>
                @elseif($ticket->priority == 'high') <span style="color: #d97706; font-weight: bold;">عالية</span>
                @elseif($ticket->priority == 'medium') <span style="color: var(--info-color); font-weight: bold;">متوسطة</span>
                @else <span style="color: var(--success-color); font-weight: bold;">منخفضة</span> @endif
            </p>
        </div>
    </div>

    <div style="background: var(--bg-color); padding: 1.5rem; border-radius: 6px; margin-bottom: 2rem;">
        <h4 style="margin-bottom: 0.5rem; color: var(--text-color);">تفاصيل البلاغ:</h4>
        <p style="line-height: 1.6; white-space: pre-line;">{{ $ticket->description }}</p>
    </div>

    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
        <hr style="margin: 2rem 0;">
        <h3 style="margin-bottom: 1rem; color: var(--primary-dark);">إدارة التذكرة (للمهندسين)</h3>
        
        <form action="{{ route('tickets.update', $ticket) }}" method="POST" style="background: #f8fafc; padding: 1.5rem; border-radius: 6px; border: 1px solid #e2e8f0;">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label for="status" class="form-label">تحديث الحالة</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>مفتوحة</option>
                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>قيد المعالجة (جاري العمل عليها)</option>
                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>تم الحل (تعمل الآن)</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>مغلقة (تم إنهاؤها)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="assigned_to" class="form-label">إسناد إلى مهندس</label>
                    <select name="assigned_to" id="assigned_to" class="form-control">
                        <option value="">-- غير مسندة --</option>
                        @foreach($engineers as $eng)
                            <option value="{{ $eng->id }}" {{ $ticket->assigned_to == $eng->id ? 'selected' : '' }}>{{ $eng->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">
                <i class="fa-solid fa-save"></i> حفظ التحديثات
            </button>
        </form>
        
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <a href="{{ route('quotations.create', ['ticket_id' => $ticket->id]) }}" class="btn btn-warning" style="background: #fbbf24; color: #92400e; border:none;">
                <i class="fa-solid fa-file-invoice-dollar"></i> طلب تسعيرة قطع غيار لهذه التذكرة
            </a>
            
            <a href="{{ route('maintenance.create', ['equipment_id' => $ticket->equipment_id]) }}" class="btn btn-secondary">
                <i class="fa-solid fa-screwdriver-wrench"></i> إغلاق التذكرة وكتابة تقرير صيانة
            </a>
        </div>
    @endif
</div>
@endsection
