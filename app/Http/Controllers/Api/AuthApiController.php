<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function register(Request $request) {
        $rules = [
            'username' => 'required|string|min:3|unique:m_users,username',
            'nama' => 'required|string|max:100',
            'no_induk' => 'required|string|max:50|unique:m_users,no_induk',
            'level_id' => 'required|exists:m_level,level_id',
            'password' => 'required|min:5',
            'confirm_password' => 'required|same:password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = UserModel::create([
            'level_id' => $request->level_id,
            'no_induk' => $request->no_induk,
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil',
            'user' => $user
        ], 201);
    }

}
