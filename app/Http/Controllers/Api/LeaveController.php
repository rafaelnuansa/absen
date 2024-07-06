<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        // Get the current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;
    
        // Get the ID of the logged-in employee
        $employeeId = Auth::guard('api')->id();
    
        // Apply filters if start_date and end_date are provided in the request
        $leavesQuery = Leave::where('employee_id', $employeeId)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth);
    
        // Check if start_date and end_date are provided and not null
        if ($request->has('start_date') && $request->has('end_date') &&
            $request->input('start_date') !== null && $request->input('end_date') !== null) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $leavesQuery->whereBetween('start_date', [$start_date, $end_date])
                ->orWhereBetween('end_date', [$start_date, $end_date]);
        }
    
        // Get leave data based on the applied filters
        $leaves = $leavesQuery->latest()->get();
    
        // If no filters are applied or start_date and end_date are null, 
        // get leaves from the beginning of the current month to the end of the current month
        if (!$request->has('start_date') || !$request->has('end_date') ||
            $request->input('start_date') === null || $request->input('end_date') === null) {
            $leaves = Leave::where('employee_id', $employeeId)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->latest()
                ->get();
        }
    
        // Return leave data as JSON response
        return response()->json(
            [
                'success' => true,
                'leaves' => $leaves,
                'message' => 'Permission/Leaves fetched successfully'
            ]
        );
    }
    
    public function store(Request $request)
{
    try {
        // Validate data received from the form
        $validatedData = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'date_work' => 'required|date|after_or_equal:end_date',
            'total' => 'required|integer',
            'reason' => 'required|string',
            'type' => 'required|string',
            'image' => 'nullable|string', // Image data in base64 format
        ]);

        // Get the ID of the logged-in employee
        $employeeId = Auth::guard('api')->id();

        // Handle image upload if provided in base64 format
        $imagePath = null;
        if ($request->filled('image')) {
            // Decode base64 image data
            $imageData = base64_decode($request->image);

            // Generate a unique filename
            $imageName = time() . '.jpg'; // You can use any desired extension

            // Save the image to the storage directory
            $savePath = public_path('/leave_images/') . $imageName;
            file_put_contents($savePath, $imageData);

            // Set the image path
            $imagePath = 'leave_images/' . $imageName;
        }

        // Create a new Leave object and fill it with validated data
        $leave = new Leave();
        $leave->employee_id = $employeeId;
        $leave->start_date = $validatedData['start_date'];
        $leave->end_date = $validatedData['end_date'];
        $leave->date_work = $validatedData['date_work'];
        $leave->total = $validatedData['total'];
        $leave->reason = $validatedData['reason'];
        $leave->type = $validatedData['type'];
        $leave->image = $imagePath;
        $leave->save();

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Leave request successfully saved.'
        ]);
    } catch (\Exception $e) {
        // Handle any exceptions here
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 422); // Return a 422 status code for validation errors
    }
}

    
    public function update(Request $request)
{
    // Validate the request
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'date_work' => 'required|date|after_or_equal:end_date',
        'total' => 'required|integer',
        'reason' => 'required|string',
        'type' => 'required|string',
        'id' => 'required|exists:leaves,id',
        'image' => 'nullable|string', // Image data in base64 format
    ]);

    // Get the leave data to update
    $leave = Leave::findOrFail($request->id);

    // Get the ID of the logged-in employee
    $employeeId = Auth::guard('api')->id();

    // Check if the user has permission to edit the leave
    if ($leave->employee_id !== $employeeId) {
        // If not authorized, return an error response
        return response()->json([
            'success' => false,
            'message' => 'You do not have permission to edit this leave.'
        ], 403);
    }

     // Handle image upload if provided in base64 format
     $imagePath = $leave->image;
     if ($request->filled('image')) {
         // Decode base64 image data
         $imageData = base64_decode($request->image);
 
         // Generate a unique filename
         $imageName = time() . '.jpg'; // You can use any desired extension
 
         // Save the image to the storage directory
         $savePath = public_path('/leave_images/') . $imageName;
         file_put_contents($savePath, $imageData);
 
         // Set the image path
         $imagePath = 'leave_images/' . $imageName;
 
         // Delete the old image if it exists
         if ($leave->image) {
             $oldImagePath = public_path($leave->image);
             if (file_exists($oldImagePath)) {
                 unlink($oldImagePath);
             }
         }
     } elseif ($request->missing('image')) {
         // If no image provided or the image field is missing,
         // set the image path to null or empty string as needed
         $imagePath = null; // or $imagePath = '';
 
         // Delete the old image if it exists
         if ($leave->image) {
             $oldImagePath = public_path($leave->image);
             if (file_exists($oldImagePath)) {
                 unlink($oldImagePath);
             }
         }
     }
    // Update the leave data
    $leave->update([
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'date_work' => $request->date_work,
        'total' => $request->total,
        'reason' => $request->reason,
        'type' => $request->type,
        'image' => $imagePath,
    ]);

    // Return a success response
    return response()->json([
        'success' => true,
        'message' => 'Leave successfully updated.'
    ]);
}

    
}
