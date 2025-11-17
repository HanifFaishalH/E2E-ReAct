<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax() || request()->wantsJson()) {
            $user = UserModel::find(auth()->id());
            if (!$user->hasPermission('admin')) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }
        }

        $user = UserModel::all();
        $level = LevelModel::all();
        $breadcrumbs = [
            'title' => 'Daftar User',
            'list' => ['home', 'user']
        ];

        $page = (object) [
            'title' => "Daftar User"
        ];

        $activeMenu = 'user';

        return view('user.index', [
            'user' => $user,
            'level' => $level,
            'breadcrumbs' => $breadcrumbs,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $data = UserModel::select(
            'user_id',
            'no_induk',
            'username',
            'password',
            'nama',
            'level_id',
            'foto',
        )->with('level');

        if ($request->level_id) {
            $data = $data->where('level_id', $request->level_id);
        }

        return datatables()->of($data->get())
            ->addIndexColumn()
            ->addColumn('level_nama', function ($row) {
                return $row->level ? $row->level->level_nama : '-';
            })
            ->addColumn('no_induk', function ($row) {
                return $row->no_induk ? $row->no_induk : '-';
            })
            ->addColumn('username', function ($row) {
                return $row->username ? $row->username : '-';
            })
            ->addColumn('nama', function ($row) {
                return $row->nama ? $row->nama : '-';
            })
            ->addColumn('aksi', function ($row) {
                $btn  = '<button onclick="modalAction(\'' . url('/user/' . $row->user_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $row->user_id . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . 'delete_ajax/' . $row->user_id ) . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
    {
        $user = UserModel::find($id);

        if (!$user) {
            abort(404, 'User tidak ditemukan');
        }

        return view('user.show', compact('user'));
    }

    public function edit($id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        if (!$user) {
            abort(404, 'User tidak ditemukan');
        }

        return view('user.edit', compact('user', 'level'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:m_users,username,'.$id.',user_id',
            'no_induk' => 'required|string|max:50|unique:m_users,no_induk,'.$id.',user_id',
            'nama' => 'required|string|max:50',
            'level_id' => 'required|exists:m_level,level_id',
            'password' => 'nullable|min:5',
        ]);

        $user = UserModel::findOrFail($id);
        $data = $request->only(['username', 'no_induk', 'nama', 'level_id']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui'
        ]);
    }

    public function delete_ajax($id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        return view('user.delete_confirm_ajax', [
            'user' => $user
        ]);
    }

    public function destroy_ajax($id)
    {
        $user = UserModel::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $user->delete();

        // Reset auto increment jika diperlukan
        $maxId = UserModel::max('user_id');
        if ($maxId) {
            DB::statement('ALTER TABLE m_users AUTO_INCREMENT = ' . ($maxId + 1));
        }

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }

    public function create_ajax()
    {
        $level = LevelModel::all();
        return view('user.create_ajax', compact('level'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:m_level,level_id',
            'username' => 'required|unique:m_users,username',
            'password' => 'required|min:6',
            'nama' => 'required',
            'no_induk' => 'nullable|string|unique:m_users,no_induk',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['level_id', 'username', 'nama', 'no_induk']);
        $data['password'] = bcrypt($request->password);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_user', 'public');
            $data['foto'] = $fotoPath;
        }

        UserModel::create($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan'
        ]);
    }
}