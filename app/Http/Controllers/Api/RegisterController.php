<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Validation rules for registration data
            $validator = Validator::make($request->all(), [
                'code' => 'required|numeric',
                'name' => 'required|string',
                'email' => 'required|email|unique:employees,email',
                'password' => 'required|string|min:6',
                'position_id' => 'required|exists:positions,id',
                'building_id' => 'required|exists:buildings,id',
                'avatar' => 'required|image|max:2024', // Assuming avatar is uploaded
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => implode(' ', $validator->errors()->all()),
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // Check if position exists
            $position = Position::findOrFail($request->position_id);
            // Check if building exists
            $building = Building::findOrFail($request->building_id);

            // Create new employee instance
            $employee = new Employee();
            $employee->code = $request->code;
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->password = Hash::make($request->password);

            // Assign position, shift, and building to the employee
            $employee->position()->associate($position);
            $employee->building()->associate($building);

            // Handle avatar upload if provided
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $image->storeAs('public/avatars', $image->hashName());
                $employee->avatar = $image->hashName();
            }

            // Save the new employee
            $employee->save();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Registration successful'
            ], 201);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }


    // Method to fetch positions
    public function fetchPositions()
    {
        $positions = Position::all();
        return response()->json([
            'success' => true,
            'positions' => $positions
        ]);
    }

    // Method to fetch buildings
    public function fetchBuildings()
    {
        $buildings = Building::all();
        return response()->json([
            'success' => true,
            'buildings' => $buildings
        ]);
    }

}
