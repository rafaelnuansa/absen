<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        // Ambil bulan dan tahun saat ini
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Ambil ID employee yang sedang login
        $employeeId = Auth::guard('employee')->id();

        // Ambil data permohonan cuti yang dibuat oleh employee yang sedang login
        $leaves = Leave::where('employee_id', $employeeId)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->latest()
            ->get();

        return view('employee.leaves.index', compact('leaves'));
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'date_work' => 'required|date|after_or_equal:end_date',
            'total' => 'required|integer',
            'reason' => 'required|string',
        ]);
        $employeeId = Auth::guard('employee')->id();
        // Buat objek Leave baru dan isi dengan data yang validasi
        $leave = new Leave();
        $leave->employee_id = $employeeId; // Ambil ID karyawan yang sedang login
        $leave->start_date = $validatedData['start_date'];
        $leave->end_date = $validatedData['end_date'];
        $leave->date_work = $validatedData['date_work'];
        $leave->total = $validatedData['total'];
        $leave->reason = $validatedData['reason'];
        $leave->save();

        // Kembalikan respons sukses
        return redirect()->route('employee.leave')->with('success', 'Permohonan cuti berhasil disimpan.');
    }

    public function update(Request $request)
    {
        // Validasi request
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'date_work' => 'required|date',
            'total' => 'required|integer',
            'reason' => 'required|string',
            'leave_id' => 'required|exists:leaves,id', // Pastikan leave_id ada di database
        ]);

        // Ambil data leave yang ingin diupdate
        $leave = Leave::findOrFail($request->id);
        $employeeId = Auth::guard('api')->id();
        // Periksa apakah pengguna memiliki izin untuk mengedit leave
        if ($leave->employee_id !== $employeeId) {
            // Jika tidak memiliki izin, redirect kembali ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit cuti ini.');
        }

        // Update data leave
        $leave->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'date_work' => $request->date_work,
            'total' => $request->total,
            'reason' => $request->reason,
        ]);

        // Redirect kembali ke halaman sebelumnya dengan pesan berhasil
        return redirect()->back()->with('success', 'Cuti berhasil diperbarui.');
    }


}
