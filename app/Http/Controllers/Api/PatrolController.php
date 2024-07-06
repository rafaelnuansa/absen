<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checkpoint;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Patrol;
use App\Models\PatrolPhoto;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PatrolController extends Controller
{

    public function index()
    {
        // Ambil ID karyawan yang saat ini diotentikasi menggunakan guard 'api'
        $employeeId = Auth::guard('api')->id();

        // Ambil informasi karyawan
        $employee = Employee::findOrFail($employeeId);

        // Ambil ID lokasi (building) dari karyawan
        $buildingId = $employee->building_id;
        // Ambil checkpoint yang terkait dengan lokasi (building) karyawan
        // $checkpoints = Checkpoint::with('building')->where('building_id', $buildingId)->get();
        $checkpoints = Checkpoint::with(['building', 'patrols' => function ($query) use ($employeeId) {
            $query->where('employee_id', $employeeId)
                  ->whereDate('date', Carbon::today());
        }])->where('building_id', $buildingId)->get();
        return response()->json([
            'success' => true,
            'checkpoints' => $checkpoints,
        ]);
    }


    public function show($id)
    {
        // Temukan checkpoint berdasarkan ID
        $checkpoint = Checkpoint::with('building')->where('id', $id)->first();
        // Pastikan checkpoint terkait dengan karyawan yang sedang diotentikasi
        $employeeId = Auth::guard('api')->id();
        // Filter patroli hari ini berdasarkan tanggal
        $todayPatrols = Patrol::where('employee_id', $employeeId)
            ->where('checkpoint_id', $id)
            ->whereDate('date', Carbon::today())
            ->get();
        // Kirim data checkpoint ke view untuk ditampilkan detailnya
        return response()->json([
            'success' => true,
            'checkpoint' => $checkpoint,
            'patrols' => $todayPatrols,
        ]);
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
            $employeeId = Auth::guard('api')->id();

            // Periksa apakah karyawan yang sedang login telah melakukan patroli di checkpoint ini sebelumnya
            $existingPatrol = Patrol::where('employee_id', $employeeId)
                ->where('checkpoint_id', $checkpoint->id)
                ->first();

            // Jika karyawan telah melakukan patroli di checkpoint ini sebelumnya, kembalikan respons JSON dengan pesan peringatan
            if ($existingPatrol) {
                return response()->json(['success' => false, 'message' => 'Anda telah menscan QR sebelumnya di lokasi ini'], 409);
            }

            // Temukan checkpoint terkait dengan patroli yang sedang ditampilkan
            $patrolCheckpoint = Patrol::findOrFail($request->patrol_id)->checkpoint;

            // Periksa apakah kode checkpoint yang dipindai sama dengan kode checkpoint yang ditampilkan
            if ($patrolCheckpoint->code !== $checkpoint->code) {
                return response()->json(['success' => false, 'error' => 'Kode checkpoint yang dipindai tidak cocok dengan checkpoint yang ditampilkan'], 400);
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
            return response()->json([
                'success' => true,
                'message' => 'Patroli berhasil dibuat',
                'status_code' => 200,
                'patrol_status' => $patrol->status,
                'patrol_id' => $patrol->id
            ], 200);
        } else {
            // Jika kode checkpoint tidak valid, kembalikan respons JSON dengan pesan error
            return response()->json(['success' => false, 'error' => 'Kode checkpoint tidak valid'], 400);
        }
    }

    public function report($patrolId)
    {
        try {
            // Temukan patroli berdasarkan ID yang diberikan
            $patrol = Patrol::with('photos')->findOrFail($patrolId);
            // Kembalikan data patroli dalam respons JSON
            return response()->json([
                'success' => true,
                'patrol' => $patrol
            ]);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons JSON dengan pesan error
            return response()->json(['success' => false, 'message' => 'Gagal menemukan patroli'], 404);
        }
    }

    public function report_store(Request $request, $patrolId)
    {
        try {
            // Temukan patroli berdasarkan ID yang diberikan
            $patrol = Patrol::findOrFail($patrolId);

            // Validasi data yang diterima dari form
            $request->validate([
                'information' => 'required|string|max:255',
                'patrol_photos.*' => 'required',
            ]);

            // Simpan informasi laporan yang diberikan ke dalam patroli
            $patrol->information = $request->input('information');
            $patrol->save();

            if ($request->has('patrol_photos')) {
                $patrolPhotos = [];

                foreach ($request->input('patrol_photos') as $base64Image) {
                    $imageData = base64_decode($base64Image);
                    $imageName = time() . '_' . Str::random(10) . '.jpg'; // Generate unique name
                
                    // Simpan file foto ke dalam penyimpanan yang diinginkan
                    $path = 'patrol_photos/' . $imageName;
                    file_put_contents(public_path($path), $imageData);
                
                    // Simpan path file foto ke dalam tabel patrol_photos
                    $patrolPhoto = new PatrolPhoto();
                    $patrolPhoto->patrol_id = $patrol->id;
                    $patrolPhoto->file_path = $path;
                    $patrolPhoto->save();
                
                    // Tambahkan foto patroli yang baru disimpan ke dalam array
                    $patrolPhotos[] = $patrolPhoto;
                }
                
                // Ubah status patroli menjadi "completed"
                $patrol->status = 'completed';
                $patrol->save();
            }

            // Kembalikan respons JSON dengan pesan sukses
            return response()->json(['success' => true, 'message' => 'Informasi laporan berhasil disimpan.', 'photos' => $patrolPhotos]);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons JSON dengan pesan error
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function photo_store(Request $request, $patrolId)
    {
        try {
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

            // Kembalikan respons JSON dengan pesan sukses
            return response()->json(['success' => true, 'message' => 'Foto berhasil diunggah.']);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons JSON dengan pesan error
            return response()->json(['success' => false, 'message' => 'Gagal mengunggah foto'], 500);
        }
    }

    public function report_update(Request $request, $patrolId)
    {
        try {
            // Temukan patroli berdasarkan ID yang diberikan
            $patrol = Patrol::findOrFail($patrolId);

            // Validasi data yang diterima dari form
            $request->validate([
                'information' => 'nullable|string|max:255',
            ]);

            // Simpan informasi laporan yang diberikan ke dalam patroli
            $patrol->information = $request->input('information');
            $patrol->save();

            // Kembalikan respons JSON dengan pesan sukses
            return response()->json(['success' => true, 'message' => 'Informasi laporan berhasil diperbarui.']);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons JSON dengan pesan error
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui informasi laporan'], 500);
        }
    }

    public function photo_update(Request $request, $patrolId)
    {
        try {
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

            // Kembalikan respons JSON dengan pesan sukses
            return response()->json(['success' => true, 'message' => 'Foto berhasil diperbarui.']);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons JSON dengan pesan error
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui foto'], 500);
        }
    }


    public function checkQR(Request $request)
    {
        // Validasi data yang diterima dari hasil pemindaian QR
        $request->validate([
            'checkpoint_code' => 'required|string|max:255',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        // Temukan checkpoint berdasarkan kode yang diterima dari hasil QR
        $checkpoint = Checkpoint::where('code', $request->checkpoint_code)->first();

        // Periksa apakah checkpoint ditemukan
        if ($checkpoint) {
            // Ambil ID karyawan yang saat ini diotentikasi menggunakan guard 'api'
            $employeeId = Auth::guard('api')->id();

            // Periksa apakah karyawan yang sedang login telah melakukan patroli di checkpoint ini sebelumnya pada hari ini
            $existingPatrol = Patrol::where('employee_id', $employeeId)
                ->where('checkpoint_id', $checkpoint->id)
                ->whereDate('created_at', today()) // Filter berdasarkan tanggal hari ini
                ->first();

            // Jika karyawan telah melakukan patroli di checkpoint ini sebelumnya pada hari ini, kembalikan respons JSON dengan pesan peringatan
            if ($existingPatrol) {
                return response()->json([
                    'success' => false,
                    'status_code' => 409,
                    'message' => 'Anda telah menscan QR sebelumnya di lokasi ini hari ini'
                ], 409);
            }

            // Jika karyawan belum melakukan patroli di checkpoint ini sebelumnya pada hari ini, tambahkan log pemindaian QR ke database
            // Patrol::create([
            //     'employee_id' => $employeeId,
            //     'checkpoint_id' => $checkpoint->id,
            //     'longitude' => $request->longitude,
            //     'latitude' => $request->latitude,
            //     'date' => now()->toDateString(), // Tandai tanggal pemindaian QR
            //     'time' => now()->toTimeString(), // Tandai waktu pemindaian QR
            //     'created_at' => now(), // Tandai waktu pemindaian QR
            // ]);

            // Jika karyawan belum melakukan patroli di checkpoint ini sebelumnya dan lokasi cocok, buat data patroli baru
            $patrol = new Patrol();
            $patrol->employee_id = $employeeId;
            $patrol->checkpoint_id = $checkpoint->id;
            $patrol->longitude = $request->longitude;
            $patrol->latitude =  $request->latitude;
            $patrol->date = now()->toDateString();
            $patrol->time = now()->toTimeString();
            $patrol->status = 'pending';
            $patrol->save();

            // Kembalikan respons JSON dengan pesan sukses
            return response()->json([
                'success' => true,
                'message' => 'Patroli berhasil dibuat',
                'status_code' => 200,
                'patrol' => $patrol,
                'patrol_id' => $patrol->id
            ], 200);
        } else {
            // Jika kode checkpoint tidak valid, kembalikan respons JSON dengan pesan error
            return response()->json([
                'success' => false,
                'status_code' => 400,  'error' => 'Kode checkpoint tidak valid'
            ], 400);
        }
    }
}
