<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('employee.auth.login');
    }

    public function authenticate(Request $request)
    {
        // Validate the incoming request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::guard('employee')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('employee.dashboard');
        }

        // Authentication failed, redirect back with error message
        return back()->with('error', 'Cek kembali email dan password anda');
    }


    public function logout()
    {
        Auth::guard('employee')->logout();
        return redirect('/login');
    }
}
