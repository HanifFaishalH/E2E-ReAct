<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_laporan_kerusakan')->insert([
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
                'tanggal_diproses' => Carbon::now()->subDays(2),
                'tanggal_selesai' => null,

                'bobot' => 0.752,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(1),
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
                'tanggal_diproses' => Carbon::now()->subDays(5),
                'tanggal_selesai' => Carbon::now()->subDays(1),

                'bobot' => 0.483,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_id' => 5,
                'role' => 'tendik',
                'gedung_id' => 1,
                'lantai_id' => 8,
                'ruang_id' => 67,
                'sarana_id' => 1644,
                'teknisi_id' => null, // Belum ditugaskan

                'laporan_judul' => 'AC tidak dingin',
                'laporan_foto' => null,

                'tingkat_kerusakan' => 'rendah',
                'tingkat_urgensi' => 'rendah',
                'dampak_kerusakan' => 'kecil',

                'status_laporan' => 'pending',
                'tanggal_diproses' => null,
                'tanggal_selesai' => null,

                'bobot' => null,
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ]
        ]);
    }
}
