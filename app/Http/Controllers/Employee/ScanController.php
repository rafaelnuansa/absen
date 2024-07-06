<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('employee.scan.index');
    }
}
