<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::with('type');
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->search . '%');
        }
        
        $equipment = $query->paginate(15);
        return view('equipment.index', compact('equipment'));
    }

    public function create()
    {
        $types = EquipmentType::all();
        return view('equipment.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:equipment',
            'equipment_type_id' => 'required|exists:equipment_types,id',
            'department' => 'required|string',
            'location' => 'required|string',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['qr_code'] = Str::uuid()->toString();

        $equipment = Equipment::create($validated);

        return redirect()->route('equipment.show', $equipment)->with('success', 'تمت إضافة الآلة بنجاح.');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load(['type', 'maintenanceRecords' => function($q) {
            $q->latest();
        }]);
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $types = EquipmentType::all();
        return view('equipment.edit', compact('equipment', 'types'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:equipment,serial_number,' . $equipment->id,
            'equipment_type_id' => 'required|exists:equipment_types,id',
            'department' => 'required|string',
            'location' => 'required|string',
            'status' => 'required|in:active,inactive,maintenance',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $equipment->update($validated);

        return redirect()->route('equipment.show', $equipment)->with('success', 'تم تحديث الآلة بنجاح.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'تم حذف الآلة بنجاح.');
    }

    public function generateQR($id)
    {
        $equipment = Equipment::findOrFail($id);
        $url = route('scan', $equipment->qr_code);
        
        $qrCode = QrCode::size(250)->generate($url);
        
        return view('equipment.qr', compact('equipment', 'qrCode'));
    }

    public function printQR($id)
    {
        $equipment = Equipment::findOrFail($id);
        $url = route('scan', $equipment->qr_code);
        
        $qrCode = QrCode::size(300)->generate($url);
        
        return view('equipment.print-qr', compact('equipment', 'qrCode'));
    }

    public function scanRedirect($qr_code)
    {
        $equipment = Equipment::where('qr_code', $qr_code)->firstOrFail();
        
        if (!auth()->check()) {
            session(['url.intended' => route('scan', $qr_code)]);
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول للوصول إلى تفاصيل الآلة: ' . $equipment->name);
        }

        $user = auth()->user();
        $equipment->load('type');

        if ($user->isAdmin()) {
            return view('scan.admin', compact('equipment'));
        } elseif ($user->isSupervisor()) {
            return view('scan.supervisor', compact('equipment'));
        } else {
            return view('scan.employee', compact('equipment'));
        }
    }
}
