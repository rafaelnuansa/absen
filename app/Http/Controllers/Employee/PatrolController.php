<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Checkpoint;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Patrol;
use App\Models\PatrolPhoto;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class PatrolController extends Controller
{

    public function index()
    {
        // Ambil tanggal dan waktu saat ini
        $now = Carbon::now();

        // Ambil ID karyawan yang saat ini diotentikasi menggunakan guard 'employee'
        $employeeId = Auth::guard('employee')->id();

        // Ambil shift karyawan saat ini
        $employee = Employee::findOrFail($employeeId);
        $shift = $employee->shift;

        // Jika tidak ada shift atau waktu masuk shift tidak valid, kembalikan pesan kesalahan
        if (!$shift || !$shift->time_in) {
            return redirect()->back()->with('error', 'Shift karyawan tidak valid.');
        }
        $timeIn = Carbon::createFromFormat('H:i', $shift->time_in);
        $timeOut = Carbon::createFromFormat('H:i', $shift->time_out);
        $checkpoints = Checkpoint::whereHas('employees', function ($query) use ($employeeId) {
            $query->where('employee_id', $employeeId);
        })->get();

        // Kirim data checkpoint ke view employee.patrol.index
        return view('employee.patrol.index', compact('checkpoints'));
    }

    public function show($id)
    {
        // Temukan checkpoint berdasarkan ID
        $checkpoint = Checkpoint::findOrFail($id);

        // Pastikan checkpoint terkait dengan karyawan yang sedang diotentikasi
        $employeeId = Auth::guard('employee')->id();
        if (!$checkpoint->employees()->where('employee_id', $employeeId)->exists()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke checkpoint ini.');
        }

        // Kirim data checkpoint ke view untuk ditampilkan detailnya
        return view('employee.patrol.show', compact('checkpoint'));
    }


    public function store(Request $request): JsonResponse
    {
        // Validasi data yang diterima dari hasil pemindaian QR
        $request->validate([
            'checkpoint_code' => 'required|string|max:255', // Ini bisa berbeda tergantung pada bagaimana Anda menyimpan hasil QR
        ]);

        // Temukan checkpoint berdasarkan kode yang diterima dari hasil QR
        $checkpoint = Checkpoint::where('code', $request->checkpoint_code)->first();

        // Periksa apakah checkpoint ditemukan
        if ($checkpoint) {
            // Ambil ID karyawan yang saat ini diotentikasi menggunakan guard 'employee'
            $employeeId = Auth::guard('employee')->id();

            // Periksa apakah karyawan yang sedang login telah melakukan patroli di checkpoint ini sebelumnya
            $existingPatrol = Patrol::where('employee_id', $employeeId)
                ->where('checkpoint_id', $checkpoint->id)
                ->first();

            // Jika karyawan telah melakukan patroli di checkpoint ini sebelumnya, kembalikan respons JSON dengan pesan peringatan
            if ($existingPatrol) {
                return response()->json(['message' => 'Anda telah menscan QR sebelumnya di lokasi ini'], 409);
            }

            // Temukan checkpoint terkait dengan patroli yang sedang ditampilkan
            $patrolCheckpoint = Patrol::findOrFail($request->patrol_id)->checkpoint;

            // Periksa apakah kode checkpoint yang dipindai sama dengan kode checkpoint yang ditampilkan
            if ($patrolCheckpoint->code !== $checkpoint->code) {
                return response()->json(['error' => 'Kode checkpoint yang dipindai tidak cocok dengan checkpoint yang ditampilkan'], 400);
            }

            // Jika karyawan belum melakukan patroli di checkpoint ini sebelumnya dan lokasi cocok, buat data patroli baru
            $patrol = new Patrol();
            $patrol->employee_id = $employeeId;
            $patrol->checkpoint_id = $checkpoint->id;
            $patrol->date = now()->toDateString();
            $patrol->time = now()->toTimeString();
            $patrol->status = 'pending';
            $patrol->save();

            // Kembalikan respons JSON dengan pesan sukses
            return response()->json(['message' => 'Patroli berhasil dibuat', 'patrol_id' => $patrol->id], 201);
        } else {
            // Jika kode checkpoint tidak valid, kembalikan respons JSON dengan pesan error
            return response()->json(['error' => 'Kode checkpoint tidak valid'], 400);
        }
    }


    public function report($patrolId)
    {
        // Temukan patroli berdasarkan ID yang diberikan
        $patrol = Patrol::findOrFail($patrolId);

        // Kembalikan view 'employee.checkpoint.report' dengan data patroli yang ditemukan
        return view('employee.checkpoint.report', compact('patrol'));
    }

    public function report_store(Request $request, $patrolId)
    {
        // Temukan patroli berdasarkan ID yang diberikan
        $patrol = Patrol::findOrFail($patrolId);

        // Validasi data yang diterima dari form
        $request->validate([
            'information' => 'nullable|string|max:255',
        ]);

        // Simpan informasi laporan yang diberikan ke dalam patroli
        $patrol->information = $request->input('information');
        $patrol->save();

        // Redirect kembali ke halaman yang sesuai atau berikan respons sesuai kebutuhan aplikasi Anda
        return redirect()->route('employee.patrol.show', $patrol->id)->with('success', 'Informasi laporan berhasil disimpan.');
    }


    public function photo_store(Request $request, $patrolId)
    {
        // Validasi data yang diterima dari form untuk foto-foto
        $request->validate([
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB untuk setiap foto
        ]);

        // Temukan patroli berdasarkan ID yang diberikan
        $patrol = Patrol::findOrFail($patrolId);

        // Proses pengunggahan foto jika ada yang diunggah
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            foreach ($photos as $photo) {
                // Simpan foto ke penyimpanan yang dipilih
                $path = $photo->store('patrol_photos');

                // Buat catatan untuk foto yang diunggah di tabel patrol_photos
                $patrolPhoto = new PatrolPhoto();
                $patrolPhoto->patrol_id = $patrol->id;
                $patrolPhoto->file_path = $path;
                $patrolPhoto->save();
            }
        }

        // Redirect kembali ke halaman yang sesuai atau berikan respons sesuai kebutuhan aplikasi Anda
        return redirect()->route('employee.patrol.show', $patrol->id)->with('success', 'Foto berhasil diunggah.');
    }

    public function report_update(Request $request, $patrolId)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'information' => 'nullable|string|max:255',
        ]);

        // Temukan patroli berdasarkan ID yang diberikan
        $patrol = Patrol::findOrFail($patrolId);

        // Simpan informasi laporan yang diberikan ke dalam patroli
        $patrol->information = $request->input('information');
        $patrol->save();

        // Redirect kembali ke halaman yang sesuai atau berikan respons sesuai kebutuhan aplikasi Anda
        return redirect()->route('employee.patrol.show', $patrol->id)->with('success', 'Informasi laporan berhasil diperbarui.');
    }

    public function photo_update(Request $request, $patrolId)
    {
        // Validasi data yang diterima dari form untuk foto-foto
        $request->validate([
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB untuk setiap foto
        ]);

        // Temukan patroli berdasarkan ID yang diberikan
        $patrol = Patrol::findOrFail($patrolId);

        // Hapus foto-foto sebelumnya
        $patrol->photos()->delete();

        // Proses pengunggahan foto jika ada yang diunggah
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            foreach ($photos as $photo) {
                // Simpan foto ke penyimpanan yang dipilih
                $path = $photo->store('patrol_photos');

                // Buat catatan untuk foto yang diunggah di tabel patrol_photos
                $patrolPhoto = new PatrolPhoto();
                $patrolPhoto->patrol_id = $patrol->id;
                $patrolPhoto->file_path = $path;
                $patrolPhoto->save();
            }
        }

        // Redirect kembali ke halaman yang sesuai atau berikan respons sesuai kebutuhan aplikasi Anda
        return redirect()->route('employee.patrol.show', $patrol->id)->with('success', 'Foto berhasil diperbarui.');
    }


}
