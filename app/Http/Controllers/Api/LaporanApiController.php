<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class LaporanApiController extends Controller
{
    public function index()
    {
        try {
            $laporan = LaporanModel::with(['user', 'sarana.barang', 'teknisi.user'])->get();
            return response()->json([
                'success' => true,
                'message' => 'Daftar semua laporan berhasil diambil',
                'data' => $laporan
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $laporan = LaporanModel::with(['user', 'sarana.barang', 'teknisi.user'])->find($id);
        if (!$laporan) {
            return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $laporan]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gedung_id' => 'required|integer',
            'lantai_id' => 'required|integer',
            'ruang_id' => 'required|integer',
            'sarana_id' => 'required|integer',
            'laporan_judul' => 'required|string|max:100',
            'tingkat_kerusakan' => 'required|in:rendah,sedang,tinggi',
            'tingkat_urgensi' => 'required|in:rendah,sedang,tinggi',
            'dampak_kerusakan' => 'required|in:kecil,sedang,besar'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $laporan = new LaporanModel($validator->validated());
            $laporan->user_id = Auth::id() ?? 1; // fallback jika belum auth
            $laporan->status_laporan = 'pending';
            $laporan->save();

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dibuat',
                'data' => $laporan
            ], 201);
        } catch (Exception $e) {
            Log::error('API Laporan gagal: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal membuat laporan'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanModel::find($id);
        if (!$laporan) {
            return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan'], 404);
        }

        $laporan->update($request->only([
            'laporan_judul', 'tingkat_kerusakan', 'tingkat_urgensi', 'dampak_kerusakan', 'status_laporan'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diperbarui',
            'data' => $laporan
        ]);
    }

    public function destroy($id)
    {
        $laporan = LaporanModel::find($id);
        if (!$laporan) {
            return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan'], 404);
        }

        $laporan->delete();
        return response()->json(['success' => true, 'message' => 'Laporan berhasil dihapus']);
    }
}
