<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\MaintenanceRecord;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $records = MaintenanceRecord::with('equipment', 'performer', 'approver')->latest()->paginate(15);
        return view('maintenance.index', compact('records'));
    }

    public function create(Request $request)
    {
        $equipment = Equipment::all();
        $selectedEquipmentId = $request->query('equipment_id');
        $ticketId = $request->query('ticket_id');
        return view('maintenance.create', compact('equipment', 'selectedEquipmentId', 'ticketId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'type' => 'required|in:preventive,corrective,emergency',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'next_maintenance_date' => 'nullable|date|after:today',
            'engineer_report' => 'nullable|string',
            'spare_parts_changed' => 'nullable|string',
            'ticket_id' => 'nullable|exists:tickets,id',
        ]);

        $validated['performed_by'] = auth()->id();
        $validated['status'] = 'approved';
        $validated['started_at'] = now();

        $record = MaintenanceRecord::create($validated);
        
        $equipment = Equipment::find($validated['equipment_id']);
        $equipment->update(['status' => 'active']); // Set back to active if maintenance is approved

        // Auto-close ticket if linked
        if (!empty($validated['ticket_id'])) {
            $ticket = \App\Models\Ticket::find($validated['ticket_id']);
            if ($ticket && $ticket->status !== 'resolved') {
                $ticket->update(['status' => 'resolved']);
            }
        }

        return redirect()->route('dashboard')->with('success', 'تم تسجيل تقرير الصيانة وإغلاق البلاغ بنجاح.');
    }

    public function show(MaintenanceRecord $maintenance)
    {
        $maintenance->load('equipment', 'performer', 'approver');
        return view('maintenance.show', compact('maintenance'));
    }

    public function approve(Request $request, $id)
    {
        $record = MaintenanceRecord::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:approved,completed',
            'engineer_report' => 'nullable|string',
            'spare_parts_changed' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'approved_by' => auth()->id(),
            'completed_at' => $validated['status'] == 'completed' ? now() : null,
        ];
        
        if (isset($validated['engineer_report'])) $updateData['engineer_report'] = $validated['engineer_report'];
        if (isset($validated['spare_parts_changed'])) $updateData['spare_parts_changed'] = $validated['spare_parts_changed'];
        if (isset($validated['cost'])) $updateData['cost'] = $validated['cost'];

        $record->update($updateData);
        
        if ($validated['status'] == 'completed' || $validated['status'] == 'approved') {
            $record->equipment->update(['status' => 'active']);
        }

        return back()->with('success', 'تم تحديث حالة الصيانة بنجاح.');
    }

    public function calendar()
    {
        $events = MaintenanceRecord::with('equipment')
            ->whereNotNull('next_maintenance_date')
            ->get()
            ->map(function($record) {
                return [
                    'title' => 'PPM: ' . ($record->equipment->name ?? 'محذوف'),
                    'start' => $record->next_maintenance_date,
                    'url' => route('maintenance.create', ['equipment_id' => $record->equipment_id]),
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706'
                ];
            });
            
        return view('maintenance.calendar', compact('events'));
    }

    public function history($equipment_id)
    {
        $equipment = Equipment::findOrFail($equipment_id);
        $records = MaintenanceRecord::with('performer', 'approver')
            ->where('equipment_id', $equipment_id)
            ->latest()
            ->paginate(10);
            
        return view('maintenance.history', compact('equipment', 'records'));
    }
}
