<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AbsensiSessionMiddleware
{
    public function handle($request, Closure $next)
    {
        // Ambil session user dan session ID dari session absensi
        $sessionUser = $_SESSION['SESSION_USER'] ?? null;
        $sessionId = $_SESSION['SESSION_ID'] ?? null;

        // Periksa apakah session user dan session ID tidak kosong
        if (!empty($sessionUser) && !empty($sessionId)) {
            // Lakukan validasi sesi di database aplikasi absensi
            $count = DB::table('user')
                ->where('session', $sessionUser)
                ->where('user_id', $sessionId)
                ->count();

            // Jika sesi valid, autentikasi pengguna di Laravel
            if ($count > 0) {
                $user = DB::table('user')->find($sessionId);
                Auth::loginUsingId($user->id);
                return $next($request);
            }
        }

        // Jika session user atau session ID kosong, redirect ke halaman login
        return redirect('/login');
    }
}
