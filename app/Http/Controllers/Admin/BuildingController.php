<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;

class BuildingController extends Controller
{
    // Display a listing of the buildings.
    public function index()
    {
        $buildings = Building::withCount('employees')->latest()->get();
        return view('admin.buildings.index', compact('buildings'));
    }

    // Show the form for creating a new building.
    public function create()
    {
        return view('admin.buildings.create');
    }

    // Store a newly created building in storage.
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:50',
            'address' => 'required|string|max:100',
            // 'building_scanner' => 'required|string|max:50',
        ]);

        Building::create($request->all());

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Building created successfully.');
    }

    // Show the form for editing the specified building.
    public function edit(Building $building)
    {
        return view('admin.buildings.edit', compact('building'));
    }

    // Update the specified building in storage.
    public function update(Request $request, Building $building)
    {
        $request->validate([
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:50',
            'address' => 'required|string|max:100',
            // 'building_scanner' => 'required|string|max:50',
        ]);

        $building->update($request->all());

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Building updated successfully.');
    }

    // Remove the specified building from storage.
    public function destroy(Building $building)
    {
        $building->delete();

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Building deleted successfully.');
    }
}
