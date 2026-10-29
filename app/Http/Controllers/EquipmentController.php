<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

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
        // إزالة الفراغات الزائدة من البداية والنهاية
        $request->merge([
            'name' => is_string($request->name) ? trim($request->name) : $request->name,
            'serial_number' => is_string($request->serial_number) ? trim($request->serial_number) : $request->serial_number,
        ]);

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('equipment', 'name'),
            ],
            'serial_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('equipment', 'serial_number'),
            ],
            'equipment_type_id' => 'required|exists:equipment_types,id',
            'department' => 'required|string',
            'location' => 'required|string',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'model_number' => 'nullable|string',
            'supplier' => 'nullable|string',
            'company_email' => 'nullable|email',
            'purchase_price' => 'nullable|numeric',
            'invoice_number' => 'nullable|string',
            'expected_life_span' => 'nullable|integer',
            'operating_hours' => 'nullable|integer',
            'risk_level' => 'nullable|in:low,medium,high',
            'operating_date' => 'nullable|date',
            'manual_file' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ], $this->validationMessages());

        if ($validator->fails()) {
            return redirect()->route('equipment.create')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        try {
            if ($request->hasFile('manual_file')) {
                $validated['manual_file_path'] = $request->file('manual_file')->store('manuals', 'public');
            }

            $validated['created_by'] = auth()->id();
            $validated['qr_code'] = Str::uuid()->toString();

            $equipment = Equipment::create($validated);

            return redirect()->route('equipment.show', $equipment)->with('success', 'تمت إضافة الجهاز بنجاح.');
        } catch (QueryException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                if (str_contains($e->getMessage(), 'serial_number')) {
                    return redirect()->route('equipment.create')->withInput()->withErrors(['serial_number' => 'الرقم التسلسلي مسجل مسبقاً لجهاز آخر في النظام.']);
                }
                if (str_contains($e->getMessage(), 'name')) {
                    return redirect()->route('equipment.create')->withInput()->withErrors(['name' => 'اسم الجهاز مسجل مسبقاً في النظام، يرجى اختيار اسم غير مكرر.']);
                }
            }
            return redirect()->route('equipment.create')->withInput()->withErrors(['error' => 'تعذر حفظ الجهاز بسبب تعارض في البيانات، يرجى التأكد من عدم تكرار الاسم أو الرقم التسلسلي.']);
        }
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
        // إزالة الفراغات الزائدة من البداية والنهاية
        $request->merge([
            'name' => is_string($request->name) ? trim($request->name) : $request->name,
            'serial_number' => is_string($request->serial_number) ? trim($request->serial_number) : $request->serial_number,
        ]);

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('equipment', 'name')->ignore($equipment->id),
            ],
            'serial_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('equipment', 'serial_number')->ignore($equipment->id),
            ],
            'equipment_type_id' => 'required|exists:equipment_types,id',
            'department' => 'required|string',
            'location' => 'required|string',
            'status' => 'required|in:active,inactive,maintenance',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'model_number' => 'nullable|string',
            'supplier' => 'nullable|string',
            'company_email' => 'nullable|email',
            'purchase_price' => 'nullable|numeric',
            'invoice_number' => 'nullable|string',
            'expected_life_span' => 'nullable|integer',
            'operating_hours' => 'nullable|integer',
            'risk_level' => 'nullable|in:low,medium,high',
            'operating_date' => 'nullable|date',
            'manual_file' => 'nullable|file|mimes:pdf|max:10240',
        ], $this->validationMessages());

        if ($validator->fails()) {
            return redirect()->route('equipment.edit', $equipment)
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        try {
            if ($request->hasFile('manual_file')) {
                $validated['manual_file_path'] = $request->file('manual_file')->store('manuals', 'public');
            }

            $equipment->update($validated);

            return redirect()->route('equipment.show', $equipment)->with('success', 'تم تحديث بيانات الجهاز بنجاح.');
        } catch (QueryException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                if (str_contains($e->getMessage(), 'serial_number')) {
                    return redirect()->route('equipment.edit', $equipment)->withInput()->withErrors(['serial_number' => 'الرقم التسلسلي مسجل مسبقاً لجهاز آخر في النظام.']);
                }
                if (str_contains($e->getMessage(), 'name')) {
                    return redirect()->route('equipment.edit', $equipment)->withInput()->withErrors(['name' => 'اسم الجهاز مسجل مسبقاً في النظام، يرجى اختيار اسم غير مكرر.']);
                }
            }
            return redirect()->route('equipment.edit', $equipment)->withInput()->withErrors(['error' => 'تعذر تحديث الجهاز بسبب تعارض في البيانات، يرجى التأكد من عدم تكرار الاسم أو الرقم التسلسلي.']);
        }
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'تم حذف الجهاز بنجاح.');
    }

    /**
     * نصوص رسائل التحقق المخصصة باللغة العربية
     */
    protected function validationMessages(): array
    {
        return [
            'name.required' => 'يرجى إدخال اسم الجهاز.',
            'name.unique' => 'اسم الجهاز مسجل مسبقاً في النظام، يرجى اختيار اسم غير مكرر أو تمييزه.',
            'name.max' => 'يجب ألا يتجاوز اسم الجهاز 255 حرفاً.',
            'serial_number.required' => 'يرجى إدخال الرقم التسلسلي للجهاز.',
            'serial_number.unique' => 'الرقم التسلسلي مسجل مسبقاً لجهاز آخر في النظام، لا يمكن تكرار الرقم التسلسلي.',
            'serial_number.max' => 'يجب ألا يتجاوز الرقم التسلسلي 255 حرفاً.',
            'equipment_type_id.required' => 'يرجى اختيار نوع الجهاز.',
            'equipment_type_id.exists' => 'نوع الجهاز المحدد غير صالح.',
            'department.required' => 'يرجى إدخال القسم التابع له الجهاز.',
            'location.required' => 'يرجى تحديد الموقع الدقيق للجهاز داخل المستشفى.',
            'status.required' => 'يرجى تحديد الحالة التشغيلية للجهاز.',
            'company_email.email' => 'صيغة البريد الإلكتروني للشركة غير صحيحة.',
            'purchase_price.numeric' => 'سعر الشراء يجب أن يكون قيمة رقمية صحيحة.',
            'manual_file.mimes' => 'يجب أن يكون دليل الاستخدام ملف من نوع PDF فقط.',
            'manual_file.max' => 'يجب ألا يتجاوز حجم ملف دليل الاستخدام 10 ميجابايت.',
        ];
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
