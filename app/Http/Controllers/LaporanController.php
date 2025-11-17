<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanModel;
use App\Models\GedungModel;
use App\Models\LantaiModel;
use App\Models\RuangModel;
use App\Models\SaranaModel;
use App\Models\TeknisiModel;
use App\Models\RiwayatModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = LaporanModel::all();

        $breadcrumbs = [
            'title' => 'Daftar Laporan',
            'list' => ['home', 'laporan']
        ];
        $page = (object) [
            'title' => "Daftar Laporan"
        ];
        $activeMenu = 'laporan';
        return view('laporan.index', [
            'laporan' => $laporan,
            'breadcrumbs' => $breadcrumbs,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function riwayat()
    {
        $riwayat = RiwayatModel::with(['laporan', 'teknisi'])->get();

        $breadcrumbs = [
            'title' => 'Daftar Laporan',
            'list' => ['home', 'riwayat']
        ];
        $page = (object) [
            'title' => "Daftar Riwayat"
        ];
        $activeMenu = 'riwayat';
        return view('laporan.riwayat', [
            'riwayat' => $riwayat,
            'breadcrumbs' => $breadcrumbs,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function bobot()
    {
        $laporan = LaporanModel::all();
        $sarana = SaranaModel::all();

        // Calculate AHP weights
        $ahpWeights = $this->calculateAHPWeights();

        $breadcrumbs = [
            'title' => 'Pembobotan',
            'list' => ['home', 'pembobotan']
        ];
        $page = (object) [
            'title' => "Pembobotan"
        ];
        $activeMenu = 'bobot';
        return view('bobot.index', [
            'laporan' => $laporan,
            'sarana' => $sarana,
            'breadcrumbs' => $breadcrumbs,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'ahpWeights' => $ahpWeights // Pass AHP weights to the view
        ]);
    }

    public function list(Request $request)
    {
        $user = auth()->user();
        $statusFilter = $request->get('status');

        $query = LaporanModel::with(['user', 'teknisi', 'sarana.barang'])
            ->select('t_laporan_kerusakan.*');

        // Filter status jika ada
        if ($statusFilter) {
            $query->where('status_laporan', $statusFilter);
        }

        // Cek role user
        if ($user->level->level_name !== 'admin') {
            // Bukan admin, batasi hanya laporan milik user ini
            $query->where('user_id', $user->user_id);
        }
        // Jika admin, query tanpa filter user_id (lihat semua)

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('role', function ($row) {
                return strtoupper($row->role);
            })
            ->addColumn('sarana', function ($row) {
                return $row->sarana->barang->barang_nama ?? '-';
            })
            ->addColumn('teknisi', function ($row) {
                return $row->teknisi ? $row->teknisi->user->name : '-';
            })
            ->addColumn('status_laporan', function ($row) {
                return ucfirst($row->status_laporan);
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('/laporan/show_ajax/' . $row->laporan_id) . '\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function kelola()
    {
        $laporan = LaporanModel::all();

        $breadcrumbs = [
            'title' => 'Daftar Laporan',
            'list' => ['home', 'kelola-laporan-kerusakan']
        ];
        $page = (object) [
            'title' => "Daftar Laporan"
        ];
        $activeMenu = 'kelola';
        return view('laporan.kelola', [
            'laporan' => $laporan,
            'breadcrumbs' => $breadcrumbs,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list_kelola(Request $request)
    {
        $user = auth()->user();

        // Use window function to get only the latest report per sarana
        $query = LaporanModel::with(['gedung', 'lantai', 'ruang', 'sarana', 'user', 'teknisi'])
            ->fromSub(function ($query) {
                $query->from('t_laporan_kerusakan')
                    ->whereNotIn('status_laporan', ['ditolak'])
                    ->selectRaw('*, ROW_NUMBER() OVER (PARTITION BY sarana_id ORDER BY laporan_id DESC) as rn');
            }, 'ranked_laporan')
            ->where('rn', 1);

        if ($request->status) {
            $query->where('status_laporan', $request->status);
        }

        // Handle search for laporan_id
        if ($request->has('search') && $request->search['value'] != '') {
            $searchValue = $request->search['value'];
            $query->where('laporan_id', 'LIKE', '%' . $searchValue . '%');
        }

        if ($user->level->level_kode === 'teknisi') {
            $teknisiId = TeknisiModel::where('user_id', $user->user_id)->value('teknisi_id');
            $query->where('teknisi_id', $teknisiId);
        } elseif ($user->level->level_kode !== 'sarpras') {
            $query->whereRaw('1 = 0');
        }

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('laporan_id', function ($row) {
                return $row->laporan_id;
            })
            ->addColumn('laporan_judul', function ($row) {
                return $row->laporan_judul;
            })
            ->addColumn('sarana', function ($row) {
                return $row->sarana ? $row->sarana->barang->barang_nama ?? '-' : '-';
            })
            ->addColumn('status_laporan', function ($row) {
                return ucfirst($row->status_laporan);
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y H:i');
            })
            ->addColumn('bobot', function ($row) {
                return $row->bobot ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('/laporan/show_kelola_ajax/' . $row->laporan_id) . '\')" class="btn btn-info btn-sm">Detail</button> ';

                if ($row->status_laporan === 'diproses') {
                    $btn .= '<br><a href="' . url('/laporan/tugaskan_teknisi/' . $row->laporan_id) . '" class="btn btn-warning btn-sm mt-1" onclick="modalAction(this.href); return false;">Tugaskan Teknisi</a>';
                }

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->orderColumn('bobot', 'bobot $1')
            ->make(true);
    }

    public function tugaskan_teknisi($id, Request $request)
    {
        $laporan = LaporanModel::findOrFail($id);

        // Cek apakah user memiliki akses (misalnya hanya sarpras)
        if (Auth::user()->username !== 'sarpras') {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk tindakan ini.'
            ], 403);
        }

        if ($request->isMethod('get')) {
            // Ambil daftar teknisi
            $teknisi = TeknisiModel::with('user')->get();

            // Render view untuk pop-up
            $html = view('laporan.tugaskan_teknisi', compact('laporan', 'teknisi'))->render();

            return response()->json([
                'status' => 'success',
                'html' => $html
            ]);
        }

        if ($request->isMethod('post')) {
            // Cek apakah laporan sudah dikerjakan
            if ($laporan->status_laporan === 'dikerjakan') {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Laporan sudah dalam status Dikerjakan. Tidak dapat menugaskan ulang teknisi.'
                ], 400);
            }

            // Validasi input
            $request->validate([
                'teknisi_id' => 'required|exists:m_teknisi,teknisi_id',
                'catatan' => 'nullable|string'
            ]);

            // Update laporan
            $laporan->update([
                'teknisi_id' => $request->teknisi_id,
                'status_laporan' => 'Dikerjakan',
                'tanggal_diproses' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Teknisi berhasil ditugaskan.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Metode tidak diizinkan.'
        ], 405);
    }

    public function finish($id)
    {
        try {
            $laporan = LaporanModel::findOrFail($id);
            if ($laporan->status_laporan !== 'dikerjakan') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Laporan tidak dalam status Dikerjakan.'
                ], 400);
            }

            $laporan->status_laporan = 'selesai';
            $laporan->tanggal_selesai = now();
            $laporan->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil diselesaikan.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui status laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function kalkulasi($id)
    {
        try {
            $laporan = LaporanModel::findOrFail($id);

            // Calculate bobot using the new method
            $this->calculateBobotForLaporan($laporan);

            // Refresh the laporan to get the updated bobot
            $laporan->refresh();

            return response()->json([
                'status' => 'success',
                'message' => 'Perhitungan bobot berhasil',
                'bobot' => $laporan->bobot
            ]);
        } catch (Exception $e) {
            Log::error('Failed to calculate bobot for laporan ' . $id . ': ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghitung bobot: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateBobotForLaporan($laporan)
    {
        $sarana = SaranaModel::findOrFail($laporan->sarana_id);

        // Map values to scores
        $kerusakanMap = ['rendah' => 1, 'sedang' => 2, 'tinggi' => 3];
        $urgensiMap = ['rendah' => 1, 'sedang' => 2, 'tinggi' => 3];
        $frekuensiMap = ['tahunan' => 1, 'bulanan' => 2, 'mingguan' => 3, 'harian' => 4];
        $dampakMap = ['kecil' => 1, 'sedang' => 2, 'besar' => 3];

        $kerusakan = $kerusakanMap[strtolower($laporan->tingkat_kerusakan)] ?? 0;
        $urgensi = $urgensiMap[strtolower($laporan->tingkat_urgensi)] ?? 0;
        $frekuensi = $frekuensiMap[strtolower($sarana->frekuensi_penggunaan)] ?? 0;
        $dampak = $dampakMap[strtolower($laporan->dampak_kerusakan)] ?? 0;

        $jumlahLaporan = $sarana->jumlah_laporan ?? 1;

        // Normalize jumlah_laporan as a cost criterion
        $totalJumlahLaporan = SaranaModel::sum('jumlah_laporan');
        $normalizedJumlahLaporan = 1 - ($jumlahLaporan / ($totalJumlahLaporan ?: 1));

        $tanggalOperasional = Carbon::parse($sarana->tanggal_operasional);
        $now = Carbon::now();
        $usia = $now->diffInDays($tanggalOperasional);
        $maxUsia = 3650;
        $normalizedUsia = 1 - ($usia / $maxUsia);

        // Calculate AHP weights using pairwise comparison
        $ahpWeights = $this->calculateAHPWeights();

        // Calculate AHP score
        $ahpScore = ($ahpWeights['kerusakan'] * $kerusakan +
            $ahpWeights['urgensi'] * $urgensi +
            $ahpWeights['frekuensi'] * $frekuensi +
            $ahpWeights['dampak'] * $dampak +
            $ahpWeights['jumlah_laporan'] * $normalizedJumlahLaporan +
            $ahpWeights['usia'] * $normalizedUsia);

        // SAW Method (remain unchanged)
        $sawBobot = [
            'kerusakan' => 0.2,
            'urgensi' => 0.2,
            'frekuensi' => 0.2,
            'dampak' => 0.1,
            'jumlah_laporan' => 0.2,
            'usia' => 0.1
        ];

        $sawScore = $sawBobot['kerusakan'] * $kerusakan +
            $sawBobot['urgensi'] * $urgensi +
            $sawBobot['frekuensi'] * $frekuensi +
            $sawBobot['dampak'] * $dampak +
            $sawBobot['jumlah_laporan'] * $normalizedJumlahLaporan +
            $sawBobot['usia'] * $normalizedUsia;

        $bobot = (($ahpScore + $sawScore) / 2) * 100;

        // Save the calculated bobot to the laporan
        $laporan->bobot = $bobot;
        $laporan->save();

        Log::info('Bobot calculated for laporan ' . $laporan->laporan_id . ': ' . $bobot);
    }

    private function calculateAHPWeights()
    {
        // Define pairwise comparison matrix using Saaty scale (1-9)
        $pairwiseMatrix = [
            ['kerusakan', 'kerusakan', 1],
            ['kerusakan', 'urgensi', 1],
            ['kerusakan', 'frekuensi', 2],
            ['kerusakan', 'dampak', 3],
            ['kerusakan', 'jumlah_laporan', 2],
            ['kerusakan', 'usia', 4],

            ['urgensi', 'kerusakan', 1],
            ['urgensi', 'urgensi', 1],
            ['urgensi', 'frekuensi', 2],
            ['urgensi', 'dampak', 3],
            ['urgensi', 'jumlah_laporan', 2],
            ['urgensi', 'usia', 4],

            ['frekuensi', 'kerusakan', 0.5],
            ['frekuensi', 'urgensi', 0.5],
            ['frekuensi', 'frekuensi', 1],
            ['frekuensi', 'dampak', 2],
            ['frekuensi', 'jumlah_laporan', 1],
            ['frekuensi', 'usia', 3],

            ['dampak', 'kerusakan', 0.33],
            ['dampak', 'urgensi', 0.33],
            ['dampak', 'frekuensi', 0.5],
            ['dampak', 'dampak', 1],
            ['dampak', 'jumlah_laporan', 0.5],
            ['dampak', 'usia', 2],

            ['jumlah_laporan', 'kerusakan', 0.5],
            ['jumlah_laporan', 'urgensi', 0.5],
            ['jumlah_laporan', 'frekuensi', 1],
            ['jumlah_laporan', 'dampak', 2],
            ['jumlah_laporan', 'jumlah_laporan', 1],
            ['jumlah_laporan', 'usia', 3],

            ['usia', 'kerusakan', 0.25],
            ['usia', 'urgensi', 0.25],
            ['usia', 'frekuensi', 0.33],
            ['usia', 'dampak', 0.5],
            ['usia', 'jumlah_laporan', 0.33],
            ['usia', 'usia', 1],
        ];

        // Convert to square matrix
        $criteria = ['kerusakan', 'urgensi', 'frekuensi', 'dampak', 'jumlah_laporan', 'usia'];
        $matrix = array_fill(0, count($criteria), array_fill(0, count($criteria), 0));

        foreach ($pairwiseMatrix as $comparison) {
            $row = array_search($comparison[0], $criteria);
            $col = array_search($comparison[1], $criteria);
            $matrix[$row][$col] = $comparison[2];
            $matrix[$col][$row] = 1 / $comparison[2]; // Reciprocal
        }

        // Diagonal elements are 1
        for ($i = 0; $i < count($criteria); $i++) {
            $matrix[$i][$i] = 1;
        }

        // Calculate column sums
        $columnSums = array_fill(0, count($criteria), 0);
        for ($j = 0; $j < count($criteria); $j++) {
            for ($i = 0; $i < count($criteria); $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        // Normalize the matrix
        $normalizedMatrix = [];
        for ($i = 0; $i < count($criteria); $i++) {
            $normalizedMatrix[$i] = [];
            for ($j = 0; $j < count($criteria); $j++) {
                $normalizedMatrix[$i][$j] = $matrix[$i][$j] / $columnSums[$j];
            }
        }

        // Calculate priority vector (average of rows)
        $priorityVector = [];
        for ($i = 0; $i < count($criteria); $i++) {
            $rowSum = array_sum($normalizedMatrix[$i]);
            $priorityVector[$criteria[$i]] = $rowSum / count($criteria);
        }

        // Calculate consistency
        $weightedSumVector = [];
        for ($i = 0; $i < count($criteria); $i++) {
            $sum = 0;
            for ($j = 0; $j < count($criteria); $j++) {
                $sum += $matrix[$i][$j] * $priorityVector[$criteria[$j]];
            }
            $weightedSumVector[$i] = $sum;
        }

        $lambdaMax = 0;
        for ($i = 0; $i < count($criteria); $i++) {
            $lambdaMax += $weightedSumVector[$i] / $priorityVector[$criteria[$i]];
        }
        $lambdaMax /= count($criteria);

        $consistencyIndex = ($lambdaMax - count($criteria)) / (count($criteria) - 1);
        $randomIndex = [0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41]; // RI for n=1 to 8
        $consistencyRatio = $consistencyIndex / $randomIndex[count($criteria) - 1];

        // Check consistency (CR should be < 0.1)
        if ($consistencyRatio > 0.1) {
            throw new Exception('Matriks perbandingan tidak konsisten. CR = ' . number_format($consistencyRatio, 3));
        }

        return $priorityVector;
    }

    private function getNormalizedMatrix()
    {
        // This method can be used to return the normalized matrix for display
        $pairwiseMatrix = [
            ['kerusakan', 'kerusakan', 1],
            ['kerusakan', 'urgensi', 1],
            ['kerusakan', 'frekuensi', 2],
            ['kerusakan', 'dampak', 3],
            ['kerusakan', 'jumlah_laporan', 2],
            ['kerusakan', 'usia', 4],

            ['urgensi', 'kerusakan', 1],
            ['urgensi', 'urgensi', 1],
            ['urgensi', 'frekuensi', 2],
            ['urgensi', 'dampak', 3],
            ['urgensi', 'jumlah_laporan', 2],
            ['urgensi', 'usia', 4],

            ['frekuensi', 'kerusakan', 0.5],
            ['frekuensi', 'urgensi', 0.5],
            ['frekuensi', 'frekuensi', 1],
            ['frekuensi', 'dampak', 2],
            ['frekuensi', 'jumlah_laporan', 1],
            ['frekuensi', 'usia', 3],

            ['dampak', 'kerusakan', 0.33],
            ['dampak', 'urgensi', 0.33],
            ['dampak', 'frekuensi', 0.5],
            ['dampak', 'dampak', 1],
            ['dampak', 'jumlah_laporan', 0.5],
            ['dampak', 'usia', 2],

            ['jumlah_laporan', 'kerusakan', 0.5],
            ['jumlah_laporan', 'urgensi', 0.5],
            ['jumlah_laporan', 'frekuensi', 1],
            ['jumlah_laporan', 'dampak', 2],
            ['jumlah_laporan', 'jumlah_laporan', 1],
            ['jumlah_laporan', 'usia', 3],

            ['usia', 'kerusakan', 0.25],
            ['usia', 'urgensi', 0.25],
            ['usia', 'frekuensi', 0.33],
            ['usia', 'dampak', 0.5],
            ['usia', 'jumlah_laporan', 0.33],
            ['usia', 'usia', 1],
        ];

        $criteria = ['kerusakan', 'urgensi', 'frekuensi', 'dampak', 'jumlah_laporan', 'usia'];
        $matrix = array_fill(0, count($criteria), array_fill(0, count($criteria), 0));

        foreach ($pairwiseMatrix as $comparison) {
            $row = array_search($comparison[0], $criteria);
            $col = array_search($comparison[1], $criteria);
            $matrix[$row][$col] = $comparison[2];
            $matrix[$col][$row] = 1 / $comparison[2];
        }

        for ($i = 0; $i < count($criteria); $i++) {
            $matrix[$i][$i] = 1;
        }

        $columnSums = array_fill(0, count($criteria), 0);
        for ($j = 0; $j < count($criteria); $j++) {
            for ($i = 0; $i < count($criteria); $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        $normalizedMatrix = [];
        for ($i = 0; $i < count($criteria); $i++) {
            $normalizedMatrix[$criteria[$i]] = [];
            for ($j = 0; $j < count($criteria); $j++) {
                $normalizedMatrix[$criteria[$i]][$criteria[$j]] = $matrix[$i][$j] / $columnSums[$j];
            }
        }

        return $normalizedMatrix;
    }

    public function accept($id)
    {
        try {
            $laporan = LaporanModel::findOrFail($id);
            if ($laporan->status_laporan === 'diproses') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Laporan sudah dalam status Proses.'
                ], 400);
            } elseif ($laporan->status_laporan === 'dikerjakan') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Laporan sudah dalam status Dikerjakan.'
                ], 400);
            } elseif ($laporan->status_laporan === 'selesai') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Laporan sudah dalam status Selesai.'
                ], 400);
            }
            $laporan->status_laporan = 'Diproses';
            $laporan->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil diterima, Laporan akan segera diproses.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui status laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject($id, Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda harus login terlebih dahulu.'
            ], 401);
        }

        $laporan = LaporanModel::findOrFail($id);

        // Cek apakah pengguna memiliki akses (misalnya hanya sarpras)
        if (Auth::user()->username !== 'sarpras') {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk tindakan ini.'
            ], 403);
        }

        if ($laporan->status_laporan === 'diproses') {
            return response()->json([
                'status' => 'error',
                'message' => 'Laporan Tidak bisa ditolak karena sedang diproses!'
            ], 400);
        } elseif ($laporan->status_laporan === 'dikerjakan') {
            return response()->json([
                'status' => 'error',
                'message' => 'Laporan Tidak bisa ditolak karena sedang dikerjakan!'
            ], 400);
        } elseif ($laporan->status_laporan === 'selesai') {
            return response()->json([
                'status' => 'error',
                'message' => 'Laporan Tidak bisa ditolak karena sudah selesai!'
            ], 400);
        }

        // Proses penolakan laporan
        $laporan->update([
            'status_laporan' => 'ditolak',
            'tanggal_selesai' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan berhasil ditolak.'
        ]);
    }

    public function show_ajax($id)
    {
        $laporan = LaporanModel::with([
            'gedung',
            'lantai',
            'ruang',
            'sarana',
            'sarana.barang',
            'user',
            'teknisi.user'
        ])->findOrFail($id);

        return view('laporan.show_ajax', [
            'laporan' => $laporan
        ]);
    }

    public function getLaporanFotoAttribute($value)
    {
        if (!$value) return null;

        // Hapus duplikasi path
        $filename = str_replace('laporan_files/', '', $value);

        // Path absolut di sistem
        $fullPath = public_path('laporan_files/' . $filename);
        if (file_exists($fullPath)) {
            return asset('laporan_files/' . $filename);
        }

        return null;
    }

    public function show_kelola($id)
    {
        $user = Auth::user();
        $laporan = LaporanModel::with([
            'gedung',
            'lantai',
            'ruang',
            'sarana',
            'sarana.barang',
            'user',
            'teknisi.user'
        ])->findOrFail($id);
        $sarana = SaranaModel::findOrFail($id);

        $html = view('laporan.show_kelola_detail', [
            'laporan' => $laporan,
            'sarana' => $sarana,
            'user' => $user,
        ])->render();

        return response()->json([
            'status' => 'success',
            'html' => $html
        ]);
    }

    public function create_ajax()
    {
        $gedung = GedungModel::first(); // Fetch the single building
        $lantai = $gedung ? LantaiModel::where('gedung_id', $gedung->gedung_id)->get() : [];
        return view('laporan.create_ajax', [
            'gedung' => $gedung,
            'lantai' => $lantai,
            'ruang' => [],
            'sarana' => [],
        ]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'gedung_id' => 'required|exists:m_gedung,gedung_id',
                'lantai_id' => 'required|exists:m_lantai,lantai_id',
                'ruang_id' => 'required|exists:m_ruang,ruang_id',
                'sarana_id' => 'required|exists:m_sarana,sarana_id',
                'laporan_judul' => 'required|string|max:100',
                'laporan_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'tingkat_kerusakan' => 'required|in:rendah,sedang,tinggi,kritis',
                'tingkat_urgensi' => 'required|in:rendah,sedang,tinggi,kritis',
                'dampak_kerusakan' => 'required|in:kecil,sedang,besar',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $data['user_id'] = Auth::user()->user_id;

            if ($request->hasFile('laporan_foto')) {
                $file = $request->file('laporan_foto');
                $path = 'laporan_files';
                $filename = 'LAP-' . Str::upper(Str::random(10)) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $filename);
                $data['laporan_foto'] = $path . '/' . $filename;
            }

            $laporan = LaporanModel::create($data);

            // Increment jumlah_laporan for the sarana
            if ($laporan->sarana_id) {
                SaranaModel::where('sarana_id', $laporan->sarana_id)
                    ->increment('jumlah_laporan');
            }

            // Auto-calculate bobot after creating the report
            try {
                $this->calculateBobotForLaporan($laporan);
            } catch (Exception $e) {
                Log::error('Failed to calculate bobot for laporan ' . $laporan->laporan_id . ': ' . $e->getMessage());
                // Don't fail the entire operation if bobot calculation fails
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil dibuat dan bobot telah dihitung',
                'data' => $laporan->fresh() // Refresh to get the calculated bobot
            ], 200);
        }

        return view('/laporan');
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $laporan = LaporanModel::where('laporan_id', $id)
                    ->where('user_id', Auth::user()->user_id)
                    ->firstOrFail();

                $validator = Validator::make($request->all(), [
                    'gedung_id' => 'required|exists:m_gedung,gedung_id',
                    'lantai_id' => 'required|exists:m_lantai,lantai_id',
                    'ruang_id' => 'required|exists:m_ruang,ruang_id',
                    'sarana_id' => 'required|exists:m_sarana,sarana_id',
                    'laporan_judul' => 'required|string|max:100',
                    'laporan_foto' => 'nullable|image|max:2048',
                    'tingkat_kerusakan' => 'required|in:rendah,sedang,tinggi',
                    'tingkat_urgensi' => 'required|in:rendah,sedang,tinggi',
                    'dampak_kerusakan' => 'required|in:kecil,sedang,besar',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $data = $validator->validated();

                if ($request->hasFile('laporan_foto')) {
                    if ($laporan->laporan_foto && Storage::exists('public/laporan_files/' . $laporan->laporan_foto)) {
                        Storage::delete('public/' . $laporan->laporan_foto);
                    }
                    $file = $request->file('laporan_foto');
                    $path = 'laporan_files';
                    $filename = 'LAP-' . Str::upper(Str::random(10)) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path($path), $filename);
                    $data['laporan_foto'] = $path . '/' . $filename;
                }

                $laporan->update($data);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Laporan berhasil diperbarui',
                    'data' => $laporan
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Laporan tidak ditemukan atau Anda tidak memiliki akses.'
                ], 404);
            }
        }

        redirect('/laporan/kelola');
    }

    public function finishForm($id)
    {
        $laporan = LaporanModel::findOrFail($id);
        return view('laporan.finish_laporan_form', compact('laporan'));
    }

    public function selesai($id, Request $request)
    {
        $laporan = LaporanModel::findOrFail($id);

        if ($laporan->status_laporan !== 'dikerjakan') {
            return response()->json([
                'status' => 'error',
                'message' => 'Laporan tidak dalam status Dikerjakan.'
            ], 400);
        }

        $request->validate([
            'tindakan' => 'required|string',
            'bahan' => 'nullable|string',
            'biaya' => 'nullable|numeric',
        ]);

        $riwayatPerbaikan = new RiwayatModel();
        $riwayatPerbaikan->laporan_id = $laporan->laporan_id;
        $riwayatPerbaikan->teknisi_id = $laporan->teknisi_id;
        $riwayatPerbaikan->tindakan = $request->tindakan;
        $riwayatPerbaikan->bahan = $request->bahan;
        $riwayatPerbaikan->biaya = $request->biaya;
        $riwayatPerbaikan->status = 'selesai';
        $riwayatPerbaikan->waktu_mulai = now();
        $riwayatPerbaikan->waktu_selesai = now();
        $riwayatPerbaikan->save();

        $laporan->status_laporan = 'selesai';
        $laporan->tanggal_selesai = now();
        $laporan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan berhasil diselesaikan.'
        ]);
    }

    public function getGedung()
    {
        $gedung = GedungModel::all();
        return response()->json($gedung);
    }

    public function getLantai($gedung_id)
    {
        $lantai = LantaiModel::where('gedung_id', $gedung_id)->get();
        return response()->json($lantai);
    }

    public function getRuangByLantai($lantai_id)
    {
        $ruang = RuangModel::where('lantai_id', $lantai_id)->get();
        return response()->json($ruang);
    }

    public function getSaranaByRuang($ruang_id)
    {
        $sarana = SaranaModel::where('ruang_id', $ruang_id)->with('barang')->get();

        $saranaFormatted = $sarana->map(function ($item) {
            return [
                'sarana_id' => $item->sarana_id,
                'sarana_kode' => $item->barang->barang_kode ?? 'KODE-' . $item->sarana_id,
                'sarana_nama' => $item->barang->barang_nama ?? 'Sarana #' . $item->sarana_id,
                'nomor_urut' => $item->nomor_urut ?? ''
            ];
        });

        return response()->json($saranaFormatted);
    }

    public function getRuangDanSarana($lantai_id)
    {
        $ruang = RuangModel::where('lantai_id', $lantai_id)->get();
        $ruangIDs = $ruang->pluck('ruang_id');

        $sarana = SaranaModel::whereIn('ruang_id', $ruangIDs)->with('barang')->get();

        $saranaFormatted = $sarana->map(function ($item) {
            return [
                'sarana_id' => $item->sarana_id,
                'sarana_kode' => $item->barang->barang_kode ?? 'KODE-' . $item->sarana_id,
                'sarana_nama' => $item->barang->barang_nama ?? 'Sarana #' . $item->sarana_id,
                
            ];
        });
        return response()->json([
            'ruang' => $ruang,
            'sarana' => $saranaFormatted
        ]);
    }
}