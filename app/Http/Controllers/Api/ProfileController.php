<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('api')->user();
        $shift = $employee->shift;
        $position = $employee->position;
        $building = $employee->building;
        return response()->json([
            'success' => true,
            'profile' => $employee,
            'shift' => $shift,
            'building' => $building,
            'position' => $position
        ]);
    }

    public function update(Request $request)
    {
        $employee = Auth::guard('api')->user();
        try {
            // Validate incoming request
            $request->validate([
                'current_password' => 'required|min:6',
                'password' => 'required|min:6|confirmed',
            ]);

            // Check if the current password matches
            if (!Hash::check($request->current_password, $employee->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password saat ini salah.',
                ], 400);
            }

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $employee->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Update Sukses.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage(),
            ], 500);
        }
    }


    public function update_avatar(Request $request)
    {
        $employee = Auth::guard('api')->user();
        try {
            if ($request->hasFile('avatar')) {
                // Delete previous avatar
                if ($employee->avatar) {
                    Storage::delete('public/avatars/' . $employee->avatar);
                }

                // Store new avatar
                $image = $request->file('avatar');
                $image->storeAs('public/avatars', $image->hashName());

                // Update employee's avatar
                $employee->avatar = $image->hashName();
                $employee->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Avatar updated successfully.',
                    'avatar_url' => asset('storage/avatars/' . $employee->avatar)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No avatar uploaded.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update avatar. ' . $e->getMessage()
            ], 500);
        }
    }
}
