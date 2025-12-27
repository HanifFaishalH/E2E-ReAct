<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            // ===================== DATA LAMA =====================
            [
                'user_id' => 3,
                'role' => 'mhs',
                'gedung_id' => 1,
                'lantai_id' => 5,
                'ruang_id' => 6,
                'sarana_id' => 178,
                'teknisi_id' => 1,
                'laporan_judul' => 'Kerusakan Proyektor',
                'laporan_foto' => 'laporan_files/proyektor_rusak.jpg',
                'tingkat_kerusakan' => 'tinggi',
                'tingkat_urgensi' => 'sedang',
                'dampak_kerusakan' => 'besar',
                'status_laporan' => 'diproses',
                'tanggal_diproses' => $now->copy()->subDays(2),
                'tanggal_selesai' => null,
                'bobot' => 0.752,
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(1),
            ],
            [
                'user_id' => 4,
                'role' => 'dosen',
                'gedung_id' => 1,
                'lantai_id' => 6,
                'ruang_id' => 22,
                'sarana_id' => 594,
                'teknisi_id' => 2,
                'laporan_judul' => 'Lemari rusak',
                'laporan_foto' => 'laporan_files/kursi_dosen.jpg',
                'tingkat_kerusakan' => 'sedang',
                'tingkat_urgensi' => 'rendah',
                'dampak_kerusakan' => 'sedang',
                'status_laporan' => 'selesai',
                'tanggal_diproses' => $now->copy()->subDays(5),
                'tanggal_selesai' => $now->copy()->subDays(1),
                'bobot' => 0.483,
                'created_at' => $now->copy()->subDays(6),
                'updated_at' => $now->copy()->subDays(1),
            ],
            [
                'user_id' => 5,
                'role' => 'tendik',
                'gedung_id' => 1,
                'lantai_id' => 8,
                'ruang_id' => 67,
                'sarana_id' => 1644,
                'teknisi_id' => null,
                'laporan_judul' => 'AC tidak dingin',
                'laporan_foto' => null,
                'tingkat_kerusakan' => 'rendah',
                'tingkat_urgensi' => 'rendah',
                'dampak_kerusakan' => 'kecil',
                'status_laporan' => 'pending',
                'tanggal_diproses' => null,
                'tanggal_selesai' => null,
                'bobot' => null,
                'created_at' => $now->copy()->subDay(),
                'updated_at' => $now->copy()->subDay(),
            ],
        ];

        // ===================== 15 DATA BARU =====================
        foreach ([2, 3, 4] as $userId) {
            for ($i = 1; $i <= 5; $i++) {
                $data[] = [
                    'user_id' => $userId,
                    'role' => $userId == 2 ? 'mhs' : ($userId == 3 ? 'dosen' : 'tendik'),
                    'gedung_id' => 1,
                    'lantai_id' => rand(5, 8),
                    'ruang_id' => rand(6, 70),
                    'sarana_id' => rand(100, 1700),
                    'teknisi_id' => rand(1, 2),

                    'laporan_judul' => "Laporan selesai user {$userId} #{$i}",
                    'laporan_foto' => null,

                    'tingkat_kerusakan' => 'sedang',
                    'tingkat_urgensi' => 'sedang',
                    'dampak_kerusakan' => 'sedang',

                    'status_laporan' => 'selesai',
                    'tanggal_diproses' => $now->copy()->subDays(rand(4, 7)),
                    'tanggal_selesai' => $now->copy()->subDays(rand(1, 3)),

                    'bobot' => round(rand(300, 800) / 1000, 3),
                    'created_at' => $now->copy()->subDays(rand(6, 10)),
                    'updated_at' => $now->copy()->subDays(rand(1, 3)),
                ];
            }
        }

        // ===================== 15 DATA NON-SELESAI =====================
        $nonSelesaiStatus = ['pending', 'diproses', 'dikerjakan', 'ditolak'];

        foreach ([2, 3, 4] as $userId) {
            for ($i = 1; $i <= 5; $i++) {
                $status = $nonSelesaiStatus[array_rand($nonSelesaiStatus)];

                $data[] = [
                    'user_id' => $userId,
                    'role' => $userId == 2 ? 'mhs' : ($userId == 3 ? 'dosen' : 'tendik'),
                    'gedung_id' => 1,
                    'lantai_id' => rand(5, 8),
                    'ruang_id' => rand(6, 70),
                    'sarana_id' => rand(100, 1700),
                    'teknisi_id' => in_array($status, ['diproses', 'dikerjakan']) ? rand(1, 2) : null,

                    'laporan_judul' => "Laporan {$status} user {$userId} #{$i}",
                    'laporan_foto' => null,

                    'tingkat_kerusakan' => 'sedang',
                    'tingkat_urgensi' => 'sedang',
                    'dampak_kerusakan' => 'sedang',

                    'status_laporan' => $status,
                    'tanggal_diproses' => $status !== 'pending'
                        ? Carbon::now()->subDays(rand(2, 5))
                        : null,
                    'tanggal_selesai' => null,

                    'bobot' => in_array($status, ['diproses', 'dikerjakan'])
                        ? round(rand(300, 700) / 1000, 3)
                        : null,

                    'created_at' => Carbon::now()->subDays(rand(3, 8)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 3)),
                ];
            }
        }


        DB::table('t_laporan_kerusakan')->insert($data);
    }
}
