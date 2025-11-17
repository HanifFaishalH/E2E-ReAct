<?php

namespace App\Http\Controllers;

use App\Models\GedungModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GedungController extends Controller
{
    public function index()
    {
        $gedung = GedungModel::all();

        $breadcrumbs = [
            'title' => 'Daftar Gedung',
            'list' => ['home', 'gedung']
        ];

        $page = (object) [
            'title' => "Daftar Gedung"
        ];

        $activeMenu = 'gedung';

        return view('gedung.index', [
            'gedung' => $gedung,
            'breadcrumbs' => $breadcrumbs,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $data = GedungModel::query();

        if ($request->gedung_id) {
            $data->where('gedung_id', $request->gedung_id);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($gedung) {
                $btn  = '<button onclick="modalAction(\'' . url('/gedung/' . $gedung->gedung_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/gedung/' . $gedung->gedung_id . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/gedung/' . $gedung->gedung_id . '/delete') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Detail

    public function show(string $id)
    {
        $gedung = GedungModel::find($id);

        if (!$gedung) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gedung dengan ID ' . $id . ' tidak ditemukan'
            ], 404);
        }

        $html = view('gedung.show', compact('gedung'))->render();

        return response()->json([
            'status' => 'success',
            'html' => $html
        ]);
    }

    // Create

    public function create_ajax()
    {
        return response()->json([
            'status' => 'success',
            'html' => view('gedung.create_ajax')->render()
        ]);
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'gedung_nama' => 'required|string|max:255',
                'gedung_kode' => 'required|string|max:50|unique:m_gedung,gedung_kode',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            GedungModel::create([
                'gedung_nama' => $data['gedung_nama'],
                'gedung_kode' => $data['gedung_kode'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Gedung berhasil ditambahkan'
            ]);
        }

        return redirect('/gedung');
    }


    // Edit

    public function edit($id)
    {
        $gedung = GedungModel::findOrFail($id);
        $html = view('gedung.edit', compact('gedung'))->render();
        return response()->json([
            'status' => 'success',
            'html' => $html,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gedung_nama' => 'required|string|max:255',
            'gedung_kode' => 'required|string|max:50|unique:m_gedung,gedung_kode,' . $id . ',gedung_id',
        ]);

        $gedung = GedungModel::findOrFail($id);
        $gedung->update($request->only(['gedung_nama', 'gedung_kode']));

        return response()->json([
            'status' => 'success',
            'message' => 'Data gedung berhasil diperbarui!',
        ]);
    }

    public function delete_ajax($id)
    {
        $gedung = GedungModel::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'html' => view('gedung.delete_confirm_ajax', [
                'gedung' => $gedung
            ])->render()
        ]);
    }

    public function destroy_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $gedung = GedungModel::findOrFail($id);
                $gedung->delete();

                // Ulang Increment
                $maxId = GedungModel::max('gedung_id');
                if ($maxId) {
                    DB::statement('ALTER TABLE m_gedung AUTO_INCREMENT = ' . ($maxId + 1));
                }

                return response()->json(['success' => true, 'message' => 'Gedung deleted successfully']);
            } catch (\Exception $e) {
                \log::error('Error deleting gedung: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
            }
        }

        return redirect('/gedung');
    }
}
