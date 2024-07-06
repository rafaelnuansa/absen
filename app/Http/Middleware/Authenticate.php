<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            // Periksa apakah pengguna autentikasi melalui API
            if (auth()->guard('api')) {
                // Jika autentikasi API, kembalikan respons JSON
                return response()->json(['error' => 'Unauthenticated.'], 401);
            } else {
                // Jika autentikasi melalui sesi, redirect ke '/'
                return route('admin.login'); // Sesuaikan dengan rute yang sesuai
            }
        }
    }
}
