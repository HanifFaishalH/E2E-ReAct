<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class FeedbackApiController extends Controller
{
    /**
     * 🔹 Ambil semua umpan balik milik user login
     */
    public function index()
    {
        try {
            $feedbacks = DB::table('t_feedback')
                ->join('t_laporan_kerusakan', 't_feedback.laporan_id', '=', 't_laporan_kerusakan.laporan_id')
                ->select('t_feedback.*', 't_laporan_kerusakan.laporan_judul')
                ->where('t_feedback.user_id', Auth::id())
                ->orderBy('t_feedback.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar umpan balik berhasil diambil',
                'data' => $feedbacks
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data umpan balik: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔹 Ambil daftar laporan yang bisa diberi umpan balik (status selesai & belum diberi feedback)
     */
    public function availableReports()
    {
        try {
            $available = DB::table('t_laporan_kerusakan')
                ->where('status_laporan', 'selesai')
                ->whereNotIn('laporan_id', function ($query) {
                    $query->select('laporan_id')
                        ->from('t_feedback')
                        ->where('user_id', Auth::id());
                })
                ->select('laporan_id', 'laporan_judul', 'status_laporan')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar laporan yang dapat diberi umpan balik berhasil diambil',
                'data' => $available
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔹 Simpan umpan balik baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'laporan_id' => 'required|exists:t_laporan_kerusakan,laporan_id',
            'rating' => 'required|integer|between:1,5',
            'komentar' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $nextId = DB::table('t_feedback')->max('feedback_id') + 1;

            DB::table('t_feedback')->insert([
                'feedback_id' => $nextId,
                'user_id' => Auth::id(),
                'laporan_id' => $request->laporan_id,
                'rating' => $request->rating,
                'komentar' => $request->komentar,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Umpan balik berhasil disimpan',
                'data' => [
                    'feedback_id' => $nextId,
                    'laporan_id' => $request->laporan_id,
                    'rating' => $request->rating,
                    'komentar' => $request->komentar,
                    'user_id' => Auth::id(),
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan umpan balik: ' . $e->getMessage()
            ], 500);
        }
    }
}
