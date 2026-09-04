@extends('layouts.app')

@section('title', 'جدول الصيانة الوقائية (PPM)')
@section('header', 'التقويم السنوي للصيانة الوقائية (PPM Calendar)')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title">جدول مهام الصيانة المجدولة للأجهزة</h3>
        <a href="{{ route('maintenance.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> جدولة صيانة جديدة
        </a>
    </div>

    <div style="background-color: var(--bg-color); padding: 1.5rem; border-radius: 6px;">
        <div id="calendar" style="min-height: 600px; background: white; padding: 1rem; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var eventsData = {!! json_encode($events) !!};

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'ar',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: {
            today: 'اليوم',
            month: 'شهر',
            week: 'أسبوع',
            day: 'يوم',
            list: 'قائمة مهام'
        },
        events: eventsData,
        eventClick: function(info) {
            info.jsEvent.preventDefault(); // don't let the browser navigate
            if (info.event.url) {
                window.open(info.event.url, '_blank');
            }
        }
    });

    calendar.render();
});
</script>
@endsection
