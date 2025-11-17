<?php

namespace App\Http\Controllers;

use App\Models\GedungModel;
use App\Models\RuangModel;
use Illuminate\Http\Request;
use App\Models\LantaiModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class RuangController extends Controller
{
    public function index()
    {
        $ruang = RuangModel::all();
        $lantai = LantaiModel::all();

        $breadcrumbs = [
            'title' => 'Daftar ruang',
            'list' => ['home', 'ruang']
        ];
        $page = (object) [
            'title' => "Daftar Ruang"
        ];
        $activeMenu = 'ruang';
        return view('ruang.index', [
            'ruang' => $ruang,
            'lantai' => $lantai,
            'breadcrumbs' => $breadcrumbs,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $ruang = RuangModel::with('lantai');

        if ($request->lantai_id) {
            $ruang->where('lantai_id', $request->lantai_id);
        }

        return datatables()->of($ruang)
            ->addIndexColumn()
            ->addColumn('lantai', function ($row) {
                return $row->lantai ? $row->lantai->lantai_nama : '-';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('/ruang/show/' . $row->ruang_id) . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/ruang/edit_ajax/' . $row->ruang_id) . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/ruang/delete_ajax/' . $row->ruang_id) . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
    {
        $ruang = RuangModel::find($id);

        if (!$ruang) {
            abort(404, 'Ruang tidak ditemukan');
        }

        return view('ruang.show', compact('ruang'));
    }

    public function create_ajax()
    {
        $gedung_list = GedungModel::all();
        $lantai = LantaiModel::all();

        return view('ruang.create_ajax', [
            'gedung_list' => $gedung_list,
            'lantai' => $lantai
        ]);
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gedung_id' => 'required|exists:m_gedung,gedung_id',
            'lantai_id' => 'required|exists:m_lantai,lantai_id',
            'ruang_nama' => 'required|string|max:255',
            'ruang_kode' => 'required|string|max:50|unique:m_ruang,ruang_kode',
            'ruang_tipe' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        RuangModel::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data ruang berhasil disimpan'
        ]);
    }

    public function getLantaiByGedung($gedung_id)
    {
        \Log::info('Mengambil lantai untuk gedung_id: ' . $gedung_id); // Debugging
        try {
            $lantai = LantaiModel::where('gedung_id', $gedung_id)->get();

            \Log::info('Jumlah lantai ditemukan: ' . $lantai->count());
            \Log::info('Data lantai: ' . json_encode($lantai->toArray()));

            if ($lantai->isEmpty()) {
                return response()->json([], 200);
            }

            return response()->json($lantai);
        } catch (\Exception $e) {
            \Log::error('Error mengambil lantai: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data lantai: ' . $e->getMessage()], 500);
        }
    }

    public function edit_ajax($id)
    {
        $ruang = RuangModel::findOrFail($id);
        $lantai = LantaiModel::all();

        return view('ruang.edit_ajax', [
            'ruang' => $ruang,
            'lantai' => $lantai
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'lantai_id' => 'required|exists:m_lantai,lantai_id',
                'ruang_nama' => 'required|string|max:255',
                'ruang_kode' => 'required|string|max:50|unique:m_ruang,ruang_kode,' . $id . ',ruang_id',
                'ruang_tipe' => 'required|string|max:50'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $ruang = RuangModel::findOrFail($id);
            $ruang->update($data);

            return response()->json(['success' => true, 'message' => 'Ruang updated successfully']);
        }

        return view('/ruang');
    }

    public function delete_ajax($id)
    {
        $ruang = RuangModel::findOrFail($id);
        $lantai = LantaiModel::all();
        return view('ruang.delete_confirm_ajax', [
            'ruang' => $ruang,
            'lantai' => $lantai
        ]);
    }

    public function destroy_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $ruang = RuangModel::findOrFail($id);
            $lantai = LantaiModel::findOrFail($ruang->lantai_id);
            $ruang->delete();
            $lantai->refresh(); // Refresh lantai data if needed

            return response()->json(['success' => true, 'message' => 'Ruang deleted successfully']);
        }

        return view('/ruang');
    }
}
