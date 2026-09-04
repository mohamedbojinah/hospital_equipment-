<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'maintenance_record_id',
        'equipment_id',
        'submitted_by',
        'approved_by',
        'title',
        'description',
        'total_amount',
        'status',
        'file_path',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function maintenanceRecord()
    {
        return $this->belongsTo(MaintenanceRecord::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
