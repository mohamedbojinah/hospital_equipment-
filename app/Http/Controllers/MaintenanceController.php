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
        $equipment = null;
        if ($request->has('equipment_id')) {
            $equipment = Equipment::findOrFail($request->equipment_id);
        }
        
        return view('maintenance.create', compact('equipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'type' => 'required|in:preventive,corrective,emergency',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'next_maintenance_date' => 'nullable|date|after:today',
        ]);

        $validated['performed_by'] = auth()->id();
        $validated['status'] = 'pending';
        $validated['started_at'] = now();

        $record = MaintenanceRecord::create($validated);
        
        // Update equipment status
        $equipment = Equipment::find($validated['equipment_id']);
        $equipment->update(['status' => 'maintenance']);

        return redirect()->route('dashboard')->with('success', 'تم تسجيل طلب الصيانة بنجاح. في انتظار الاعتماد.');
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
            'notes' => 'nullable|string',
        ]);

        $record->update([
            'status' => $validated['status'],
            'approved_by' => auth()->id(),
            'completed_at' => $validated['status'] == 'completed' ? now() : null,
        ]);
        
        if ($validated['status'] == 'completed' || $validated['status'] == 'approved') {
            $record->equipment->update(['status' => 'active']);
        }

        return back()->with('success', 'تم تحديث حالة الصيانة بنجاح.');
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
