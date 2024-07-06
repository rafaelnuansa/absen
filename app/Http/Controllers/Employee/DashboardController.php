<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->guard('employee')->check()) {
            return view('employee.auth.login');
        }

        $employee = Auth::guard('employee')->user();
        $shift = $employee->shift;
        $presence = Presence::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();
        return view('employee.dashboard.index', compact('presence'));
    }

    public function darkmode(Request $request)
    {
        // Togle status dark mode
        $darkMode = $request->session()->get('dark_mode', false);
        $request->session()->put('dark_mode', !$darkMode);

        // Redirect kembali ke halaman sebelumnya
        return redirect()->back();
    }
}
