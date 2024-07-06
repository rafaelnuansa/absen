<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PatrolExport;
use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;
use App\Models\Patrol;
use App\Models\Checkpoint;
use App\Models\Employee;
use App\Models\Shift;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PatrolController extends Controller
{
    public function index(Request $request)
    {
        // Get month, year, and shift_id from the request, defaulting to current month and year
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $shiftId = $request->input('shift_id');
        $buildingId = $request->input('building_id');

        // Get the number of days in the selected month
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;

        // Fetch all checkpoints for dropdown filter
        $checkpoints = Checkpoint::all();
        $buildings = Building::all();
        // Fetch all shifts for dropdown filter
        $shifts = Shift::all();

        // Query patrols for the selected month
        $patrols = Patrol::with(['employee.building', 'checkpoint'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->when($shiftId, function ($query, $shiftId) {
                $query->whereHas('employee', function ($q) use ($shiftId) {
                    $q->where('shift_id', $shiftId);
                });
            })
            ->when($buildingId, function ($query, $buildingId) {
                $query->whereHas('employee', function ($q) use ($buildingId) {
                    $q->where('building_id', $buildingId);
                });
            })
            ->orderBy('employee_id')
            ->get();

        // Pass the variables to the view
        return view('admin.patrols.index', compact('shifts', 'buildings', 'patrols', 'month', 'year', 'daysInMonth', 'checkpoints', 'shiftId', 'buildingId'));
    }


    public function show($id)
    {
        $patrol = Patrol::with('employee', 'checkpoint', 'photos')->findOrFail($id);
        return view('admin.patrols.show', compact('patrol'));
    }

    public function export(Request $request)
    {
        // Dapatkan bulan, tahun, checkpoint_id, dan shift_id dari permintaan
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $buildingId = $request->input('building_id');
        $shiftId = $request->input('shift_id');
        // Buat nama file untuk disimpan
        $filename = 'patrols_' . $year . '_' . $month . '.xlsx';
        // Ekspor data patroli ke dalam format Excel
        return Excel::download(new PatrolExport($month, $year, $buildingId, $shiftId), $filename);
    }
 

    public function exportPdf(Request $request)
    {
        // Get month, year, building_id, and shift_id from the request
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $buildingId = $request->input('building_id');

        // Fetch patrols data based on filters
          $patrols = Patrol::with(['employee','employee.building', 'checkpoint', 'photos'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->when($buildingId, function ($query, $buildingId) {
                $query->whereHas('employee', function ($q) use ($buildingId) {
                    $q->where('building_id', $buildingId);
                });
            })
        ->orderBy('created_at', 'desc')
            ->get();

            // dd($patrols);
            
        // Generate PDF using DomPDF
        $pdf = Pdf::loadView('admin.patrols.pdf', compact('patrols', 'month', 'year', 'buildingId'))->setPaper('a4', 'landscape');
        // Set PDF filename
        $filename = 'patrols_' . $year . '_' . $month . '.pdf';
        // Download PDF file
        return $pdf->download($filename);
    }
}
