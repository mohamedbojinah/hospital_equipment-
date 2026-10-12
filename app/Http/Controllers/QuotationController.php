<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Quotation;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with(['equipment', 'submitter', 'approver', 'ticket'])->latest()->paginate(15);
        return view('quotations.index', compact('quotations'));
    }

    public function create(Request $request)
    {
        $ticket = null;
        if ($request->has('ticket_id')) {
            $ticket = Ticket::with('equipment')->findOrFail($request->ticket_id);
        }
        return view('quotations.create', compact('ticket'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'ticket_id' => 'nullable|exists:tickets,id',
            'equipment_id' => 'required|exists:equipment,id',
            'quotation_file' => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        $validated['submitted_by'] = auth()->id();
        $validated['status'] = 'pending';

        if ($request->hasFile('quotation_file')) {
            $validated['file_path'] = $request->file('quotation_file')->store('quotations', 'public');
        }

        $quotation = Quotation::create($validated);
        $equipment = \App\Models\Equipment::find($validated['equipment_id']);

        // إشعار المشرفين والمدراء بعرض السعر الجديد
        $supervisors = User::whereIn('role', ['admin', 'supervisor'])
            ->where('id', '!=', auth()->id())
            ->get();

        foreach ($supervisors as $supervisor) {
            Notification::create([
                'user_id' => $supervisor->id,
                'title' => 'عرض سعر جديد بحاجة للمراجعة',
                'message' => 'تم تقديم عرض سعر: "' . $quotation->title . '" بقيمة ' . number_format($quotation->total_amount, 2) . ' د.ل للجهاز (' . ($equipment->name ?? '') . ') بواسطة ' . auth()->user()->name . '.',
                'type' => 'warning',
                'is_read' => false,
                'related_equipment_id' => $equipment->id ?? null,
            ]);
        }

        return redirect()->route('quotations.index')->with('success', 'تم تقديم عرض السعر/الطلب بنجاح.');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['equipment', 'submitter', 'approver', 'ticket']);
        return view('quotations.show', compact('quotation'));
    }

    public function approve(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $quotation->update([
            'status' => $validated['status'],
            'approved_by' => auth()->id(),
        ]);

        // إشعار مقدم الطلب بحالة عرض السعر
        if ($quotation->submitted_by && $quotation->submitted_by != auth()->id()) {
            $statusText = $validated['status'] == 'approved' ? 'الموافقة على' : 'رفض';
            Notification::create([
                'user_id' => $quotation->submitted_by,
                'title' => 'تحديث حالة عرض السعر',
                'message' => 'تم ' . $statusText . ' عرض السعر "' . $quotation->title . '" الخاص بالجهاز (' . ($quotation->equipment->name ?? '') . ') من قبل ' . auth()->user()->name . '.',
                'type' => $validated['status'] == 'approved' ? 'success' : 'danger',
                'is_read' => false,
                'related_equipment_id' => $quotation->equipment_id,
            ]);
        }

        return back()->with('success', 'تم تحديث حالة العرض بنجاح.');
    }
}
