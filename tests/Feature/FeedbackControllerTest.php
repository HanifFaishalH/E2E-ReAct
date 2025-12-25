<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Database\Seeders\{
    UsersSeeder,
    GedungSeeder,
    LantaiSeeder,
    RuangSeeder,
    KategoriSeeder,
    BarangSeeder,
    SaranaSeeder,
    LaporanSeeder
};
use App\Models\UserModel;
use App\Models\LaporanModel;

class FeedbackControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Seeder lengkap agar FK aman
        $this->seed([
            UsersSeeder::class,
            KategoriSeeder::class,
            GedungSeeder::class,
            LantaiSeeder::class,
            RuangSeeder::class,
            BarangSeeder::class,
            SaranaSeeder::class,
            LaporanSeeder::class,
        ]);
    }

    /** @test */
    public function halaman_feedback_menampilkan_laporan_selesai_yang_belum_diberi_feedback()
    {
        $user = UserModel::where('user_id', 2)->first();
        $this->actingAs($user);

        // Ambil laporan selesai milik user
        $laporan = LaporanModel::where('user_id', $user->user_id)
            ->where('status_laporan', 'selesai')
            ->first();

        $this->assertNotNull($laporan, 'Laporan selesai tidak ditemukan');

        $response = $this->get(route('feedback.index'));

        $response->assertStatus(200);
        $response->assertViewHas('laporans');

        $laporans = collect($response->viewData('laporans'))
            ->pluck('laporan_id')
            ->toArray();

        $this->assertContains($laporan->laporan_id, $laporans);
    }

    /** @test */
    public function user_dapat_menyimpan_feedback_untuk_laporan_selesai()
    {
        $user = UserModel::where('user_id', 2)->first();
        $this->actingAs($user);

        $laporan = LaporanModel::where('user_id', $user->user_id)
            ->where('status_laporan', 'selesai')
            ->first();

        $response = $this->post(route('feedback.store'), [
            'laporan_id' => $laporan->laporan_id,
            'rating' => 5,
            'komentar' => 'Pelayanan sangat baik',
        ]);

        $response->assertRedirect(route('feedback.index'));

        $this->assertDatabaseHas('t_feedback', [
            'laporan_id' => $laporan->laporan_id,
            'user_id' => $user->user_id,
            'rating' => 5,
            'komentar' => 'Pelayanan sangat baik',
        ]);
    }

    /** @test */
    public function laporan_yang_sudah_diberi_feedback_tidak_muncul_lagi_di_form()
    {
        $user = UserModel::where('user_id', 2)->first();
        $this->actingAs($user);

        $laporan = LaporanModel::where('user_id', $user->user_id)
            ->where('status_laporan', 'selesai')
            ->first();

        // Insert feedback manual
        DB::table('t_feedback')->insert([
            'user_id' => $user->user_id,
            'laporan_id' => $laporan->laporan_id,
            'rating' => 4,
            'komentar' => 'Cukup baik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('feedback.index'));

        $laporans = collect($response->viewData('laporans'))
            ->pluck('laporan_id')
            ->toArray();

        $this->assertNotContains($laporan->laporan_id, $laporans);
    }
}
