<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::all();
        return view('admin.positions.index', compact('positions'));
    }

    public function create()
    {
        return view('admin.positions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:30',
        ]);

        Position::create($request->all());

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position created successfully.');
    }

    public function show(Position $position)
    {
        // return view('admin.positions.show', compact('position'));
    }

    public function edit(Position $position)
    {
        return view('admin.positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|max:30',
        ]);

        $position->update($request->all());

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position updated successfully');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position deleted successfully');
    }
}
