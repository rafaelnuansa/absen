<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated employee
        $employee = Auth::guard('api')->user();

        // Get today's presence record for the employee
        $todayPresence = Presence::where('employee_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        $presenceToDisplay = $todayPresence;

        if (!$todayPresence) {
            // If there is no presence for today, check yesterday's presence
             $yesterdayPresence = Presence::where('employee_id', $employee->id)
                ->whereDate('date', Carbon::yesterday())
                ->where('shift', 2)
                ->first();

            if ($yesterdayPresence) {
                // End time for shift 2 is 09:00 the next day
                $shift2EndTime = Carbon::yesterday()->addDay()->setTime(9, 0);
                if (Carbon::now()->lessThan($shift2EndTime)) {
                    $presenceToDisplay = $yesterdayPresence;
                }
            }
            
         \Log::error($shift2EndTime);
        }
        // Get the start and end dates of the current month
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Get the presence records for the current month for the employee
        $presences = Presence::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        // Initialize counters for total counts
        $totalPresent = 0;
        $totalPermission = 0;
        $totalSick = 0;
        $totalLate = 0;

        // Calculate the total counts based on the status of each presence record
        foreach ($presences as $presence) {
            switch ($presence->status) {
                case 'present':
                    $totalPresent++;
                    break;
                case 'on_leave':
                    $totalPermission++;
                    break;
                case 'sick':
                    $totalSick++;
                    break;
                case 'absent':
                    $scheduledTimeIn = Carbon::parse($presence->date)->setTimeFromTimeString($presence->shift == 2 ? '18:00' : '06:00');
                    $actualTimeIn = Carbon::parse($presence->time_in);
                    $lateness = max(0, $actualTimeIn->diffInMinutes($scheduledTimeIn));
                    $totalLate += $lateness;
                    break;
            }
        }

        // Prepare the response data
        $responseData = [
            'employee' => $employee,
            'presence' => $presenceToDisplay,
            'total_present' => $totalPresent,
            'total_permission' => $totalPermission,
            'total_sick' => $totalSick,
            'total_late' => $totalLate
        ];

        // Return the response as JSON
        return response()->json($responseData);
    }

}
