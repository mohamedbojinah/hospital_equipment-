<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentTypeController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// QR Scan (public route - redirects based on auth status)
Route::get('/scan/{qr_code}', [EquipmentController::class, 'scanRedirect'])->name('scan');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// المؤقت لتشغيل قاعدة البيانات على الاستضافة المجانية
Route::get('/setup-db', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true
        ]);
        return 'تم تهيئة قاعدة البيانات بالكامل (Fresh).';
    } catch (\Exception $e) {
        return 'حدث خطأ: ' . $e->getMessage();
    }
});

Route::get('/migrate-only', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--force' => true
        ]);
        return 'تم تحديث قاعدة البيانات بنجاح (بدون حذف البيانات السابقة)!';
    } catch (\Exception $e) {
        return 'حدث خطأ: ' . $e->getMessage();
    }
});
    
    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('equipment-types', EquipmentTypeController::class);
        Route::resource('equipment', EquipmentController::class);
        Route::get('equipment/{id}/qr', [EquipmentController::class, 'generateQR'])->name('equipment.qr');
        Route::get('equipment/{id}/print-qr', [EquipmentController::class, 'printQR'])->name('equipment.print-qr');
    });
    
    // Admin + Supervisor
    Route::middleware('role:admin,supervisor')->group(function () {
        Route::get('reports/equipment-status', [ReportController::class, 'equipmentStatus']);
        Route::get('reports/maintenance', [ReportController::class, 'maintenanceSummary']);
        Route::get('reports/statistics', [ReportController::class, 'statistics']);
        
        // New Advanced Reports
        Route::get('reports/financial', [ReportController::class, 'financialCosts'])->name('reports.financial');
        Route::get('reports/warranty', [ReportController::class, 'warrantyAging'])->name('reports.warranty');
        Route::get('reports/failures', [ReportController::class, 'frequentFailures'])->name('reports.failures');
        Route::get('reports/staff', [ReportController::class, 'staffPerformance'])->name('reports.staff');
        
        Route::patch('maintenance/{id}/approve', [MaintenanceController::class, 'approve']);
        
        // Maintenance
        Route::post('maintenance', [MaintenanceController::class, 'store']);
        Route::get('maintenance/create', [MaintenanceController::class, 'create'])->name('maintenance.create');
        Route::get('maintenance/calendar', [MaintenanceController::class, 'calendar'])->name('maintenance.calendar');
        Route::get('maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::get('maintenance/{maintenance}', [MaintenanceController::class, 'show'])->name('maintenance.show');
        Route::get('maintenance/{equipment_id}/history', [MaintenanceController::class, 'history']);
        
        // Quotations (Admin & Supervisor only)
        Route::resource('quotations', QuotationController::class);
        Route::patch('quotations/{quotation}/approve', [QuotationController::class, 'approve'])->name('quotations.approve');
    });
    
    // All authenticated users
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::patch('notifications/{id}/read', [NotificationController::class, 'markRead']);
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead']);
    
    // Tickets
    Route::resource('tickets', TicketController::class);
});
