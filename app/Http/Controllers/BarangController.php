<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class BarangController extends Controller
{
    public function index()
    {
        $kategori = KategoriModel::all();

        $breadcrumbs = [
            'title' => 'Daftar Barang',
            'list' => ['home', 'Barang']
        ];

        $page = (object) [
            'title' => "Daftar Barang"
        ];

        $activeMenu = 'barang';

        return view('barang.index', [
            'kategori' => $kategori,
            'breadcrumbs' => $breadcrumbs,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $data = BarangModel::query()->with('kategori');

        if ($request->kategori_id) {
            $data->where('kategori_id', $request->kategori_id);
        }

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('kategori_nama', function ($row) {
                return $row->kategori ? $row->kategori->kategori_nama : '-';
            })
            ->addColumn('aksi', function ($row) {
                return '
                    <a href="javascript:void(0)" onclick="modalAction(\'' . url('barang/show/' . $row->barang_id) . '\')" class="btn btn-sm btn-info">Detail</a>
                    <a href="javascript:void(0)" onclick="modalAction(\'' . url('barang/edit_ajax/' . $row->barang_id) . '\')" class="btn btn-sm btn-warning">Edit</a>
                    <a href="javascript:void(0)" onclick="modalAction(\'' . url('barang/delete_ajax/' . $row->barang_id) . '\')" class="btn btn-sm btn-danger">Hapus</a>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show($id)
    {
        $barang = BarangModel::with('kategori')->findOrFail($id);
        return view('barang.show', compact('barang'));
    }

    public function create_ajax()
    {
        $kategori_list = KategoriModel::all();

        return view('barang.create_ajax', [
            'kategori_list' => $kategori_list
        ]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'barang_nama' => 'required|string|max:255',
                'kategori_id' => 'required|exists:m_kategori,kategori_id',
                'spesifikasi' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            BarangModel::create($data);

            return response()->json(['success' => true, 'message' => 'Barang created successfully']);
        }

        return redirect('/barang');
    }

    public function edit_ajax($id)
    {
        $barang = BarangModel::findOrFail($id);
        $kategori_list = KategoriModel::all();

        return view('barang.edit_ajax', [
            'barang' => $barang,
            'kategori_list' => $kategori_list
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'barang_nama' => 'required|string|max:255',
                'kategori_id' => 'required|exists:m_kategori,kategori_id',
                'spesifikasi' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $barang = BarangModel::findOrFail($id);
            $barang->update($data);

            return response()->json(['success' => true, 'message' => 'Barang updated successfully']);
        }

        return redirect('/barang');
    }

    public function delete_ajax($id)
    {
        $barang = BarangModel::findOrFail($id);
        return view('barang.delete_confirm_ajax', [
            'barang' => $barang
        ]);
    }

    public function destroy_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::findOrFail($id);
            $barang->delete();

            // Ulang Increment
            $maxId = BarangModel::max('barang_id');
            if ($maxId) {
                DB::statement('ALTER TABLE m_barang AUTO_INCREMENT = ' . ($maxId + 1));
            }

            return response()->json(['success' => true, 'message' => 'Barang deleted successfully']);
        }

        return redirect('/barang');
    }
}
