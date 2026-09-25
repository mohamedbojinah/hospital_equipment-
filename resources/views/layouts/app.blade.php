<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة أجهزة المستشفى')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fa-solid fa-hospital"></i> إدامة</h2>
            </div>
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-pie"></i> لوحة التحكم
                    </a>
                </li>
                
                @if(auth()->user()->isAdmin())
                <li>
                    <a href="{{ route('equipment-types.index') }}" class="nav-item {{ request()->routeIs('equipment-types.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group"></i> أنواع الأجهزة
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i> المستخدمين
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                <li>
                    <a href="{{ route('equipment.index') }}" class="nav-item {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-microscope"></i> إدارة الأجهزة
                    </a>
                </li>
                <li>
                    <a href="{{ route('maintenance.index') }}" class="nav-item {{ request()->routeIs('maintenance.index') || request()->routeIs('maintenance.show') ? 'active' : '' }}">
                        <i class="fa-solid fa-screwdriver-wrench"></i> سجلات الصيانة
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('maintenance.calendar') }}" class="nav-item {{ request()->routeIs('maintenance.calendar') ? 'active' : '' }}">
                        <i class="fa-regular fa-calendar-days"></i> جدول الصيانة (PPM)
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ route('tickets.index') }}" class="nav-item {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-triangle-exclamation"></i> نظام البلاغات
                    </a>
                </li>

                @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                <li>
                    <a href="{{ route('quotations.index') }}" class="nav-item {{ request()->routeIs('quotations.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar"></i> عروض الأسعار
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                <li style="margin-top: 1rem;">
                    <span style="display: block; padding: 0.5rem 1.5rem; font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">التقارير المتقدمة</span>
                </li>
                <li>
                    <a href="{{ url('reports/equipment-status') }}" class="nav-item {{ request()->is('reports/equipment-status') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-pie"></i> حالة الأجهزة
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.financial') }}" class="nav-item {{ request()->routeIs('reports.financial') ? 'active' : '' }}">
                        <i class="fa-solid fa-money-bill-wave"></i> التكاليف المالية
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.failures') }}" class="nav-item {{ request()->routeIs('reports.failures') ? 'active' : '' }}">
                        <i class="fa-solid fa-triangle-exclamation"></i> الأعطال المتكررة
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.warranty') }}" class="nav-item {{ request()->routeIs('reports.warranty') ? 'active' : '' }}">
                        <i class="fa-solid fa-shield-halved"></i> الضمان والتقادم
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.staff') }}" class="nav-item {{ request()->routeIs('reports.staff') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear"></i> أداء الموظفين
                    </a>
                </li>
                @endif
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="page-title">
                    <h3 style="color: var(--text-main);">@yield('header', 'لوحة التحكم')</h3>
                </div>
                
                <div class="user-menu">
                    <a href="{{ url('notifications') }}" class="btn btn-outline" style="border:none; position:relative;">
                        <i class="fa-regular fa-bell"></i>
                        @if(auth()->user()->notifications()->where('is_read', false)->count() > 0)
                        <span style="position:absolute; top:5px; right:5px; background:var(--danger-color); width:10px; height:10px; border-radius:50%;"></span>
                        @endif
                    </a>
                    
                    <div style="font-weight: 500;">
                        {{ auth()->user()->name }}
                        <span class="badge badge-info" style="margin-right: 5px;">
                            @if(auth()->user()->isAdmin()) مدير @elseif(auth()->user()->isSupervisor()) مشرف @else موظف @endif
                        </span>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="border:none; color: var(--danger-color);" title="تسجيل خروج">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    
    @yield('scripts')
</body>
</html>
