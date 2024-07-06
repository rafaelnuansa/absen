<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Encryption\Encrypter;

class SSOController extends Controller
{


public function handleSSO($employeeId, $employeePassword)
{
    $employee = Employee::where('id', $employeeId)
                        ->where('employees_password', $employeePassword)
                        ->first();

    if ($employee) {
        // Otentikasi employee menggunakan guard 'employee'
        Auth::guard('employee')->loginUsingId($employee->id);

        // Redirect ke halaman dashboard employee
        return redirect()->route('employee.dashboard');
    } else {
        // Jika employee tidak ditemukan, kembalikan respon error
        return response()->json(['error' => 'Employee not found'], 404);
    }
}

    public function handleSSOAdmin($userId, $userPassword)
    {
        $user = User::where('user_id', $userId)
            ->where('password', $userPassword)
            ->first();
        if ($user) {
            // Jika pengguna ditemukan, lakukan otentikasi dan arahkan ke dashboard admin
            Auth::guard('admin')->loginUsingId($user->user_id);
            return redirect()->route('admin.dashboard');
        } else {
            // Jika pengguna tidak ditemukan, kembalikan respons error
            return response()->json(['error' => 'User not found'], 404);
        }
    }

}
