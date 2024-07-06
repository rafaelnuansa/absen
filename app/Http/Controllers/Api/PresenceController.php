<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        $employee = Auth::guard('api')->user();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate && !$endDate) {
            // Jika tidak ada filter, tentukan tanggal awal dan akhir bulan saat ini
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        $presencesQuery = Presence::where('employee_id', $employee->id);

        if ($startDate && $endDate) {
            // Filter berdasarkan start_date dan end_date jika disediakan
            $presencesQuery->whereBetween('date', [$startDate, $endDate]);
        }

        $presence = $presencesQuery->first();
        $presenceOut = $presence && $presence->time_out;

        return response()->json([
            'employee' => $employee,
            'presenceOut' => $presenceOut,
            'presence' => $presence
        ]);
    }

    public function store(Request $request)
    {
        try {
            $employee = Auth::guard('api')->user();

            // Cek apakah hari ini adalah hari Minggu
            // if (Carbon::now()->isSunday()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Hari Minggu libur, tidak dapat melakukan absen.'
            //     ], 400);
            // }

            $currentTime = Carbon::now();

            // Tentukan shift secara otomatis berdasarkan waktu saat ini
            if ($currentTime->between(Carbon::createFromTimeString('06:00'), Carbon::createFromTimeString('15:00'))) {
                $shift = 1;

                // Batasan waktu masuk dan keluar untuk shift 1
                $shiftInStart1 = Carbon::createFromTimeString('06:00');
                $shiftInEnd1 = Carbon::createFromTimeString('15:00');
                $shiftOutStart1 = Carbon::createFromTimeString('19:00');
                $shiftOutEnd1 = Carbon::createFromTimeString('21:00');
            } elseif ($currentTime->between(Carbon::createFromTimeString('18:00'), Carbon::createFromTimeString('22:00'))) {
                $shift = 2;
                // Batasan waktu masuk dan keluar untuk shift 2
                $shiftInStart2 = Carbon::createFromTimeString('18:00');
                $shiftInEnd2 = Carbon::createFromTimeString('21:00');
                $shiftOutStart2 = Carbon::createFromTimeString('06:00')->addDay();
                $shiftOutEnd2 = Carbon::createFromTimeString('09:00')->addDay();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Waktu absen tidak ada dalam shift'
                ], 400);
            }

            // Periksa apakah sudah ada kehadiran hari ini dengan time_in tetapi tanpa time_out
           // Check if there is an incomplete presence record for yesterday's shift 2
            $yesterdayPresence = Presence::where('employee_id', $employee->id)
                ->whereDate('date', Carbon::yesterday())
                ->where('shift', 2)
                ->whereNotNull('time_in')
                ->whereNull('time_out')
                ->first();

        // Check if there is already a presence record for today with time_in but without time_out
                $presence = Presence::where('employee_id', $employee->id)
                    ->whereDate('date', Carbon::today())
                    ->whereNotNull('time_in')
                    ->whereNull('time_out')
                    ->first();
        
                // If yesterday's presence exists and is still ongoing, use it
                if ($yesterdayPresence) {
                    $shift2EndTime = Carbon::yesterday()->addDay()->setTime(9, 0);
        
                    if (Carbon::now()->lessThan($shift2EndTime)) {
                        $presence = $yesterdayPresence;
                    }
                }

            if ($presence) {

                if ($presence->shift == 1) {
                    // Pastikan $presence->date adalah objek Carbon sebelum menggunakan copy() dan setTime()
                    $shiftOutStart1 = Carbon::parse($presence->date)->copy()->setTime(19, 0, 0);
                    $shiftOutEnd1 = Carbon::parse($presence->date)->copy()->setTime(21, 0, 0);

                    if ($currentTime->lessThan($shiftOutStart1) || $currentTime->greaterThan($shiftOutEnd1)) {
                        \Log::error('Gagal Absen pulang shift 1. User: ' . $employee->id);
                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal Absen pulang shift 1'
                        ], 400);
                    }
                } elseif ($presence->shift == 2) {
                    // Pastikan $presence->date adalah objek Carbon sebelum menggunakan copy() dan setTime()
                    $shiftOutStart2 = Carbon::parse($presence->date)->copy()->addDay()->setTime(6, 0);
                    $shiftOutEnd2 = Carbon::parse($presence->date)->copy()->addDay()->setTime(13, 0);

                    if ($currentTime->lessThan($shiftOutStart2) || $currentTime->greaterThan($shiftOutEnd2)) {
                        \Log::error($shiftOutStart2);
                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal Absen pulang shift 2'
                        ], 400);
                    }
                }

                $presence->time_out = $currentTime->format('H:i');
                $presence->latitude_longitude_out = $request->latitude_longitude;

                // Simpan foto jika tersedia
                if ($request->has('resultCapture')) {
                    $image = $request->resultCapture;  // gambar terenkripsi base64
                    $image = str_replace('data:image/jpeg;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = 'presence_out_' . time() . '.jpeg';
                    \File::put(public_path() . '/presence_images/' . $imageName, base64_decode($image));
                    $presence->picture_out = 'presence_images/' . $imageName;
                }

                $presence->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Absen pulang berhasil disimpan',
                    'presence' => $presence,
                ], 200);
            } else {
                // Validasi batas waktu masuk
                if ($shift == 1 && !$currentTime->between($shiftInStart1, $shiftInEnd1)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal Absen masuk shift 1'
                    ], 400);
                }

                if ($shift == 2 && !$currentTime->between($shiftInStart2, $shiftInEnd2)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal Absen masuk shift 2'
                    ], 400);
                }

                // Jika tidak ada kehadiran, buat catatan kehadiran untuk check-in
                $presence = new Presence();
                $presence->employee_id = $employee->id;
                $presence->date = Carbon::today();
                $presence->time_in = $currentTime->format('H:i');
                $presence->shift = $shift;
                $presence->latitude_longitude_in = $request->latitude_longitude;

                // Simpan foto jika tersedia
                if ($request->has('resultCapture')) {
                    $image = $request->resultCapture;  // gambar terenkripsi base64
                    $image = str_replace('data:image/jpeg;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = 'presence_in_' . time() . '.jpeg';
                    \File::put(public_path() . '/presence_images/' . $imageName, base64_decode($image));
                    $presence->picture_in = 'presence_images/' . $imageName;
                }

                $presence->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Absen masuk berhasil shift : ' . $shift,
                    'presence' => $presence,
                ], 200);
            }
        } catch (\Exception $e) {
            // Tangani pengecualian di sini
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan : ' . $e->getMessage()
            ], 500);
        }
    }
}
