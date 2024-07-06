<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route untuk register
Route::post('register', [App\Http\Controllers\Api\RegisterController::class, 'index']);
// Route to fetch positions
Route::get('fetch-positions', [App\Http\Controllers\Api\RegisterController::class, 'fetchPositions']);
// Route to fetch buildings
Route::get('fetch-buildings', [App\Http\Controllers\Api\RegisterController::class, 'fetchBuildings']);
// Route to fetch shifts

// Route untuk login
Route::post('login', [App\Http\Controllers\Api\LoginController::class, 'index']);
// Route untuk logout
Route::post('logout', [App\Http\Controllers\Api\LoginController::class, 'logout']);

// Route untuk validasi token
Route::middleware('auth:api')->post('validate-token', [App\Http\Controllers\Api\LoginController::class, 'validateToken']);
Route::middleware('auth:api')->get('dashboard', [App\Http\Controllers\Api\DashboardController::class, 'index']);
Route::middleware('auth:api')->get('precense', [App\Http\Controllers\Api\PresenceController::class, 'index']);
Route::middleware('auth:api')->post('precense', [App\Http\Controllers\Api\PresenceController::class, 'store']);
// Route untuk index permohonan cuti
Route::middleware('auth:api')->get('leaves', [App\Http\Controllers\Api\LeaveController::class, 'index']);
// Route untuk menyimpan permohonan cuti baru
Route::middleware('auth:api')->post('leaves', [App\Http\Controllers\Api\LeaveController::class, 'store']);
// Route untuk mengupdate permohonan cuti
Route::middleware('auth:api')->put('leaves', [App\Http\Controllers\Api\LeaveController::class, 'update']);
Route::middleware('auth:api')->get('history', [App\Http\Controllers\Api\HistoryController::class, 'index']);

// Rute untuk method index()
Route::middleware('auth:api')->get('/patrols', [App\Http\Controllers\Api\PatrolController::class, 'index']);

// Rute untuk method show()
Route::middleware('auth:api')->get('/patrols/{id}', [App\Http\Controllers\Api\PatrolController::class, 'show']);

// Rute untuk method store()
Route::middleware('auth:api')->post('/patrols', [App\Http\Controllers\Api\PatrolController::class, 'store']);

// Rute untuk method report()
Route::middleware('auth:api')->get('/patrols/report/{patrolId}', [App\Http\Controllers\Api\PatrolController::class, 'report']);

// Rute untuk method report_store()
Route::middleware('auth:api')->post('/patrols/report/{patrolId}', [App\Http\Controllers\Api\PatrolController::class, 'report_store']);
// Rute untuk method photo_store()
Route::middleware('auth:api')->post('/patrols/{patrolId}/photos', [App\Http\Controllers\Api\PatrolController::class, 'photo_store']);

// Rute untuk method report_update()
Route::middleware('auth:api')->put('/patrols/report/{patrolId}', [App\Http\Controllers\Api\PatrolController::class, 'report_update']);
// Rute untuk method photo_update()
Route::middleware('auth:api')->put('/patrols/{patrolId}/photos', [App\Http\Controllers\Api\PatrolController::class, 'photo_update']);
// Rute untuk method checkQR()
Route::middleware('auth:api')->post('/patrols/check-qr', [App\Http\Controllers\Api\PatrolController::class, 'checkQR']);

// Rute untuk menampilkan profil
Route::middleware('auth:api')->get('profile', [App\Http\Controllers\Api\ProfileController::class, 'index']);

// Rute untuk memperbarui profil
Route::middleware('auth:api')->put('profile/update', [App\Http\Controllers\Api\ProfileController::class, 'update']);

// Rute untuk memperbarui avatar
Route::middleware('auth:api')->post('profile/update-avatar', [App\Http\Controllers\Api\ProfileController::class, 'update_avatar']);

Route::get('/send-sos', [App\Http\Controllers\Api\AlertController::class, 'sendSOS']);