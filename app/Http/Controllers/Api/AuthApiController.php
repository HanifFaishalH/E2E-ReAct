<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user(); // ambil user yang sudah login

            // pastikan sanctum aktif dulu
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login Berhasil',
                'user' => $user,
                'token' => $token
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Login Gagal'
        ], 401);
    }

    public function logout(Request $request)
    {
        // Hapus token yang dipakai saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

}
