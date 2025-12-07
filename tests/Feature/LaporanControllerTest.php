<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Database\Seeders\{
    UsersSeeder,
    GedungSeeder,
    LantaiSeeder,
    RuangSeeder,
    SaranaSeeder,
    LaporanSeeder
};
use App\Models\UserModel;
use App\Models\LaporanModel;

class LaporanControllerTest extends TestCase
{
    use DatabaseTransactions;


    /** @test */
    public function user_dapat_membuat_laporan_baru_melalui_store_ajax()
    {
        // 🧩 Jalankan semua seeder agar foreign key lengkap
        $this->seed([
            UsersSeeder::class,
            GedungSeeder::class,
            LantaiSeeder::class,
            RuangSeeder::class,
            SaranaSeeder::class,
        ]);

        $user = UserModel::where('username', 'mahasiswa')->first();
        $this->assertNotNull($user);

        $this->actingAs($user);

        // Ambil data real dari database hasil seeder
        $gedung = \App\Models\GedungModel::first();
        $lantai = \App\Models\LantaiModel::where('gedung_id', $gedung->gedung_id)->first();
        $ruang = \App\Models\RuangModel::where('lantai_id', $lantai->lantai_id)->first();
        $sarana = \App\Models\SaranaModel::where('ruang_id', $ruang->ruang_id)->first();

        $this->assertNotNull($sarana);

        // 🔥 POST request AJAX
        $response = $this->postJson('/laporan/store_ajax', [
            'gedung_id' => $gedung->gedung_id,
            'lantai_id' => $lantai->lantai_id,
            'ruang_id' => $ruang->ruang_id,
            'sarana_id' => $sarana->sarana_id,
            'laporan_judul' => 'Kerusakan stopkontak ruang lab',
            'tingkat_kerusakan' => 'sedang',
            'tingkat_urgensi' => 'tinggi',
            'dampak_kerusakan' => 'sedang',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Laporan berhasil dibuat dan bobot telah dihitung',
                 ]);

        $this->assertDatabaseHas('t_laporan_kerusakan', [
            'laporan_judul' => 'Kerusakan stopkontak ruang lab',
        ]);
    }

    /** @test */
    public function sarpras_dapat_menerima_dan_menolak_laporan()
    {
        $this->seed([
            UsersSeeder::class,
            GedungSeeder::class,
            LantaiSeeder::class,
            RuangSeeder::class,
            SaranaSeeder::class,
            LaporanSeeder::class,
        ]);

        $sarpras = UserModel::where('username', 'sarpras')->first();
        $this->actingAs($sarpras);

        // ✅ Pastikan ambil laporan pending
        $laporan = LaporanModel::where('status_laporan', 'pending')->first();

        if (!$laporan) {
            $gedung = \App\Models\GedungModel::first();
            $lantai = \App\Models\LantaiModel::where('gedung_id', $gedung->gedung_id)->first();
            $ruang = \App\Models\RuangModel::where('lantai_id', $lantai->lantai_id)->first();
            $sarana = \App\Models\SaranaModel::where('ruang_id', $ruang->ruang_id)->first();

            $laporan = LaporanModel::create([
                'user_id' => $sarpras->user_id,
                'gedung_id' => $gedung->gedung_id,
                'lantai_id' => $lantai->lantai_id,
                'ruang_id' => $ruang->ruang_id,
                'sarana_id' => $sarana->sarana_id,
                'laporan_judul' => 'Laporan pending untuk test',
                'status_laporan' => 'pending',
                'tingkat_kerusakan' => 'rendah',
                'tingkat_urgensi' => 'sedang',
                'dampak_kerusakan' => 'kecil',
            ]);
        }

        $this->assertNotNull($laporan);

        // ✅ Terima laporan
        $acceptResponse = $this->postJson("/laporan/accept/{$laporan->laporan_id}");
        $acceptResponse->assertStatus(200)
                    ->assertJson([
                        'status' => 'success',
                        'message' => 'Laporan berhasil diterima, Laporan akan segera diproses.'
                    ]);

        // 🔄 Tolak laporan baru dengan data valid dari seeder
        $gedung = \App\Models\GedungModel::first();
        $lantai = \App\Models\LantaiModel::where('gedung_id', $gedung->gedung_id)->first();
        $ruang = \App\Models\RuangModel::where('lantai_id', $lantai->lantai_id)->first();
        $sarana = \App\Models\SaranaModel::where('ruang_id', $ruang->ruang_id)->first();

        $laporanBaru = LaporanModel::create([
            'user_id' => $sarpras->user_id,
            'gedung_id' => $gedung->gedung_id,
            'lantai_id' => $lantai->lantai_id,
            'ruang_id' => $ruang->ruang_id,
            'sarana_id' => $sarana->sarana_id,
            'laporan_judul' => 'Dummy untuk tolak',
            'tingkat_kerusakan' => 'rendah',
            'tingkat_urgensi' => 'rendah',
            'dampak_kerusakan' => 'kecil',
        ]);

        $rejectResponse = $this->postJson("/laporan/reject/{$laporanBaru->laporan_id}");
        $rejectResponse->assertStatus(200)
                    ->assertJson([
                        'status' => 'success',
                        'message' => 'Laporan berhasil ditolak.'
                    ]);
    }


    /** @test */
    public function sarpras_dapat_menghitung_bobot_laporan()
    {
        $this->seed([
            UsersSeeder::class,
            GedungSeeder::class,
            LantaiSeeder::class,
            RuangSeeder::class,
            SaranaSeeder::class,
            LaporanSeeder::class,
        ]);

        $sarpras = UserModel::where('username', 'sarpras')->first();
        $this->actingAs($sarpras);

        $laporan = LaporanModel::first();
        $this->assertNotNull($laporan);

        $response = $this->getJson("/laporan/kalkulasi/{$laporan->laporan_id}");
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Perhitungan bobot berhasil'
                 ]);
    }
}
