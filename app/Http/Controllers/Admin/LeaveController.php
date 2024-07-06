<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::query();

        if ($request->has('search')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
            $query->where('start_date', '>=', $startDate);
        }

        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->format('Y-m-d');
            $query->where('end_Date', '<=', $endDate);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->latest()->paginate(10);

        return view('admin.leaves.index', compact('leaves'));
    }


    public function show($id)
    {
        $leave = Leave::findOrFail($id);
        return view('admin.leaves.show', compact('leave'));
    }

    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Leave request has been approved.');
    }

    public function decline($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Leave request has been declined.');
    }


}
