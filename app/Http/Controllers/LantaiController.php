<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LantaiModel;
use App\Models\GedungModel;
use Illuminate\Support\Facades\Validator;

class LantaiController extends Controller
{
    public function index()
    {
        $lantai = LantaiModel::all();
        $gedung = GedungModel::all();

        $breadcrumbs = [
            'title' => 'Daftar lantai',
            'list' => ['home', 'lantai']
        ];
        $page = (object) [
            'title' => "Daftar Lantai"
        ];
        $activeMenu = 'lantai';

        return view('lantai.index', [
            'lantai' => $lantai,
            'gedung' => $gedung,
            'breadcrumbs' => $breadcrumbs,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $lantai = LantaiModel::with('gedung');

        if ($request->gedung_id) {
            $lantai->where('gedung_id', $request->gedung_id);
        }

        return datatables()->of($lantai)
            ->addIndexColumn()
            ->addColumn('gedung', fn($row) => $row->gedung ? $row->gedung->gedung_nama : '-')
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('/lantai/show_ajax/' . $row->lantai_id) . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/lantai/edit_ajax/' . $row->lantai_id) . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/lantai/delete_ajax/' . $row->lantai_id) . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $gedung = GedungModel::all();
        return view('lantai.create_ajax', [
            'gedung' => $gedung
        ]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'gedung_id' => 'required|exists:m_gedung,gedung_id',
                'lantai_nama' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            LantaiModel::create($data);

            return response()->json(['success' => true, 'message' => 'Lantai created successfully']);
        }

        return view('/lantai');
    }

    public function edit_ajax($id)
    {
        $lantai = LantaiModel::findOrFail($id);
        $gedung = GedungModel::all();

        return view('lantai.edit_ajax', [
            'lantai' => $lantai,
            'gedung' => $gedung
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'gedung_id' => 'required|exists:m_gedung,gedung_id',
                'lantai_nama' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $lantai = LantaiModel::findOrFail($id);
            $lantai->update($data);

            return response()->json(['success' => true, 'message' => 'Lantai updated successfully']);
        }

        return view('/lantai');
    }

    public function delete_ajax($id)
    {
        $lantai = LantaiModel::findOrFail($id);
        $gedung = GedungModel::all();

        return view('lantai.delete_confirm_ajax', [
            'lantai' => $lantai,
            'gedung' => $gedung
        ]);
    }

    public function destroy_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $lantai = LantaiModel::findOrFail($id);
            $lantai->delete();

            return response()->json(['success' => true, 'message' => 'Lantai deleted successfully']);
        }

        return view('/lantai');
    }

    public function show_ajax($id)
    {
        $lantai = LantaiModel::with('gedung')->findOrFail($id);
        return view('lantai.show_ajax', [
            'lantai' => $lantai
        ]);
    }
}
