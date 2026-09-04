<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Ticket;
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

        Quotation::create($validated);

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

        return back()->with('success', 'تم تحديث حالة العرض بنجاح.');
    }
}
