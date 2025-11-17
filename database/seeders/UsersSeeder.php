<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            [
                'username' => 'admin',
                'password' => bcrypt('admin'),
                'nama' => 'Admin1',
                'no_induk' => '101',
                'level_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'mahasiswa',
                'password' => bcrypt('mahasiswa'),
                'nama' => 'Mahasiswa1',
                'no_induk' => '201',
                'level_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'dosen',
                'password' => bcrypt('dosen'),
                'nama' => 'Dosen1',
                'no_induk' => '301',
                'level_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'tendik',
                'password' => bcrypt('tendik'),
                'nama' => 'Tenaga Pendidik1',
                'no_induk' => '401',
                'level_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'teknisi1',
                'password' => bcrypt('teknisi1'),
                'nama' => 'Teknisi1',
                'no_induk' => '501',
                'level_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'teknisi2',
                'password' => bcrypt('teknisi2'),
                'nama' => 'Teknisi2',
                'no_induk' => '502',
                'level_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'teknisi3',
                'password' => bcrypt('teknisi3'),
                'nama' => 'Teknisi3',
                'no_induk' => '503',
                'level_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'teknisi4',
                'password' => bcrypt('teknisi4'),
                'nama' => 'Teknisi4',
                'no_induk' => '504',
                'level_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'teknisi5',
                'password' => bcrypt('teknisi5'),
                'nama' => 'Teknisi5',
                'no_induk' => '505',
                'level_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'sarpras',
                'password' => bcrypt('sarpras'),
                'nama' => 'Sarpras',
                'no_induk' => '601',
                'level_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        foreach ($users as $user) {
            DB::table('m_users')->insertOrIgnore($user);
        }
    }
}