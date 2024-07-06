<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('employees_code')->paginate(10);
        return view('admin.checkin.index', compact('employees'));
    }
    public function show(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        // Mendapatkan bulan dan tahun dari permintaan
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);

        // Mendapatkan jumlah hari dalam bulan yang dipilih
        $daysInMonth = Carbon::createFromDate($selectedYear, $selectedMonth)->daysInMonth;

        // Membuat array untuk menyimpan data checkin untuk setiap tanggal
        $checkinsByDate = [];

        // Iterasi melalui setiap hari dalam bulan untuk mengisi array
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($selectedYear, $selectedMonth, $day)->toDateString();
            $checkinsByDate[$date] = Checkin::where('employee_id', $id)
                ->whereDate('date', $date)
                ->get(); // Menggunakan paginate dengan 5 item per halaman
        }
        return view('admin.checkin.show', compact('employee', 'checkinsByDate'));
    }


}
