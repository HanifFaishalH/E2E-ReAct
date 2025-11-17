<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index()
    {
        $laporans = DB::table('t_laporan_kerusakan')
            ->where('status_laporan', 'selesai')
            ->whereNotIn('laporan_id', function ($query) {
                $query->select('laporan_id')
                    ->from('t_feedback')
                    ->where('user_id', auth()->id());
            })
            ->get();

        return view('feedback.create', compact('laporans'))->with('activeMenu', 'berikan-umpan-balik');
    }

    public function list()
    {
        $feedbacks = DB::table('t_feedback')->get();
        return view('feedback.index', compact('feedbacks'))->with('activeMenu', 'daftar-umpan-balik');
    }

    public function store(Request $request)
    {
        $request->validate([
            'laporan_id' => 'required|exists:t_laporan_kerusakan,laporan_id',
            'rating' => 'required|integer|between:1,5',
            'komentar' => 'required|string|max:500',
        ]);

        DB::table('t_feedback')->insert([
            'feedback_id' => DB::table('t_feedback')->max('feedback_id') + 1, // Auto-increment sederhana
            'user_id' => auth()->id(), // Ambil ID pengguna yang login
            'laporan_id' => $request->laporan_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('feedback.index')->with('success', 'Umpan balik berhasil disimpan!')->with('activeMenu', 'berikan-umpan-balik');
    }
}
