<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\MaintenanceRecord;

class ReportController extends Controller
{
    public function equipmentStatus()
    {
        $active = Equipment::where('status', 'active')->count();
        $maintenance = Equipment::where('status', 'maintenance')->count();
        $inactive = Equipment::where('status', 'inactive')->count();
        
        $departments = Equipment::select('department', \DB::raw('count(*) as total'))
            ->groupBy('department')
            ->get();

        return view('reports.equipment-status', compact('active', 'maintenance', 'inactive', 'departments'));
    }

    public function maintenanceSummary()
    {
        $records = MaintenanceRecord::with('equipment')
            ->select('type', \DB::raw('count(*) as total'), \DB::raw('sum(cost) as total_cost'))
            ->groupBy('type')
            ->get();
            
        $recentCompleted = MaintenanceRecord::with('equipment', 'performer')
            ->where('status', 'completed')
            ->latest('completed_at')
            ->take(10)
            ->get();

        return view('reports.maintenance-summary', compact('records', 'recentCompleted'));
    }

    public function statistics()
    {
        $monthlyMaintenance = MaintenanceRecord::select(
            \DB::raw('MONTH(created_at) as month'),
            \DB::raw('YEAR(created_at) as year'),
            \DB::raw('count(*) as total')
        )
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get();
        
        return view('reports.statistics', compact('monthlyMaintenance'));
    }

    public function financialCosts()
    {
        $costsByDepartment = Equipment::leftJoin('maintenance_records', 'equipment.id', '=', 'maintenance_records.equipment_id')
            ->select('equipment.department', \DB::raw('count(distinct equipment.id) as total_equipment'), \DB::raw('COALESCE(sum(maintenance_records.cost), 0) as total_maintenance_cost'))
            ->groupBy('equipment.department')
            ->get();

        $costsByType = Equipment::join('equipment_types', 'equipment.equipment_type_id', '=', 'equipment_types.id')
            ->leftJoin('maintenance_records', 'equipment.id', '=', 'maintenance_records.equipment_id')
            ->select('equipment_types.name', \DB::raw('COALESCE(sum(maintenance_records.cost), 0) as total_cost'))
            ->groupBy('equipment_types.id', 'equipment_types.name')
            ->orderByDesc('total_cost')
            ->get();

        $totalCost = MaintenanceRecord::sum('cost');

        return view('reports.financial', compact('costsByDepartment', 'costsByType', 'totalCost'));
    }

    public function warrantyAging()
    {
        $expiringWarranties = Equipment::whereNotNull('warranty_expiry')
            ->where('warranty_expiry', '>=', now())
            ->where('warranty_expiry', '<=', now()->addDays(90))
            ->orderBy('warranty_expiry', 'asc')
            ->get();

        $expiredWarranties = Equipment::whereNotNull('warranty_expiry')
            ->where('warranty_expiry', '<', now())
            ->orderBy('warranty_expiry', 'desc')
            ->take(15)
            ->get();
            
        $oldestEquipment = Equipment::whereNotNull('purchase_date')
            ->orderBy('purchase_date', 'asc')
            ->take(10)
            ->get();

        return view('reports.warranty', compact('expiringWarranties', 'expiredWarranties', 'oldestEquipment'));
    }

    public function frequentFailures()
    {
        $frequentEquipment = Equipment::withCount(['maintenanceRecords as failure_count' => function ($query) {
                $query->whereIn('type', ['corrective', 'emergency']);
            }])
            ->orderByDesc('failure_count')
            ->having('failure_count', '>', 0)
            ->take(15)
            ->get();

        $frequentTypes = Equipment::join('equipment_types', 'equipment.equipment_type_id', '=', 'equipment_types.id')
            ->join('maintenance_records', 'equipment.id', '=', 'maintenance_records.equipment_id')
            ->whereIn('maintenance_records.type', ['corrective', 'emergency'])
            ->select('equipment_types.name', \DB::raw('count(maintenance_records.id) as failure_count'))
            ->groupBy('equipment_types.id', 'equipment_types.name')
            ->orderByDesc('failure_count')
            ->take(10)
            ->get();

        return view('reports.failures', compact('frequentEquipment', 'frequentTypes'));
    }

    public function staffPerformance()
    {
        $performers = \App\Models\User::whereHas('maintenancePerformed')
            ->withCount(['maintenancePerformed as total_tasks'])
            ->withCount(['maintenancePerformed as completed_tasks' => function ($q) {
                $q->where('status', 'completed');
            }])
            ->withSum('maintenancePerformed as total_cost', 'cost')
            ->orderByDesc('total_tasks')
            ->get();

        return view('reports.staff', compact('performers'));
    }
}
