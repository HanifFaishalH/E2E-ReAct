<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\BarangModel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKerusakanController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::info('Attempting to load BarangModel');

            $barangList = BarangModel::select('barang_id as id', 'barang_nama as nama', 'kategori_id')
                ->orderBy('barang_nama')
                ->get();

            // Dapatkan filter tahun, bulan, barang dari request atau default
           $tahunDipilih = $request->get('tahun', null); 
            $bulanDipilih = $request->get('bulan', null);
            $barangDipilih = $request->get('barang', null);

            // Siapkan daftar tahun dari 2020 sampai sekarang + 1
            $listTahun = range(2020, date('Y') + 1);

            return view('laporan.periode', [
                'barangList' => $barangList,
                'listTahun' => $listTahun,
                'tahunDipilih' => $tahunDipilih,
                'bulanDipilih' => $bulanDipilih,
                'barangDipilih' => $barangDipilih,
                'activeMenu' => 'kelola-periode'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in LaporanKerusakanController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat halaman kelola periode: ' . $e->getMessage());
        }
    }


    public function getData(Request $request)
    {
        try {
            Log::info('getData called with params:', $request->all());

            $request->validate([
                'tahun' => 'nullable|integer|min:2020|max:' . (now()->year + 1),
                'bulan' => 'nullable|integer|min:1|max:12',
                'barang' => 'nullable|integer|exists:m_barang,barang_id'
            ]);

            $tahun = $request->tahun;
            $bulan = $request->bulan;
            $barang = $request->barang;

            Log::info('Filters applied:', compact('tahun', 'bulan', 'barang'));

            $tableExists = DB::select("SHOW TABLES LIKE 't_laporan_kerusakan'");
            if (empty($tableExists)) {
                throw new \Exception('Tabel t_laporan_kerusakan tidak ditemukan');
            }

            $query = DB::table('t_laporan_kerusakan')
                ->join('m_sarana', 't_laporan_kerusakan.sarana_id', '=', 'm_sarana.sarana_id')
                ->whereNotNull('t_laporan_kerusakan.tanggal_diproses');

            if ($tahun) {
                $query->whereYear('t_laporan_kerusakan.tanggal_diproses', $tahun);
            }

            if ($bulan) {
                $query->whereMonth('t_laporan_kerusakan.tanggal_diproses', $bulan);
            }

            if ($barang) {
                $query->where('m_sarana.barang_id', $barang);
            }

            // Ambil jumlah laporan per bulan (tahun & bulan) saja
            $data = $query
                ->selectRaw('YEAR(t_laporan_kerusakan.tanggal_diproses) as tahun, MONTH(t_laporan_kerusakan.tanggal_diproses) as bulan, COUNT(*) as jumlah')
                ->groupByRaw('YEAR(t_laporan_kerusakan.tanggal_diproses), MONTH(t_laporan_kerusakan.tanggal_diproses)')
                ->orderByRaw('tahun DESC, bulan DESC')
                ->get();

            $total = $data->sum('jumlah');

            $barangInfo = null;
            if ($barang) {
                $barangInfo = DB::table('m_barang')
                    ->leftJoin('m_kategori', 'm_barang.kategori_id', '=', 'm_kategori.kategori_id')
                    ->where('m_barang.barang_id', $barang)
                    ->select('m_barang.*', 'm_kategori.kategori_nama')
                    ->first();
            }

            // Generate statistik HTML dengan canvas untuk chart
            $statistik_html = $this->generateStatistikHtml($total, $data, $tahun, $bulan, $barangInfo);

            // Siapkan data chart untuk JS
            $tabel = $data->map(function ($item) {
                return [
                    'tahun' => $item->tahun,
                    'bulan' => Carbon::create()->month($item->bulan)->translatedFormat('F'),
                    'jumlah' => $item->jumlah,
                ];
            });

            $chartLabels = $tabel->pluck('bulan')->toArray();
            $chartData = $tabel->pluck('jumlah')->toArray();

            return response()->json([
                'success' => true,
                'statistik_html' => $statistik_html,
                'chart' => [
                    'labels' => $chartLabels,
                    'data' => $chartData
                ],
                'tabel' => $tabel,
                'summary' => [
                    'total_laporan' => $total,
                    'total_periode' => $tabel->count(),
                    'filter_applied' => [
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'barang' => $barang,
                        'barang_info' => $barangInfo ? [
                            'id' => $barangInfo->barang_id,
                            'nama' => $barangInfo->barang_nama,
                            'kategori' => $barangInfo->kategori_nama ?? 'Unknown'
                        ] : null
                    ]
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data filter tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in getData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    private function generateStatistikHtml($total, $data, $tahun, $bulan, $barangInfo)
    {
        $html = '<div class="row">';
        $html .= '<div class="col-12"><div class="card"><div class="card-body">';
        $html .= '<h5 class="card-title">Total Laporan per Bulan</h5>';
        $html .= '<canvas id="chartTotalLaporan" height="120"></canvas>';
        $html .= '</div></div></div>';
        $html .= '</div>'; // end row

        if ($tahun || $bulan || $barangInfo) {
            $html .= '<div class="alert alert-info mt-3"><i class="fa fa-filter"></i> <strong>Filter Aktif:</strong> ';
            $filters = [];
            if ($tahun) $filters[] = "Tahun: $tahun";
            if ($bulan) $filters[] = "Bulan: " . Carbon::create()->month($bulan)->translatedFormat('F');
            if ($barangInfo) {
                $filters[] = "Barang: {$barangInfo->barang_nama} (Kategori: {$barangInfo->kategori_nama})";
            }
            $html .= implode(' | ', $filters);
            $html .= '</div>';
        }

        return $html;
    }

    public function exportPdf(Request $request)
    {
        try {
            $request->validate([
                'tahun' => 'nullable|integer|min:2020|max:' . (now()->year + 1),
                'bulan' => 'nullable|integer|min:1|max:12',
                'barang' => 'nullable|integer|exists:m_barang,barang_id'
            ]);

            $tahun = $request->tahun;
            $bulan = $request->bulan;
            $barang = $request->barang;

            $query = DB::table('t_laporan_kerusakan')
                ->join('m_sarana', 't_laporan_kerusakan.sarana_id', '=', 'm_sarana.sarana_id')
                ->whereNotNull('t_laporan_kerusakan.tanggal_diproses');

            if ($tahun) {
                $query->whereYear('t_laporan_kerusakan.tanggal_diproses', $tahun);
            }
            if ($bulan) {
                $query->whereMonth('t_laporan_kerusakan.tanggal_diproses', $bulan);
            }
            if ($barang) {
                $query->where('m_sarana.barang_id', $barang);
            }

            $data = $query
                ->selectRaw('YEAR(t_laporan_kerusakan.tanggal_diproses) as tahun, MONTH(t_laporan_kerusakan.tanggal_diproses) as bulan, COUNT(*) as jumlah')
                ->groupByRaw('YEAR(t_laporan_kerusakan.tanggal_diproses), MONTH(t_laporan_kerusakan.tanggal_diproses)')
                ->orderByRaw('tahun DESC, bulan DESC')
                ->get();

            $barangInfo = null;
            if ($barang) {
                $barangInfo = DB::table('m_barang')
                    ->leftJoin('m_kategori', 'm_barang.kategori_id', '=', 'm_kategori.kategori_id')
                    ->where('m_barang.barang_id', $barang)
                    ->select('m_barang.*', 'm_kategori.kategori_nama')
                    ->first();
            }

            $tabel = $data->map(function ($item) {
                return [
                    'tahun' => $item->tahun,
                    'bulan' => Carbon::create()->month($item->bulan)->translatedFormat('F'),
                    'jumlah' => $item->jumlah,
                ];
            });

            $total = $data->sum('jumlah');

            $pdf = PDF::loadView('laporan.export_pdf', [
                'data' => $tabel,
                'total' => $total,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'barangInfo' => $barangInfo
            ])->setPaper('A4', 'portrait');

            return $pdf->stream('laporan_periode.pdf');
        } catch (\Exception $e) {
            Log::error('Export PDF gagal: ' . $e->getMessage());
            return back()->with('error', 'Export PDF gagal: ' . $e->getMessage());
        }
    }
}
