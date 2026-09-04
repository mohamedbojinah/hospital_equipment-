@extends('layouts.app')

@section('title', 'تقديم عرض سعر جديد')
@section('header', 'رفع عرض سعر / طلب شراء')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">نموذج طلب مالي أو تسعيرة قطع غيار</h3>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-right: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('quotations.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        @if($ticket)
            <div class="alert alert-info" style="margin-bottom: 1.5rem;">
                <strong>هذا العرض مرتبط بالتذكرة:</strong> #{{ $ticket->id }} - {{ $ticket->title }}
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                <input type="hidden" name="equipment_id" value="{{ $ticket->equipment_id }}">
            </div>
        @else
            <div class="form-group">
                <label for="equipment_id" class="form-label">الجهاز المرتبط بالعرض *</label>
                <select name="equipment_id" id="equipment_id" class="form-control" required>
                    <option value="">-- اختر الجهاز --</option>
                    @foreach(\App\Models\Equipment::all() as $eq)
                        <option value="{{ $eq->id }}" {{ old('equipment_id') == $eq->id ? 'selected' : '' }}>
                            {{ $eq->name }} ({{ $eq->serial_number }})
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="form-group">
            <label for="title" class="form-label">عنوان العرض / الطلب *</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required placeholder="مثال: طلب شراء بوردة شاشة عرض">
        </div>

        <div class="form-group">
            <label for="total_amount" class="form-label">المبلغ الإجمالي المتوقع (د.ل) *</label>
            <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" value="{{ old('total_amount') }}" required>
        </div>

        <div class="form-group">
            <label for="quotation_file" class="form-label">صورة أو ملف عرض السعر المبدئي (PDF/Image)</label>
            <input type="file" name="quotation_file" id="quotation_file" class="form-control" accept=".pdf,.png,.jpg,.jpeg">
        </div>

        <div class="form-group">
            <label for="description" class="form-label">تفاصيل الطلب الفنية (مبررات الطلب) *</label>
            <textarea name="description" id="description" rows="5" class="form-control" required placeholder="اشرح لماذا تحتاج هذه القطع وما تأثيرها على الجهاز...">{{ old('description') }}</textarea>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-paper-plane"></i> تقديم الطلب للمدير المالي / الإداري
            </button>
            <a href="{{ route('quotations.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-times"></i> إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
