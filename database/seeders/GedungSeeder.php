<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gedung= [
            ['gedung_id' => '1' ,'gedung_nama' => 'Gedung Teknik Sipil - Teknologi Informasi', 'gedung_kode' => 'TS-TI', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($gedung as $g) {
            DB::table('m_gedung')->insertOrIgnore($g);
        }
    }
}
