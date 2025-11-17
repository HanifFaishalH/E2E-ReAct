<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanModel;
use App\Models\TeknisiModel;

class HomeController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            dd('Pengguna belum login');
        }

        $user = auth()->user();

        $total = 0;
        $pending = 0;
        $diproses = 0;
        $dikerjakan = 0;
        $selesai = 0;
        $ditolak = 0;

        if ($user->level_id == 6) { // SARPRAS
            $total = LaporanModel::count();
            $pending = LaporanModel::where('status_laporan', 'pending')->count();
            $diproses = LaporanModel::where('status_laporan', 'diproses')->count();
            $dikerjakan = LaporanModel::where('status_laporan', 'dikerjakan')->count();
            $selesai = LaporanModel::where('status_laporan', 'selesai')->count();
            $ditolak = LaporanModel::where('status_laporan', 'ditolak')->count();
        } elseif ($user->level_id == 5) { // TEKNISI
            $teknisi = TeknisiModel::where('user_id', $user->user_id)->first();
            if ($teknisi) {
                $total = LaporanModel::where('teknisi_id', $teknisi->teknisi_id)->count();
                $pending = LaporanModel::where('teknisi_id', $teknisi->teknisi_id)->where('status_laporan', 'pending')->count();
                $diproses = LaporanModel::where('teknisi_id', $teknisi->teknisi_id)->where('status_laporan', 'diproses')->count();
                $dikerjakan = LaporanModel::where('teknisi_id', $teknisi->teknisi_id)->where('status_laporan', 'dikerjakan')->count();
                $selesai = LaporanModel::where('teknisi_id', $teknisi->teknisi_id)->where('status_laporan', 'selesai')->count();
                $ditolak = LaporanModel::where('teknisi_id', $teknisi->teknisi_id)->where('status_laporan', 'ditolak')->count();
            }
        } else {
            $total = LaporanModel::where('user_id', $user->user_id)->count();
            $pending = LaporanModel::where('user_id', $user->user_id)->where('status_laporan', 'pending')->count();
            $diproses = LaporanModel::where('user_id', $user->user_id)->where('status_laporan', 'diproses')->count();
            $dikerjakan = LaporanModel::where('user_id', $user->user_id)->where('status_laporan', 'dikerjakan')->count();
            $selesai = LaporanModel::where('user_id', $user->user_id)->where('status_laporan', 'selesai')->count();
            $ditolak = LaporanModel::where('user_id', $user->user_id)->where('status_laporan', 'ditolak')->count();
        }

        $breadcrumbs = [
            'title' => 'Dashboard',
            'list' => ['home']
        ];

        $page = (object) [
            'title' => "Dashboard"
        ];

        $activeMenu = 'home';

        return view('layout.welcome', [
            'breadcrumbs' => $breadcrumbs,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'user' => $user,
            'total' => $total,
            'pending' => $pending,
            'diproses' => $diproses,
            'dikerjakan' => $dikerjakan,
            'selesai' => $selesai,
            'ditolak' => $ditolak,
        ]);
    }
}