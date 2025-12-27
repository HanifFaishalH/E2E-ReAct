<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Database\Seeders\{
    UsersSeeder,
    GedungSeeder,
    LantaiSeeder
};
use App\Models\UserModel;
use App\Models\LantaiModel;
use App\Models\GedungModel;

class LantaiControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed data yang dibutuhkan: User, Gedung (Parent), Lantai (Child)
        $this->seed([
            UsersSeeder::class,
            GedungSeeder::class,
            LantaiSeeder::class,
        ]);
    }

    // Helper untuk mengambil user admin
    private function getAdminUser()
    {
        return UserModel::first(); 
    }

    /** @test */
    public function halaman_index_lantai_dapat_diakses_oleh_admin()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $response = $this->get(url('/lantai'));

        $response->assertStatus(200);
        $response->assertViewIs('lantai.index');
        $response->assertViewHas(['lantai', 'gedung', 'breadcrumbs', 'page', 'activeMenu']);
    }

    /** @test */
    public function list_lantai_mengembalikan_json_datatables()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        // Route: GET /lantai/list
        $response = $this->get(url('/lantai/list'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'draw',
            'recordsTotal',
            'recordsFiltered'
        ]);
    }

    /** @test */
    public function admin_dapat_melihat_detail_lantai_via_ajax()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $lantai = LantaiModel::first();

        // Route: GET /lantai/show_ajax/{id}
        $response = $this->get(url('/lantai/show_ajax/' . $lantai->lantai_id));

        $response->assertStatus(200);
        $response->assertViewIs('lantai.show_ajax');
        $response->assertViewHas('lantai');
    }

    /** @test */
    public function admin_dapat_menambah_lantai_baru()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        // Ambil gedung yang valid untuk relasi
        $gedung = GedungModel::first();

        $dataBaru = [
            'gedung_id' => $gedung->gedung_id,
            'lantai_nama' => 'Lantai Test Baru',
        ];

        // Route: POST /lantai/store_ajax
        $response = $this->postJson(url('/lantai/store_ajax'), $dataBaru);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Lantai created successfully'
                 ]);

        // Pastikan masuk ke database (Asumsi nama tabel 'm_lantai' atau 't_lantai' sesuai model)
        // Saya gunakan assertDatabaseHas tanpa nama tabel spesifik jika Model sudah define table
        $this->assertDatabaseHas('m_lantai', [ // Sesuaikan nama tabel jika bukan m_lantai
            'lantai_nama' => 'Lantai Test Baru',
            'gedung_id' => $gedung->gedung_id
        ]);
    }

    /** @test */
    public function validasi_gagal_jika_data_tidak_lengkap()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        // Kirim data kosong
        $response = $this->postJson(url('/lantai/store_ajax'), []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['gedung_id', 'lantai_nama']);
    }

    /** @test */
    public function admin_dapat_mengupdate_lantai()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $lantai = LantaiModel::first();
        $gedung = GedungModel::first();

        $dataUpdate = [
            'gedung_id' => $gedung->gedung_id,
            'lantai_nama' => 'Lantai Updated Name',
        ];

        // Route: PUT /lantai/update_ajax/{id}
        $response = $this->putJson(url('/lantai/update_ajax/' . $lantai->lantai_id), $dataUpdate);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Lantai updated successfully'
                 ]);

        $this->assertDatabaseHas('m_lantai', [
            'lantai_id' => $lantai->lantai_id,
            'lantai_nama' => 'Lantai Updated Name'
        ]);
    }

    /** @test */
    public function admin_dapat_menghapus_lantai()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        // Buat dummy lantai untuk dihapus
        $gedung = GedungModel::first();
        $lantai = LantaiModel::create([
            'gedung_id' => $gedung->gedung_id,
            'lantai_nama' => 'Lantai To Delete',
            'lantai_kode' => 'L-DEL' // Opsional jika ada kolom kode
        ]);

        // Route: DELETE /lantai/destroy_ajax/{id}
        $response = $this->deleteJson(url('/lantai/destroy_ajax/' . $lantai->lantai_id));

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Lantai deleted successfully'
                 ]);

        $this->assertDatabaseMissing('m_lantai', [
            'lantai_id' => $lantai->lantai_id
        ]);
    }
}