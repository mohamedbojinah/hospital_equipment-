<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'performed_by',
        'approved_by',
        'type',
        'description',
        'status',
        'cost',
        'next_maintenance_date',
        'started_at',
        'completed_at',
        'engineer_report',
        'spare_parts_changed',
    ];

    protected $casts = [
        'next_maintenance_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cost' => 'decimal:2',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
