<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the current user is authenticated as admin
        if (Auth::check()) {
            return $next($request);
        }

        // If not, redirect to the admin login page with a message
        return redirect()->route('admin.login')->with('warning', 'Mohon login terlebih dahulu');
    }
}
