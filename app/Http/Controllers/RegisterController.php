<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('register.register')->with('level', $level);
    }

    public function store(Request $request)
    {
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        UserModel::create([
            'level_id' => $request->level_id,
            'no_induk' => $request->no_induk,
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->back()->with('success', 'Registrasi berhasil. Silakan login.');
    }
}
