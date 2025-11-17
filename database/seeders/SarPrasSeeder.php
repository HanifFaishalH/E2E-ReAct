<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SarPrasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sarprasUser = DB::table(table: 'm_users')
            ->where('username', 'sarpras')
            ->first();

        if ($sarprasUser) {
            $sarprasData = [
                [
                    'sarPras_id' => 1, // New ID for sarpras record
                    'user_id' => $sarprasUser->user_id, // Reference to the sarpras user
                    'level_id' => $sarprasUser->level_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];

            foreach ($sarprasData as $data) {
                DB::table('m_saranaPrasarana')->insertOrIgnore($data);
            }
        }
    }
}