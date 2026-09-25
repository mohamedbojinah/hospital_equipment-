<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'serial_number',
        'equipment_type_id',
        'department',
        'location',
        'status',
        'purchase_date',
        'warranty_expiry',
        'qr_code',
        'notes',
        'created_by',
        'manufacturer',
        'model_number',
        'supplier',
        'company_email',
        'purchase_price',
        'invoice_number',
        'expected_life_span',
        'operating_hours',
        'risk_level',
        'operating_date',
        'manual_file_path',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'operating_date' => 'date',
    ];

    public function type()
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function schedules()
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
