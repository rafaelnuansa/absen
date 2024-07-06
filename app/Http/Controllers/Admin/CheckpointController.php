<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;
use App\Models\Checkpoint;
use App\Models\CheckpointEmployee;
use App\Models\Employee;
use Illuminate\Support\Facades\File;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


class CheckpointController extends Controller
{
    public function index(Request $request)
    {
        // Query awal untuk mengambil semua data checkpoint
        $query = Checkpoint::query();
    
        // Cek apakah ada pencarian yang dilakukan
        if ($request->has('search')) {
            // Filter data berdasarkan nama lokasi checkpoint
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }
    
        // Ambil data checkpoint sesuai dengan filter pencarian dan urutkan berdasarkan yang terbaru
        $checkpoints = $query->with('building')->latest()->paginate(20);
    
        // Tampilkan halaman index dengan data checkpoint yang sudah difilter
        return view('admin.checkpoint.index', compact('checkpoints'));
    }
    
    public function show($id)
    {
        // Mengambil data checkpoint beserta building dan karyawannya yang terhubung dengan building tersebut
        $checkpoint = Checkpoint::with('building.employees')->findOrFail($id);
        
        // Mengambil building dari checkpoint
        $building = $checkpoint->building;
    
        // Mengambil karyawan yang terhubung dengan building checkpoint tersebut
        $employees = $building->employees;
    
        return view('admin.checkpoint.show', compact('checkpoint', 'employees'));
    }
    
    public function enrollEmployeeToCheckpoint(Request $request)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'required|exists:employees,id',
            'checkpoint_id' => 'required|exists:checkpoints,id',
        ]);

        // Ambil array ID karyawan dari request
        $employeeIds = $request->input('employee_ids');

        // Cek apakah karyawan-karyawan sudah terdaftar di titik kontrol
        $existingEnrollments = CheckpointEmployee::whereIn('employee_id', $employeeIds)
            ->where('checkpoint_id', $request->checkpoint_id)
            ->exists();

        if (!$existingEnrollments) {
            // Jika karyawan belum terdaftar, tambahkan ke titik kontrol
            foreach ($employeeIds as $employeeId) {
                CheckpointEmployee::create([
                    'employee_id' => $employeeId,
                    'checkpoint_id' => $request->checkpoint_id,
                ]);
            }

            return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan ke titik kontrol.');
        } else {
            // Jika karyawan sudah terdaftar, kirimkan pesan error
            return redirect()->back()->with('error', 'Salah satu atau lebih karyawan sudah terdaftar di titik kontrol.');
        }
    }

    public function unenrollEmployeefromCheckpoint(Request $request)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'required|exists:employees,id',
            'checkpoint_id' => 'required|exists:checkpoints,id',
        ]);

        // Ambil array ID karyawan dari request
        $employeeIds = $request->input('employee_ids');

        // Hapus karyawan dari titik kontrol
        CheckpointEmployee::whereIn('employee_id', $employeeIds)
            ->where('checkpoint_id', $request->checkpoint_id)
            ->delete();

        return redirect()->back()->with('success', 'Karyawan berhasil dihapus dari titik kontrol.');
    }
    public function create()
    {
        $buildings = Building::all();
        return view('admin.checkpoint.create', compact('buildings'));
    }


    public function store(Request $request)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'building_id' => 'required',
            'description' => 'nullable|string|max:255',
        ]);

        // Simpan data checkpoint beserta path QR code ke dalam database
        $checkpoint = new Checkpoint();
        // $code = 'CP' . date('YmdHis');
        $code = $request->code;
        $checkpoint->code = $code;
        $checkpoint->name = $request->name;
        $checkpoint->building_id = $request->building_id;
        $checkpoint->description = $request->description;

        // Membuat QR code dengan menggunakan Builder
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($code)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->labelText($checkpoint->code)
            ->validateResult(false)
            ->build();

        // Simpan QR code ke dalam file
        $qrCodePath = public_path('qr-code/' . $code . '.png');
        file_put_contents($qrCodePath, $result->getString());

        $checkpoint->qrcode = 'qr-code/' . $code . '.png';
        $checkpoint->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.checkpoint.index')->with('success', 'Checkpoint berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'building_id' => 'required',
            'description' => 'nullable|string|max:255',
        ]);

        // Cari data checkpoint berdasarkan ID
        $checkpoint = Checkpoint::findOrFail($id);

        // Update data checkpoint
        $checkpoint->code = $request->code;
        $checkpoint->name = $request->name;
        $checkpoint->building_id = $request->building_id;
        $checkpoint->description = $request->description;

        // Membuat QR code dengan menggunakan Builder
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($checkpoint->code)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->labelText($checkpoint->code)
            ->validateResult(false)
            ->build();

        // Simpan QR code ke dalam file
        $qrCodePath = public_path('qr-code/' . $checkpoint->code . '.png');
        file_put_contents($qrCodePath, $result->getString());

        // Simpan path QR code ke dalam database
        $checkpoint->qrcode = 'qr-code/' . $checkpoint->code . '.png';
        $checkpoint->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.checkpoint.index')->with('success', 'Checkpoint berhasil diperbarui.');
    }

    public function edit($id)
    {
        // Temukan checkpoint berdasarkan ID
        $checkpoint = Checkpoint::findOrFail($id);

        $buildings = Building::all();
        // Tampilkan halaman edit dengan data checkpoint yang ditemukan
        return view('admin.checkpoint.edit', compact('checkpoint', 'buildings'));
    }

   
    public function destroy($id)
    {
        // Temukan checkpoint berdasarkan ID
        $checkpoint = Checkpoint::findOrFail($id);
        // Hapus checkpoint
        $checkpoint->delete();
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.checkpoint.index')->with('success', 'Checkpoint berhasil dihapus.');
    }

}
