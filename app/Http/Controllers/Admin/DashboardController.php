<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Position;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $employeesActiveCount = Employee::where('is_active', 1)->count();
        $positionsCount = Position::count();
        $locationsCount = Building::count();
        $pendingLeaveCount = Leave::where('status', 'pending')->count();
        $pendingLeaves = Leave::where('status', 'pending')->latest()->limit(5)->get();
        
        return view('admin.dashboard.index', [
            'employeesActiveCount' => $employeesActiveCount,
            'positionsCount' => $positionsCount,
            'locationsCount' => $locationsCount,
            'pendingLeaveCount' => $pendingLeaveCount,
            'pendingLeaves' => $pendingLeaves
        ]);
    }
}
