<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KategoriSeeder extends Seeder
{
    public function run()
    {

        $kategori = [
            [
                'kategori_kode' => 'PRB',
                'kategori_nama' => 'Perabotan',
            ],
            [
                'kategori_kode' => 'ELK',
                'kategori_nama' => 'Elektronik',
            ],
            [
                'kategori_kode' => 'PJ',
                'kategori_nama' => 'Peralatan Jaringan',
            ],
            [
                'kategori_kode' => 'BS',
                'kategori_nama' => 'Barang Sanitasi',
            ],
            [
                'kategori_kode' => 'ADM',
                'kategori_nama' => 'Administrasi Kantor',
            ],
            [
                'kategori_kode' => 'BL',
                'kategori_nama' => 'Barang Lainnya',
            ],
        ];

        $now = Carbon::now();

        $data = [];

        foreach ($kategori as $item) {
            $data[] = [
                'kategori_kode' => $item['kategori_kode'],
                'kategori_nama' => $item['kategori_nama'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('m_kategori')->insertOrIgnore($data);
    }
}
