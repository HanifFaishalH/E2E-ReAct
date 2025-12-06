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
            $userId = Auth::id();

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak valid atau user belum login.'
                ], 401);
            }

            $feedbacks = DB::table('t_feedback')
                ->join('t_laporan_kerusakan', 't_feedback.laporan_id', '=', 't_laporan_kerusakan.laporan_id')
                ->select(
                    't_feedback.feedback_id',
                    't_feedback.laporan_id',
                    't_laporan_kerusakan.laporan_judul',
                    't_feedback.rating',
                    't_feedback.komentar',
                    't_feedback.created_at'
                )
                ->where('t_feedback.user_id', $userId)
                ->orderBy('t_feedback.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar umpan balik milik user berhasil diambil.',
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
     * 🔹 Daftar laporan yang bisa diberi umpan balik (status selesai & belum diberi feedback)
     */
    public function availableReports(Request $request)
    {
        try {
            $userId = Auth::id() ?? $request->user()?->id ?? $request->query('user_id');

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak valid atau user belum login.'
                ], 401);
            }

            $laporans = DB::table('t_laporan_kerusakan')
                ->where('status_laporan', 'selesai')
                ->whereNotIn('laporan_id', function ($query) use ($userId) {
                    $query->select('laporan_id')
                        ->from('t_feedback')
                        ->where('user_id', $userId);
                })
                ->select('laporan_id', 'laporan_judul', 'status_laporan')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar laporan yang bisa diberi feedback berhasil diambil.',
                'data' => $laporans
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar laporan: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * 🔹 Simpan feedback baru (hanya jika user login)
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
            $userId = Auth::id();
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User belum login.'
                ], 401);
            }

            // Cek apakah user ini sudah pernah memberi feedback untuk laporan tersebut
            $existing = DB::table('t_feedback')
                ->where('laporan_id', $request->laporan_id)
                ->where('user_id', $userId)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memberikan feedback untuk laporan ini.'
                ], 409);
            }

            $nextId = DB::table('t_feedback')->max('feedback_id') + 1;

            DB::table('t_feedback')->insert([
                'feedback_id' => $nextId,
                'user_id' => $userId,
                'laporan_id' => $request->laporan_id,
                'rating' => $request->rating,
                'komentar' => $request->komentar,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Umpan balik berhasil disimpan.',
                'data' => [
                    'feedback_id' => $nextId,
                    'laporan_id' => $request->laporan_id,
                    'rating' => $request->rating,
                    'komentar' => $request->komentar,
                    'user_id' => $userId,
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan feedback: ' . $e->getMessage()
            ], 500);
        }
    }
}
