<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('employee')->user();
        $shift = $employee->shift;
        $presence = Presence::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();
        $presenceOut = false;

        // Jika sudah ada data absen dan waktu absen pulang sudah diisi, maka set status absen pulang menjadi true
        if ($presence && $presence->time_out) {
            $presenceOut = true;
        }

        return view('employee.presences.index', compact('employee', 'shift', 'presenceOut', 'presence'));
    }

    public function store(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $shift = $employee->shift;

        if (!$shift) {
            return response()->json(['error' => 'Anda tidak memiliki shift pada waktu ini.'], 400);
        }

        $currentTime = Carbon::now()->format('H:i');
        if ($currentTime < $shift->time_in || $currentTime > $shift->time_out) {
            return response()->json(['error' => 'Anda tidak dapat melakukan absen di luar jam kerja.'], 400);
        }

        // Periksa apakah karyawan sudah melakukan absen hari ini
        $presence = Presence::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        // Jika belum absen, simpan data absen masuk
        if (!$presence) {
            $presence = new Presence();
            $presence->employee_id = $employee->id;
            $presence->date = Carbon::today();
            $presence->time_in = $currentTime;
            $presence->latitude_longitude_in = $request->latitude_longitude;

            if ($request->has('resultCapture')) {
                $image = $request->resultCapture;  // your base64 encoded
                $image = str_replace('data:image/jpeg;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'presence_in_' . time() . '.jpeg';
                \File::put(public_path() . '/presence_images/' . $imageName, base64_decode($image));
                $presence->picture_in = 'presence_images/' . $imageName;
            }


            $presence->save();

            return response()->json([
                'success' => true,
                'message' => 'Absen masuk berhasil disimpan.',
                'presence' => $presence,
                'redirect' => route('employee.dashboard')
            ], 200);
        } else {
            // Jika sudah absen masuk, tandai sebagai absen pulang
            if ($presence->time_out) {
                return response()->json(['message' => 'Anda sudah melakukan absen masuk dan pulang hari ini.'], 400);
            }

            $presence->time_out = $currentTime;
            $presence->latitude_longitude_out = $request->latitude_longitude;

            if ($request->has('resultCapture')) {
                $image = $request->resultCapture;  // your base64 encoded
                $image = str_replace('data:image/jpeg;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'presence_out_' . time() . '.jpeg';
                \File::put(public_path() . '/presence_images/' . $imageName, base64_decode($image));
                $presence->picture_out = 'presence_images/' . $imageName;
            }

            $presence->save();

            return response()->json([
                'success' => true,
                'message' => 'Absen pulang berhasil disimpan.',
                'presence' => $presence,
                'redirect' => route('employee.dashboard')
            ], 200);
        }
    }
}
