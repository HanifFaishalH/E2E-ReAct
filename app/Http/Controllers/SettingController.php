<?php

namespace App\Http\Controllers;

use App\Models\SettingProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function edit()
    {
        /** @var SettingProfile $user */
        $user = Auth::user();

        return view('setting-profile.setting', [
            'user' => $user,
            'activeMenu' => 'profile'
        ]);
    }

    public function update(Request $request)
    {
        /** @var SettingProfile $user */
        $user = Auth::user();

        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|confirmed',
        ]);

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak cocok'])->withInput();
        }

        $user->password = Hash::make($request->password_baru);
        $user->save();

        return redirect()->route('profile.setting')->with('success', 'Password berhasil diperbarui!');
    }
}
