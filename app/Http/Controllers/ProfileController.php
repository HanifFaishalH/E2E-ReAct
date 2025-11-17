<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var User|null $user */
        $user = Auth::user();

        return view('profile.show', compact('user'))->with('activeMenu', 'profile');
    }

    public function update(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $user->nama = $request->name;

        if ($request->hasFile('foto')) {
            if ($user->foto && file_exists(public_path('uploads/foto/' . $user->foto))) {
                unlink(public_path('uploads/foto/' . $user->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/foto'), $filename);
            $user->foto = $filename;
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui');
    }
}
