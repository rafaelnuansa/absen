<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EmployeeMonthlyAttendanceExport;
use App\Exports\EmployeePresenceExport;
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Presence;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class PresenceController extends Controller
{
    public function index(Request $request): View
    {
        // Query awal untuk mengambil semua data employee
        $query = Employee::query();
        // Cek apakah ada pencarian yang dilakukan
        if ($request->has('search')) {
            // Filter data berdasarkan code atau name employee
            $search = $request->search;
            $query->where('code', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%');
        }
        // Ambil data employee sesuai dengan filter pencarian dan urutkan berdasarkan code secara ascending
        $employees = $query->orderBy('code', 'asc')->paginate(20);
        return view('admin.presences.index', compact('employees'));
    }

    public function createNew($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        return view('admin.presences.create', compact('employee'));
    }

    public function storeNew(Request $request, $employeeId)
    {
        // Cek apakah karyawan ada
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return redirect()->back()->with('error', 'Karyawan tidak ditemukan.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'picture_in' => 'nullable|image|max:2048', // Max size 2MB
            'picture_out' => 'nullable|image|max:2048', // Max size 2MB
            'latitude_longitude_in' => 'nullable|string',
            'latitude_longitude_out' => 'nullable|string',
            'status' => 'nullable|in:present,on_leave,sick,absent',
            'information' => 'nullable|string',
        ]);

        // Jika validasi gagal, kembalikan pesan error
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek apakah data absen sudah ada pada tanggal tertentu
        $date = $request->date;
        $existingPresence = Presence::where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->first();

        if ($existingPresence) {
            return redirect()->back()->with('error', 'Data absen untuk karyawan ini pada tanggal tersebut sudah ada.');
        }

        // Buat presence baru
        $presence = new Presence();
        $presence->employee_id = $employeeId;
        $presence->date = $date;
        $presence->time_in = $request->time_in;
        $presence->time_out = $request->time_out;
        $presence->latitude_longitude_in = $request->latitude_longitude_in ?? '';
        $presence->latitude_longitude_out = $request->latitude_longitude_out ?? '';
        $presence->status = $request->status ?? 'present';
        $presence->information = $request->information;

        // Upload foto absen masuk jika ada
        if ($request->hasFile('picture_in')) {
            $pictureInPath = $request->file('picture_in')->store('presence_images');
            $presence->picture_in = $pictureInPath;
        }

        // Upload foto absen keluar jika ada
        if ($request->hasFile('picture_out')) {
            $pictureOutPath = $request->file('picture_out')->store('presence_images');
            $presence->picture_out = $pictureOutPath;
        }

        // Simpan data presence
        $presence->save();

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Absen berhasil disimpan.');
    }


    public function show(Request $request, $employeeId)
    {
        // Mendapatkan data karyawan
        $employee = Employee::findOrFail($employeeId);

        // Mendapatkan bulan dan tahun yang dipilih
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);

        // Mendapatkan jumlah hari dalam bulan yang dipilih
        $daysInMonth = Carbon::createFromDate($selectedYear, $selectedMonth)->daysInMonth;

        // Inisialisasi variabel untuk menyimpan informasi absensi
        $absenByDate = [];

        // Menghitung jumlah kehadiran, keterlambatan, sakit, dan izin
        $hadirCount = 0;
        $terlambatCount = 0;
        $sakitCount = 0;
        $izinCount = 0;

        // Mendapatkan data shift karyawan
        $shift = $employee->shift;

        // Looping untuk setiap hari dalam bulan yang dipilih
        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Mendapatkan tanggal dalam format Y-m-d
            $date = Carbon::createFromDate($selectedYear, $selectedMonth, $day)->toDateString();

            // Mengambil data absensi untuk karyawan pada tanggal tersebut
            $absensi = Presence::where('employee_id', $employeeId)
                ->whereDate('date', $date)
                ->get();

            // Inisialisasi array untuk menyimpan informasi absensi pada tanggal tersebut
            $absenByDate[$date] = [];

            // Looping untuk setiap data absensi pada tanggal tersebut
            foreach ($absensi as $absen) {
                // Menghitung keterlambatan
                $lateMinutes = 0;
              
                $terlambatCount += $lateMinutes > 0 ? 1 : 0;


                // Menghitung jumlah hadir
                $hadirCount += $absen->status === 'present' ? 1 : 0;

                // Menghitung jumlah sakit
                $sakitCount += $absen->status === 'sick' ? 1 : 0;

                // Menghitung jumlah izin
                $izinCount += $absen->status === 'on_leave' ? 1 : 0;

                // Menyimpan informasi absensi pada tanggal tersebut
                $absenByDate[$date][] = [
                    'absensi' => $absen,
                    'lateMinutes' => $lateMinutes,
                ];
            }
        }

        // Mengambil jumlah hari kerja dalam bulan tersebut
        $workingDaysCount = $daysInMonth;

        // Mengambil hari libur pada bulan tersebut
        $holidaysCount = 0; // Anda perlu mengganti ini dengan jumlah hari libur yang sesuai

        // Menghitung jumlah hari kerja yang sebenarnya (tidak termasuk hari libur)
        $actualWorkingDaysCount = $workingDaysCount - $holidaysCount;
        // Jika terdapat parameter export dengan nilai excel, maka lakukan export ke Excel
        if ($request->has('export') && $request->export === 'excel') {
            return $this->export($request, $employeeId);
        }

        // Menampilkan halaman dengan data absensi dan informasi jumlah kehadiran, keterlambatan, sakit, dan izin
        return view('admin.presences.show', compact('employee', 'absenByDate', 'hadirCount', 'terlambatCount', 'sakitCount', 'izinCount', 'actualWorkingDaysCount'));
    }



    public function detail($employeeId, $presenceId)
    {
        $employee = Employee::findOrFail($employeeId);
        $presence = Presence::findOrFail($presenceId);

        // Memeriksa apakah absen keluar telah dilakukan
        if ($presence->latitude_longitude_out) {
            list($latitude_out, $longitude_out) = explode(",", $presence->latitude_longitude_out);
        } else {
            // Jika absen keluar belum dilakukan, Anda dapat menetapkan nilai default
            $latitude_out = 0;
            $longitude_out = 0;
        }

        // Memeriksa apakah lokasi saat jam masuk ada
        if ($presence->latitude_longitude_in) {
            // Jika ada, memisahkan latitude dan longitude dari parameter absen masuk
            $latlong_in = $presence->latitude_longitude_in;
            list($latitude_in, $longitude_in) = explode(",", $latlong_in);
        } else {
            // Jika tidak ada, Anda dapat menetapkan nilai default
            $latitude_in = 0;
            $longitude_in = 0;
        }

        // Mengirim nilai latitude dan longitude ke tampilan
        return view('admin.presences.detail', compact('employee', 'presence', 'latitude_in', 'longitude_in', 'longitude_out', 'latitude_out'));
    }



    public function export(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);
        $daysInMonth = Carbon::createFromDate($selectedYear, $selectedMonth)->daysInMonth;
        $absenByDate = [];

        // Existing logic to fetch $absenByDate
        $shift = $employee->shift;
        $jam_masuk = $shift->time_in;
        $jam_pulang = $shift->time_out;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($selectedYear, $selectedMonth, $day)->toDateString();
            $absensi = Presence::where('employee_id', $employeeId)
                ->whereDate('date', $date)
                ->get();

            $absenByDate[$date] = [];

            foreach ($absensi as $absen) {
                $lateMinutes = max(0, Carbon::parse($absen->time_in)->diffInMinutes(Carbon::parse($jam_masuk)));
                $earlyMinutes = 0;
                if ($absen->time_out !== null) {
                    $earlyMinutes = max(0, Carbon::parse($jam_pulang)->diffInMinutes(Carbon::parse($absen->time_out)));
                }

                $absenByDate[$date][] = [
                    'absensi' => $absen,
                    'lateMinutes' => $lateMinutes,
                    'earlyMinutes' => $earlyMinutes,
                ];
            }
        }

        return Excel::download(
            new EmployeePresenceExport(
                $employee,
                $absenByDate,
                $selectedYear,
                $selectedMonth
            ),
            strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', $employee->name))) . '_employee_presence.xlsx'
        );
    }

    public function allEmployeeAttendance(Request $request)
    {
        // Get month and year from the request, defaulting to current month and year
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Export to Excel if requested
        if ($request->has('export') && $request->export === 'excel') {
            $positionId = $request->input('position_id');
            $buildingId = $request->input('building_id');
            return $this->exportMonthlyAttendance($month, $year, $positionId, $buildingId); // Pass shiftId to export function
        }

        // Fetch positions, buildings
        $positions = Position::all();
        $buildings = Building::all();

        // Fetch employees based on filters
        $employees = Employee::query()
            ->when($request->filled('position_id'), function ($query) use ($request) {
                $query->where('position_id', $request->position_id);
            })
            ->when($request->filled('building_id'), function ($query) use ($request) {
                $query->where('building_id', $request->building_id);
            })
            ->with('building', 'position')
            ->orderBy('name')
            ->get();

        // Get the number of days in the selected month
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        // Return the view with necessary data
            $isFilterApplied = $request->filled('month') || $request->filled('year') || $request->filled('position_id') || $request->filled('building_id') ;

        return view('admin.presences.all', compact('buildings', 'positions', 'employees', 'month', 'year', 'daysInMonth', 'isFilterApplied'));
    }

    public function exportMonthlyAttendance($month, $year, $positionId = null, $buildingId = null)
    {
        $positionName = $positionId ? Position::find($positionId)->name : null;
        $buildingName = $buildingId ? Building::find($buildingId)->name : null;

        // Clean special characters from names
        $positionName = $positionName ? preg_replace('/[^A-Za-z0-9\-]/', '', $positionName) : null;
        $buildingName = $buildingName ? preg_replace('/[^A-Za-z0-9\-]/', '', $buildingName) : null;

        $fileName = 'attendance_' . \Carbon\Carbon::create()->month($month)->format('F') . '_' . $year;

        if ($positionName) {
            $fileName .= '_position_' . strtolower($positionName);
        }

        if ($buildingName) {
            $fileName .= '_building_' . strtolower($buildingName);
        }


        return Excel::download(new EmployeeMonthlyAttendanceExport($month, $year, $positionId, $buildingId), $fileName . '.xlsx');
    }
}
