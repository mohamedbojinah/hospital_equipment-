<?php

namespace App\Http\Controllers;

use App\Models\EquipmentType;
use Illuminate\Http\Request;

class EquipmentTypeController extends Controller
{
    public function index()
    {
        $types = EquipmentType::withCount('equipment')->get();
        return view('equipment-types.index', compact('types'));
    }

    public function create()
    {
        return view('equipment-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:equipment_types',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        EquipmentType::create($validated);

        return redirect()->route('equipment-types.index')->with('success', 'تمت إضافة نوع الآلة بنجاح.');
    }

    public function edit(EquipmentType $equipmentType)
    {
        return view('equipment-types.edit', compact('equipmentType'));
    }

    public function update(Request $request, EquipmentType $equipmentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:equipment_types,name,' . $equipmentType->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $equipmentType->update($validated);

        return redirect()->route('equipment-types.index')->with('success', 'تم تحديث نوع الآلة بنجاح.');
    }

    public function destroy(EquipmentType $equipmentType)
    {
        if ($equipmentType->equipment()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف هذا النوع لوجود آلات مرتبطة به.');
        }
        
        $equipmentType->delete();
        return redirect()->route('equipment-types.index')->with('success', 'تم حذف نوع الآلة بنجاح.');
    }
}
