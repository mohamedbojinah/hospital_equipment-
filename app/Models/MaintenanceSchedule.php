<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'frequency_days',
        'last_performed',
        'next_due',
        'assigned_to',
        'is_active',
    ];

    protected $casts = [
        'last_performed' => 'date',
        'next_due' => 'date',
        'is_active' => 'boolean',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
