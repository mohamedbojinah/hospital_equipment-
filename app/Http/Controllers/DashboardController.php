<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\MaintenanceRecord;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isSupervisor()) {
            return $this->supervisorDashboard();
        } else {
            return $this->employeeDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_equipment' => Equipment::count(),
            'active_equipment' => Equipment::where('status', 'active')->count(),
            'maintenance_equipment' => Equipment::where('status', 'maintenance')->count(),
            'inactive_equipment' => Equipment::where('status', 'inactive')->count(),
        ];
        
        $departments = Equipment::selectRaw('department, count(*) as count')->groupBy('department')->get();
        
        $topCostEquipment = MaintenanceRecord::selectRaw('equipment_id, sum(cost) as total_cost')
            ->groupBy('equipment_id')
            ->orderByDesc('total_cost')
            ->take(5)
            ->with('equipment')
            ->get();
        
        $recentMaintenance = MaintenanceRecord::with('equipment', 'performer')->latest()->take(5)->get();
        
        return view('dashboard.admin', compact('stats', 'departments', 'topCostEquipment', 'recentMaintenance'));
    }

    private function supervisorDashboard()
    {
        $stats = [
            'total_equipment' => Equipment::count(),
            'pending_tickets' => \App\Models\Ticket::whereIn('status', ['open', 'in_progress'])->count(),
        ];
        
        $pendingTickets = \App\Models\Ticket::with('equipment', 'reporter')->whereIn('status', ['open', 'in_progress'])->latest()->get();
        
        return view('dashboard.supervisor', compact('stats', 'pendingTickets'));
    }

    private function employeeDashboard()
    {
        $myTickets = \App\Models\Ticket::where('reported_by', auth()->id())->latest()->get();
        
        $stats = [
            'total_tickets' => $myTickets->count(),
            'open_tickets' => $myTickets->whereIn('status', ['open', 'in_progress'])->count(),
            'resolved_tickets' => $myTickets->where('status', 'resolved')->count(),
        ];
        
        return view('dashboard.employee', compact('stats', 'myTickets'));
    }
}
