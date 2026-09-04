<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['equipment', 'reporter', 'assignee'])->latest()->paginate(15);
        return view('tickets.index', compact('tickets'));
    }

    public function create(Request $request)
    {
        $equipment = Equipment::all();
        $selected_equipment_id = $request->query('equipment_id');
        return view('tickets.create', compact('equipment', 'selected_equipment_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'equipment_id' => 'required|exists:equipment,id',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $validated['reported_by'] = auth()->id();
        $validated['status'] = 'open';

        Ticket::create($validated);

        return redirect()->route('tickets.index')->with('success', 'تم تسجيل البلاغ بنجاح وسيتم مراجعته قريباً.');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['equipment', 'reporter', 'assignee', 'quotations']);
        $engineers = User::whereIn('role', ['admin', 'supervisor'])->get();
        return view('tickets.show', compact('ticket', 'engineers'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update($validated);

        return back()->with('success', 'تم تحديث التذكرة بنجاح.');
    }
}
