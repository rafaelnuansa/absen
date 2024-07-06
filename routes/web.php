<?php
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Route::middleware([AdminMiddleware::class])->get('/', [App\Http\Controllers\Admin\DashboardController::class, '__invoke'])->name('employee.dashboard');

Route::get('/', [App\Http\Controllers\Admin\LoginController::class, 'index'])->name('admin.login');
Route::get('/login', [App\Http\Controllers\Admin\LoginController::class, 'index'])->name('admin.login');
Route::post('/login', [App\Http\Controllers\Admin\LoginController::class, 'authenticate'])->name('admin.login');
Route::get('/logout', [App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('admin.logout');

Route::middleware([AdminMiddleware::class])->group(function () {
    // Tempatkan route yang perlu dilindungi oleh AdminMiddleware di sini
    // Route::get('/', App\Http\Controllers\Admin\DashboardController::class)->name('admin.dashboard');
    Route::get('/dashboard', App\Http\Controllers\Admin\DashboardController::class)->name('admin.dashboard');
    Route::post('/checkpoint/enroll', [App\Http\Controllers\Admin\CheckpointController::class, 'enrollEmployeeToCheckpoint'])->name('admin.checkpoint.enrollEmployee');
    Route::post('/checkpoint/unroll', [App\Http\Controllers\Admin\CheckpointController::class, 'unenrollEmployeeFromCheckpoint'])->name('admin.checkpoint.unenrollEmployee');

    Route::resource('/checkpoint', App\Http\Controllers\Admin\CheckpointController::class)->names('admin.checkpoint');
    Route::resource('/checkin', App\Http\Controllers\Admin\CheckinController::class)->names('admin.checkin');
    
    Route::get('/employees/export', [App\Http\Controllers\Admin\EmployeeController::class, 'export'])->name('admin.employees.export');
    Route::resource('/employees', App\Http\Controllers\Admin\EmployeeController::class)->names('admin.employees');
    Route::resource('/positions', App\Http\Controllers\Admin\PositionController::class)->names('admin.positions');
    Route::resource('/buildings', App\Http\Controllers\Admin\BuildingController::class)->names('admin.buildings');
    
 
    Route::get('/patrols/export', [App\Http\Controllers\Admin\PatrolController::class, 'export'])->name('admin.patrols.export');
    Route::get('/patrols/export-pdf', [App\Http\Controllers\Admin\PatrolController::class, 'exportPdf'])->name('admin.patrols.exportPdf');

    Route::resource('/patrols', App\Http\Controllers\Admin\PatrolController::class)->names('admin.patrols');
    Route::get('/presences/{employeeId}/create', [App\Http\Controllers\Admin\PresenceController::class, 'createNew'])->name('admin.presences.createNew');
    Route::post('/presences/{employeeId}', [App\Http\Controllers\Admin\PresenceController::class, 'storeNew'])->name('admin.presences.storeNew');
    Route::get('/presences/all',[ App\Http\Controllers\Admin\PresenceController::class, 'allEmployeeAttendance'])->name('admin.presences.all');


    Route::get('/presences/all',[ App\Http\Controllers\Admin\PresenceController::class, 'allEmployeeAttendance'])->name('admin.presences.all');
    Route::resource('/presences', App\Http\Controllers\Admin\PresenceController::class)->names('admin.presences');
    Route::get('/presences/{employeeId}/export', [App\Http\Controllers\Admin\PresenceController::class, 'export'])->name('admin.presences.export');
    Route::get('/admin/presences/export', [App\Http\Controllers\Admin\PresenceController::class, 'exportMonthlyAttendance'])->name('admin.presences.export.monthly');

    Route::resource('/shifts', App\Http\Controllers\Admin\ShiftController::class)->names('admin.shifts');
    Route::resource('/leaves', App\Http\Controllers\Admin\LeaveController::class)->names('admin.leaves');
    Route::post('/leaves/{id}/approve', [App\Http\Controllers\Admin\LeaveController::class, 'approve'])->name('admin.leaves.approve');
    Route::post('/leaves/{id}/decline', [App\Http\Controllers\Admin\LeaveController::class, 'decline'])->name('admin.leaves.decline');
    Route::get('/presences/{employeeId}/{presenceId}/detail', [App\Http\Controllers\Admin\PresenceController::class, 'detail'])->name('admin.presences.detail');

    Route::get('/employees/search', [App\Http\Controllers\Admin\EmployeeController::class, 'search'])->name('admin.employees.search');
 
 
    Route::resource('/users', App\Http\Controllers\Admin\UserController::class)->names('admin.users');
    Route::get('/profile', [App\Http\Controllers\Admin\UserController::class, 'profile'])->name('admin.users.profile');
    Route::put('/profile', [App\Http\Controllers\Admin\UserController::class, 'profileUpdate'])->name('admin.users.profile.update');
});



Route::get('generate', function (){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'generated storage';
});
