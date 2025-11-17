<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TeknisiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Define a fixed list of unique skills
        $keahlianList = [
            'Listrik',
            'Jaringan',
            'AC',
            'Perabot',
            'Komputer'
        ];

        $levelId = 5;
        
        $userIds = DB::table('m_users')
            ->whereBetween('user_id', [5, 9])
            ->pluck('user_id')
            ->toArray();

            if (empty($userIds)) {
            $this->command->warn('Tabel m_users dengan user_id 5-9 kosong. Seeder Teknisi dibatalkan.');
            return;
        }

        // Check if the number of users matches the number of skills
        if (count($userIds) > count($keahlianList)) {
            $this->command->warn('Jumlah user_id melebihi jumlah keahlian yang tersedia. Tambahkan lebih banyak keahlian.');
            return;
        }

        // Shuffle the keahlian list to randomize assignment while ensuring uniqueness
        $shuffledKeahlian = $keahlianList;
        shuffle($shuffledKeahlian);

        // Assign keahlian to users
        foreach ($userIds as $index => $userId) {
            $keahlian = $shuffledKeahlian[$index];
            DB::table('m_teknisi')->insert([
                'user_id' => $userId,
                'keahlian' => $shuffledKeahlian[$index],
                'level_id' => $levelId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
