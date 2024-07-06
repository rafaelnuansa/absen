<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the current user is authenticated as an employee
        if (Auth::guard('employee')->check()) {
            return $next($request);
        }

        // If not, redirect to the employee login page with a message
        return redirect()->route('employee.login')->with('error', 'Unauthorized access');
    }
}
