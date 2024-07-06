<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('admin.shifts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'required|date_format:H:i',
        ]);

        Shift::create($request->all());

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Shift created successfully.');
    }

    public function show(Shift $shift)
    {
        // return view('admin.shifts.show', compact('shift'));
    }

    public function edit(Shift $shift)
    {
        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name' => 'required|max:20',
            'time_in' => 'required',
            'time_out' => 'required',
        ]);

        // dd($request);
        $shift->update($request->all());

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Shift updated successfully');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Shift deleted successfully');
    }
}
