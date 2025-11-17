<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['level_nama' => 'Administrator', 'level_kode' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['level_nama' => 'Mahasiswa', 'level_kode' => 'mhs', 'created_at' => now(), 'updated_at' => now()],
            ['level_nama' => 'Dosen', 'level_kode' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
            ['level_nama' => 'Tenaga Kependidikan', 'level_kode' => 'tendik', 'created_at' => now(), 'updated_at' => now()],
            ['level_nama' => 'Teknisi', 'level_kode' => 'teknisi', 'created_at' => now(), 'updated_at' => now()],
            ['level_nama' => 'Sarana Prasarana', 'level_kode' => 'sarpras', 'created_at' => now(), 'updated_at' => now()],
        ];
        foreach ($levels as $level) {
            DB::table('m_level')->insertOrIgnore($level);
        }
    }
}