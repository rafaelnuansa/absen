<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EmployeeExport;
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Employee;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Query awal untuk mengambil semua data employee
        $query = Employee::query();
    
        // Cek apakah ada pencarian yang dilakukan
        if ($request->has('search')) {
            // Filter data berdasarkan code atau name employee
            $search = $request->search;
            $query->where('code', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%');
        }
    
        // Filter berdasarkan is_active
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }
    
        // Filter berdasarkan position
        if ($request->has('position_id')) {
            $query->where('position_id', $request->position_id);
        }
    
        // Filter berdasarkan building
        if ($request->has('building_id')) {
            $query->where('building_id', $request->building_id);
        }
    
        // Ambil data dengan relasi position dan building
        $employees = $query->with(['position', 'building'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        // Ambil data posisi untuk dropdown filter
        $positions = Position::all();
    
        // Ambil data building untuk dropdown filter
        $buildings = Building::all();
    
        // Inisialisasi variabel $positionFilter dan $buildingFilter
        $positionFilter = Position::find($request->position_id);
        $buildingFilter = Building::find($request->building_id);
    
        // Tampilkan halaman index dengan data employee yang sudah difilter
        return view('admin.employees.index', compact('employees', 'positions', 'buildings', 'positionFilter', 'buildingFilter'));
    }
    

    public function show($id)
    { {
            $employee = Employee::findOrFail($id);
            return view('admin.employees.show', $employee);
        }
    }

    public function create()
    {
        $positions = Position::all();
        $buildings = Building::all();
        // Return view for creating a new employee
        return view('admin.employees.create', compact('positions', 'buildings'));
    }

    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'code' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'name' => 'required',
                'position_id' => 'required|numeric',
                'building_id' => 'required|numeric',
                'avatar' => 'nullable|image|max:1024', // Assuming avatar is uploaded
            ]);

            // Create new employee
            $employee = new Employee();
            $employee->code = $request->code;
            $employee->email = $request->email;
            $employee->password = bcrypt($request->password);
            $employee->name = $request->name;
            $employee->position_id = $request->position_id;
            $employee->building_id = $request->building_id;
            $employee->is_active = $request->is_active;
            // Handle avatar upload if provided
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $image->storeAs('public/avatars', $image->hashName());
                $employee->avatar = $image->hashName();
            }


            // Save the employee
            $employee->save();

            // Redirect to index with success message
            return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            // If an error occurs, redirect back with error message
            return redirect()->back()->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }


    public function edit(Employee $employee)
    {
        // Return view for editing an existing employee

        $positions = Position::all();
        $buildings = Building::all();
        return view('admin.employees.edit', compact('employee', 'positions', 'buildings'));
    }

    public function update(Request $request, Employee $employee)
    {
        try {
            // Validate incoming request
            $request->validate([
                'code' => 'required',
                'email' => 'required|email',
                'password' => 'nullable|min:6',
                'name' => 'required',
                'position_id' => 'required|numeric',
                'building_id' => 'required|numeric',
                'avatar' => 'nullable|image|max:2024', // Assuming avatar is uploaded
            ]);

            if($request->password) {
                    $employee->update([
                'code' => $request->code,
                'email' => $request->email,
                'password' =>bcrypt($request->password),
                'name' => $request->name,
                'position_id' => $request->position_id,
                'building_id' => $request->building_id,
                'is_active' => $request->is_active,
            ]);
                
            }else {
                $employee->update([
                'code' => $request->code,
                'email' => $request->email,
                'name' => $request->name,
                'position_id' => $request->position_id,
                'building_id' => $request->building_id,
                'is_active' => $request->is_active,
            ]);
            }
            if ($request->hasFile('avatar')) {

                Storage::delete('public/avatars/' . $employee->avatar);
                $image = $request->file('avatar');
                $image->storeAs('public/avatars', $image->hashName());
                $employee->avatar = $image->hashName();
                $employee->save();
            }


            // Redirect to index with success message
            return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            // If an error occurs, redirect back with error message
            return redirect()->back()->with('error', 'Failed to update employee: ' . $e->getMessage());
        }
    }

    public function destroy(Employee $employee)
    {
        // Delete avatar if exists
        if ($employee->avatar) {
            Storage::delete('public/avatars/' . $employee->avatar);
        }

        // Delete employee
        $employee->delete();

        // Redirect to index with success message
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function search(Request $request)
    {
        // Ambil kata kunci pencarian dari request
        $keyword = $request->input('q');

        // Cari karyawan berdasarkan nama yang cocok dengan kata kunci pencarian
        $employees = Employee::where('name', 'like', "%{$keyword}%")->get();

        // Format data untuk respons JSON
        $formattedEmployees = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->name,
            ];
        });
        return response()->json($formattedEmployees);
    }

    public function export(Request $request)
    {
        // Get filter parameters from the request
        $buildingId = $request->input('building_id');
        $positionId = $request->input('position_id');
        $isActive = $request->input('is_active');
    
        // Create a filename for the exported file
        $filename = 'employees_' . Carbon::now()->format('Y_m_d') . '.xlsx';
    
        // Export the employee data to an Excel file
        return Excel::download(new EmployeeExport($buildingId, $positionId, $isActive), $filename);
    }
    
 
}
