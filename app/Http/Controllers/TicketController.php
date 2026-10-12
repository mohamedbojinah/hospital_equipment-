<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Notification;
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

        $ticket = Ticket::create($validated);
        $equipment = Equipment::find($validated['equipment_id']);

        // إرسال إشعار للمشرفين والمدراء بطلب الصيانة / البلاغ الجديد
        $supervisors = User::whereIn('role', ['admin', 'supervisor'])
            ->where('id', '!=', auth()->id())
            ->get();

        foreach ($supervisors as $supervisor) {
            Notification::create([
                'user_id' => $supervisor->id,
                'title' => 'طلب صيانة جديد (بلاغ عطل)',
                'message' => 'تم استلام بلاغ عطل جديد للجهاز (' . ($equipment->name ?? 'غير محدد') . ') في ' . ($equipment->department ?? '') . ' بواسطة ' . auth()->user()->name . '.',
                'type' => in_array($validated['priority'], ['high', 'critical']) ? 'danger' : 'warning',
                'is_read' => false,
                'related_equipment_id' => $equipment->id ?? null,
            ]);
        }

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

        $oldStatus = $ticket->status;
        $oldAssignee = $ticket->assigned_to;

        $ticket->update($validated);

        // إشعار المهندس المكلف بالبلاغ
        if (!empty($validated['assigned_to']) && $validated['assigned_to'] != $oldAssignee && $validated['assigned_to'] != auth()->id()) {
            Notification::create([
                'user_id' => $validated['assigned_to'],
                'title' => 'تكليف ببلاغ صيانة',
                'message' => 'تم تكليفك بمتابعة البلاغ رقم #' . $ticket->id . ' للجهاز (' . ($ticket->equipment->name ?? '') . ').',
                'type' => 'info',
                'is_read' => false,
                'related_equipment_id' => $ticket->equipment_id,
            ]);
        }

        // إشعار مقدم البلاغ عند تغير حالته
        if ($validated['status'] != $oldStatus && $ticket->reported_by && $ticket->reported_by != auth()->id()) {
            $statusArabic = match($validated['status']) {
                'in_progress' => 'قيد المعالجة (جاري العمل عليها)',
                'resolved' => 'تم الحل وإصلاح العطل',
                'closed' => 'مغلقة',
                default => 'مفتوحة'
            };

            Notification::create([
                'user_id' => $ticket->reported_by,
                'title' => 'تحديث حالة البلاغ #' . $ticket->id,
                'message' => 'تم تحديث حالة بلاغك للجهاز (' . ($ticket->equipment->name ?? '') . ') إلى: ' . $statusArabic,
                'type' => in_array($validated['status'], ['resolved', 'closed']) ? 'success' : 'info',
                'is_read' => false,
                'related_equipment_id' => $ticket->equipment_id,
            ]);
        }

        return back()->with('success', 'تم تحديث البلاغ بنجاح.');
    }
}
