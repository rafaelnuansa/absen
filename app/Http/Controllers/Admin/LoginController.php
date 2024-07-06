<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{public function index()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
    
        return view('auth.admin.login');
    }
    
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->with([
            'error' => 'Mohon cek kembali email / password anda.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout(); // Melakukan logout dari sistem
        $request->session()->invalidate(); // Mematikan sesi pengguna
        $request->session()->regenerateToken(); // Membuat token baru untuk sesi
        return redirect('/'); // Redirect pengguna ke halaman login setelah logout
    }

}
