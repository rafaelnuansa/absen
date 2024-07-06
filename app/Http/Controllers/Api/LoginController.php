<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $validator->errors()->all()),
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        // Ambil kredensial dari input
        $credentials = $request->only('email', 'password');
    
        // Periksa apakah email terkait dengan karyawan yang aktif
        $employee = Employee::where('email', $request->email)->where('is_active', true)->first();
    
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Akun anda tidak aktif atau belum teregistrasi, hubungi administrator'
            ], 400);
        }
            // Authentikasi user
            if (!$token = Auth::guard('api')->attempt($credentials)) {
                // Jika gagal, kirim respons error
                return response()->json([
                    'success' => false,
                    'message' => 'Email or Password is incorrect'
                ], 400);
            }

    
        // Jika sukses, kirim respons berhasil
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user()->only(['name', 'email']),
            'message' => 'Berhasil login',
            'token'   => $token
        ], 200);
    }

    public function logout()
    {
        // Logout user
        auth()->logout();
        // Kirim respons logout berhasil
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
    
    public function validateToken()
    {
        try {
            // Ambil user yang diotentikasi berdasarkan token
            $user = Auth::guard('api')->user();

            if ($user) {
                // Jika user ditemukan, token valid
                return response()->json([
                    'success' => true,
                    'message' => 'Token valid'
                ], 200);
            } else {
                // Jika user tidak ditemukan, token tidak valid
                return response()->json([
                    'success' => false,
                    'message' => 'Token invalid'
                ], 401);
            }
        } catch (\Exception $e) {
            // Tangani kesalahan server
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate token'
            ], 500);
        }
    }
}
