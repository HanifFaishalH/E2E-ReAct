<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangan = [
            5 => [
                // Lantai 5
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang Laboratorium Praktik Y 1', 'ruang_kode' => 'LPY1', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang Teori 01', 'ruang_kode' => 'RT01', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang Teori 02', 'ruang_kode' => 'RT02', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang Teori 03', 'ruang_kode' => 'RT03', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang Teori 04', 'ruang_kode' => 'RT04', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang Teori 05', 'ruang_kode' => 'RT05', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang Teori 06', 'ruang_kode' => 'RT06', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang Teori 07', 'ruang_kode' => 'RT07', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang Teknisi 4', 'ruang_kode' => 'RT4', 'ruang_tipe' => 'teknisi', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang OB 1', 'ruang_kode' => 'ROB1', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang OB 2', 'ruang_kode' => 'ROB2', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang Toilet Barat 5', 'ruang_kode' => 'RTB5', 'ruang_tipe' => 'toilet', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Ruang Toilet Timur 5', 'ruang_kode' => 'RTS5', 'ruang_tipe' => 'toilet', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 5, 'ruang_nama' => 'Lobby Lantai 5', 'ruang_kode' => 'LL5', 'ruang_tipe' => 'lobby', 'created_at' => now(), 'updated_at' => now()],
            ],
            6 => [
                // Lantai 6
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang Laboratorium Praktik Y 2', 'ruang_kode' => 'LPY2', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang Laboratorium Praktik Y 3', 'ruang_kode' => 'LPY3', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang Arsip', 'ruang_kode' => 'RA1', 'ruang_tipe' => 'arsip', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang Workshop Ekosistem', 'ruang_kode' => 'RWE', 'ruang_tipe' => 'workshop', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Lab Sistem Informasi 1', 'ruang_kode' => 'LSI1', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Lab Sistem Informasi 2', 'ruang_kode' => 'LSI2', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Lab Sistem Informasi 3', 'ruang_kode' => 'LSI3', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang Baca', 'ruang_kode' => 'RB', 'ruang_tipe' => 'perpustakaan', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang OB 3', 'ruang_kode' => 'ROB3', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang OB 4', 'ruang_kode' => 'ROB4', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang Mushola 2', 'ruang_kode' => 'RM2', 'ruang_tipe' => 'mushola', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang Toilet Barat 6', 'ruang_kode' => 'RTB6', 'ruang_tipe' => 'toilet', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang Toilet Timur 6', 'ruang_kode' => 'RTS6', 'ruang_tipe' => 'toilet', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang Rapat 4', 'ruang_kode' => 'RR4', 'ruang_tipe' => 'rapat', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang D.01', 'ruang_kode' => 'RD.01', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang D.02', 'ruang_kode' => 'RD.02', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang D.03', 'ruang_kode' => 'RD.03', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang D.04', 'ruang_kode' => 'RD.04', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang D.05', 'ruang_kode' => 'RD.05', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang D.06', 'ruang_kode' => 'RD.06', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang Jurusan TI', 'ruang_kode' => 'RJT', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Ruang Program Studi', 'ruang_kode' => 'RPS', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 6, 'ruang_nama' => 'Lobby Lantai 6', 'ruang_kode' => 'LL6', 'ruang_tipe' => 'lobby', 'created_at' => now(), 'updated_at' => now()],
            ],
            7 => [
                // Lantai 7
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LPR 1', 'ruang_kode' => 'LPR1', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LPR 2', 'ruang_kode' => 'LPR2', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LPR 3', 'ruang_kode' => 'LPR3', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LPR 4', 'ruang_kode' => 'LPR4', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LPR 5', 'ruang_kode' => 'LPR5', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LPR 6', 'ruang_kode' => 'LPR6', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LPR 7', 'ruang_kode' => 'LPR7', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LPR 8', 'ruang_kode' => 'LPR8', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LKJ 1', 'ruang_kode' => 'LKJ1', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LKJ 2', 'ruang_kode' => 'LKJ2', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LKJ 3', 'ruang_kode' => 'LKJ3', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LERP', 'ruang_kode' => 'LERP', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LPY 4', 'ruang_kode' => 'LPY4', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LAI 1', 'ruang_kode' => 'LAI1', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LIG 1', 'ruang_kode' => 'LIG1', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang LIG 2', 'ruang_kode' => 'LIG2', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang OB 5', 'ruang_kode' => 'ROB5', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang OB 6', 'ruang_kode' => 'ROB6', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang Teknisi 5', 'ruang_kode' => 'RT5', 'ruang_tipe' => 'teknisi', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang Toilet Barat 7', 'ruang_kode' => 'RTB7', 'ruang_tipe' => 'toilet', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Ruang Toilet Timur 7', 'ruang_kode' => 'RTS7', 'ruang_tipe' => 'toilet', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 7, 'ruang_nama' => 'Lobby Lantai 7', 'ruang_kode' => 'LL7', 'ruang_tipe' => 'lobby', 'created_at' => now(), 'updated_at' => now()],
            ],
            8 => [
                // Lantai 8
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang LAI 2', 'ruang_kode' => 'LAI2', 'ruang_tipe' => 'lab', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Teori 08', 'ruang_kode' => 'RT08', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Teori 09', 'ruang_kode' => 'RT09', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Teori 10', 'ruang_kode' => 'RT10', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Teori 11', 'ruang_kode' => 'RT11', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Teori 12', 'ruang_kode' => 'RT12', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Teori 13', 'ruang_kode' => 'RT13', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Teori 14', 'ruang_kode' => 'RT14', 'ruang_tipe' => 'kelas', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Kantin 2', 'ruang_kode' => 'RK2', 'ruang_tipe' => 'kantin', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang OB 7', 'ruang_kode' => 'ROB7', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang OB 8', 'ruang_kode' => 'ROB8', 'ruang_tipe' => 'kantor', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Toilet Barat 8', 'ruang_kode' => 'RTB8', 'ruang_tipe' => 'toilet', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Toilet Timur 8', 'ruang_kode' => 'RTS8', 'ruang_tipe' => 'toilet', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Musik', 'ruang_kode' => 'RM', 'ruang_tipe' => 'musik', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Ruang Auditorium', 'ruang_kode' => 'R.Audit', 'ruang_tipe' => 'auditorium', 'created_at' => now(), 'updated_at' => now()],
                ['lantai_id' => 8, 'ruang_nama' => 'Lobby Lantai 8', 'ruang_kode' => 'LL8', 'ruang_tipe' => 'lobby', 'created_at' => now(), 'updated_at' => now()],
            ]
        ];

        $now = Carbon::now();

        $data = [];

        foreach ($ruangan as $lantai_id => $listRuangan) {
            foreach ($listRuangan as $ruang) {
                $data[] = [
                    'lantai_id' => $ruang['lantai_id'],
                    'gedung_id' => 1, // Tambahkan gedung_id dengan nilai 1 di sini
                    'ruang_nama' => $ruang['ruang_nama'],
                    'ruang_kode' => $ruang['ruang_kode'],
                    'ruang_tipe' => $ruang['ruang_tipe'],
                    'created_at' => $ruang['created_at'] ?? $now,
                    'updated_at' => $ruang['updated_at'] ?? $now,
                ];
            }
        }

        DB::table('m_ruang')->insertOrIgnore($data);
    }
}
