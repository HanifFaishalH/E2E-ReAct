<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaranaSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks for truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('m_sarana')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();
        $data = [];
        $counter = 1;

        $roomConfigurations = [
            // Room ID 1: Ruang Laboratorium Praktik
            1 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],

            // Room ID 2: Ruang Teori
            2 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],

            // Room ID 3: Ruang Teori 02
            3 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],
            // Room ID 4: Ruang Teori 03
            4 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],
            // Room ID 5: Ruang Teori 04
            5 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],
            // Room ID 6: Ruang Teori 05 
            6 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],
            // Room ID 7: Ruang Teori 06 
            7 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],
            // Room ID 8: Ruang Teori 07
            8 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],
            // Room ID 9: Ruang Teknisi 4
            9 => [
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 3],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 10: Ruangan OB
            10 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 5],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 2],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 3],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 11: Ruangan OB
            11 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 5],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 2],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 3],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 12: Toilet Cowo
            12 => [
                ['kategori_id' => 4, 'barang_id' => 17, 'jumlah' => 4],
                ['kategori_id' => 4, 'barang_id' => 16, 'jumlah' => 3],
                ['kategori_id' => 4, 'barang_id' => 18, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 19, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 20, 'jumlah' => 2],
            ],

            // Room ID 13: Toilet Cewe
            13 => [
                ['kategori_id' => 4, 'barang_id' => 16, 'jumlah' => 3],
                ['kategori_id' => 4, 'barang_id' => 18, 'jumlah' => 3],
                ['kategori_id' => 4, 'barang_id' => 19, 'jumlah' => 3],
                ['kategori_id' => 4, 'barang_id' => 20, 'jumlah' => 3],
            ],

            // Room ID 14: Lobby Lantai 5
            14 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 11, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 3],
            ],

            // Room ID 15: Ruang Laboratorium Praktik Y2
            15 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 15],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 30],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 3],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
            ],

            // Room ID 16: Ruang Laboratorium Praktik Y3
            16 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 17: Ruang Arsip
            17 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 2],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 18: Ruang Workshop Ekosistem
            18 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 15],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
            ],

            // Room ID 19: Lab Sistem Informasi 1
            19 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 15],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 15],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
            ],

            // Room ID 20: Lab Sistem Informasi 2
            20 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 21: Lab Sistem Informasi 3
            21 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 22: Ruang Baca
            22 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 1],
            ],

            // Room ID 23: Ruang OB 3
            23 => [
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 2],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 3],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 24: Ruang Mushola 2
            24 => [
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
            ],

            // Room ID 25: Ruang Toilet Barat 6
            25 => [
                ['kategori_id' => 4, 'barang_id' => 17, 'jumlah' => 4],
                ['kategori_id' => 4, 'barang_id' => 16, 'jumlah' => 3],
                ['kategori_id' => 4, 'barang_id' => 18, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 19, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 20, 'jumlah' => 2],
            ],

            // Room ID 26: Ruang Toilet Timur 6
            26 => [
                ['kategori_id' => 4, 'barang_id' => 16, 'jumlah' => 3],
                ['kategori_id' => 4, 'barang_id' => 18, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 19, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 20, 'jumlah' => 2],
            ],

            // Room ID 27: Ruang Rapat 4
            27 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 28: Ruang D.01
            28 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],

            // Room ID 29: Ruang D.02
            29 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],

            // Room ID 30: Ruang D.03
            30 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],

            // Room ID 31: Ruang D.04
            31 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],

            // Room ID 32: Ruang D.05
            32 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],

            // Room ID 33: Ruang D.06
            33 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 30],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],

            // Room ID 34: Ruang D.06
            34 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 20],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
            ],

            // Room ID 35: Ruang Jurusan TI
            35 => [
                ['kategori_id' => 1, 'barang_id' => 4, 'jumlah' => 7],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 2],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 36: Ruang Program Studi
            36 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 3],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 2],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 37: Lobby Lantai 6
            37 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 11, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 3],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 3],
            ],

            // Room ID 38: Ruang LPR 1
            38 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 39: Ruang LPR 2
            39 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 40: Ruang LPR 3
            40 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 41: Ruang LPR 4
            41 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 42: Ruang LPR 5
            42 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 43: Ruang LPR 6
            43 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 44: Ruang LPR 7
            44 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 45: Ruang LPR 8
            45 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 46: Ruang LKJ 1
            46 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 3],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 47: Ruang LKJ 2
            47 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 3],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 48: Ruang LKJ 3
            48 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 3],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 49: Ruang LERP
            49 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 50: Ruang LPY 4
            50 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 51: Ruang LAI 1
            51 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 3],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 52: Ruang LIG 1
            52 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 53: Ruang LIG 2
            53 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 10],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 5],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 54: Ruang OB 5
            54 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 5],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 2],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 22, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 3],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 55: Ruang OB 6
            55 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 5],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 2],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 22, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 3],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 56: Ruang Teknisi 5
            56 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 3],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 2],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 13, 'jumlah' => 3],
                ['kategori_id' => 3, 'barang_id' => 14, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 57: Ruang Toilet Barat 7
            57 => [
                ['kategori_id' => 4, 'barang_id' => 17, 'jumlah' => 3],
                ['kategori_id' => 4, 'barang_id' => 16, 'jumlah' => 1],
                ['kategori_id' => 4, 'barang_id' => 18, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 19, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 20, 'jumlah' => 2],
            ],

            // Room ID 58: Ruang Toilet Timur 7
            58 => [
                ['kategori_id' => 4, 'barang_id' => 17, 'jumlah' => 3],
                ['kategori_id' => 4, 'barang_id' => 16, 'jumlah' => 1],
                ['kategori_id' => 4, 'barang_id' => 18, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 19, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 20, 'jumlah' => 2],
            ],

            // Room ID 59: Lobby Lantai 7
            59 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 2, 'barang_id' => 11, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 3],
            ],

            // Room ID 60: Ruang LAI 2
            60 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 3],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 61: Ruang Teori 08
            61 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 20],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 62: Ruang Teori 09
            62 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 20],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 63: Ruang Teori 10
            63 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 20],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 64: Ruang Teori 11
            64 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 20],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 65: Ruang Teori 12
            65 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 20],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 66: Ruang Teori 13
            66 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 20],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 67: Ruang Teori 14
            67 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 20],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 68: Ruang Kantin 2
            68 => [
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 3],
            ],

            // Room ID 69: Ruang OB 8
            69 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 5],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 2],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 3],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 21, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 22, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 3],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 2],
            ],

            // Room ID 70: Ruang Toilet Barat 8
            70 => [
                ['kategori_id' => 4, 'barang_id' => 17, 'jumlah' => 3],
                ['kategori_id' => 4, 'barang_id' => 16, 'jumlah' => 1],
                ['kategori_id' => 4, 'barang_id' => 18, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 19, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 20, 'jumlah' => 2],
            ],

            // Room ID 71: Ruang Toilet Timur 8
            71 => [
                ['kategori_id' => 4, 'barang_id' => 17, 'jumlah' => 3],
                ['kategori_id' => 4, 'barang_id' => 16, 'jumlah' => 1],
                ['kategori_id' => 4, 'barang_id' => 18, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 19, 'jumlah' => 2],
                ['kategori_id' => 4, 'barang_id' => 20, 'jumlah' => 2],
            ],

            // Room ID 72: Ruang Musik
            72 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],

            // Room ID 73: Ruang Auditorium
            73 => [
                ['kategori_id' => 1, 'barang_id' => 26, 'jumlah' => 200],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 3],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 5],
            ],

            // Room ID 74: Lobby Lantai 8
            74 => [
                ['kategori_id' => 1, 'barang_id' => 5, 'jumlah' => 5],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 2],
                ['kategori_id' => 2, 'barang_id' => 11, 'jumlah' => 2],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 15, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 3],
            ],

            // Room ID 75: Ruang D.07
            75 => [
                ['kategori_id' => 1, 'barang_id' => 1, 'jumlah' => 20],
                ['kategori_id' => 1, 'barang_id' => 7, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 8, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 9, 'jumlah' => 1],
                ['kategori_id' => 2, 'barang_id' => 10, 'jumlah' => 1],
                ['kategori_id' => 3, 'barang_id' => 12, 'jumlah' => 1],
                ['kategori_id' => 5, 'barang_id' => 23, 'jumlah' => 2],
                ['kategori_id' => 6, 'barang_id' => 24, 'jumlah' => 1],
                ['kategori_id' => 6, 'barang_id' => 25, 'jumlah' => 1],
            ],
        ];

        $counter = 1;
        $nomorUrutCounter = [];
        $frekuensiOptions = ['harian', 'mingguan', 'bulanan', 'tahunan'];
        $tingkatKerusakanOptions = ['rendah', 'sedang', 'tinggi'];

        foreach ($roomConfigurations as $roomId => $items) {
            foreach ($items as $item) {
                if (!isset($nomorUrutCounter[$roomId][$item['barang_id']])) {
                    $nomorUrutCounter[$roomId][$item['barang_id']] = 1;
                }

                for ($i = 0; $i < $item['jumlah']; $i++) {
                    $saranaKode = "SAR-".str_pad($counter, 4, '0', STR_PAD_LEFT);
                    $counter++;

                    // Randomize some values for realism
                    $frekuensi = $frekuensiOptions[array_rand($frekuensiOptions)];
                    $tingkatKerusakan = $tingkatKerusakanOptions[array_rand($tingkatKerusakanOptions)];
                    $skorPrioritas = $tingkatKerusakan ? mt_rand(10, 100) / 100 : null;
                    
                    // Random operational date (within last 5 years)
                    $tanggalOperasional = Carbon::now()
                        ->subYears(rand(0, 5))
                        ->subMonths(rand(0, 12))
                        ->subDays(rand(0, 30));

                    $data[] = [
                        'sarana_kode' => $saranaKode,
                        'gedung_id' => 1,
                        'ruang_id' => $roomId,
                        'kategori_id' => $item['kategori_id'],
                        'barang_id' => $item['barang_id'],
                        'jumlah_laporan' => rand(0, 10), // Random report count
                        'nomor_urut' => $nomorUrutCounter[$roomId][$item['barang_id']],
                        'frekuensi_penggunaan' => $frekuensi,
                        'tanggal_operasional' => $tanggalOperasional,
                        'tingkat_kerusakan_tertinggi' => $tingkatKerusakan,
                        'skor_prioritas' => $skorPrioritas,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    $nomorUrutCounter[$roomId][$item['barang_id']]++;

                    // Insert in batches of 500
                    if (count($data) >= 500) {
                        DB::table('m_sarana')->insert($data);
                        $data = [];
                    }
                }
            }
        }

        // Insert remaining records
        if (!empty($data)) {
            DB::table('m_sarana')->insert($data);
        }
    }
}