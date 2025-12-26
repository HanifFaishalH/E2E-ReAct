<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Database\Seeders\{
    UsersSeeder,
    GedungSeeder
};
use App\Models\UserModel;
use App\Models\GedungModel;

class GedungControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed data yang dibutuhkan
        $this->seed([
            UsersSeeder::class,
            GedungSeeder::class,
        ]);
    }

    // Helper untuk mengambil user admin (sesuaikan logic admin Anda)
    private function getAdminUser()
    {
        // Pastikan user ini lolos middleware 'authorize:admin'
        return UserModel::first(); 
    }

    /** @test */
    public function halaman_index_gedung_dapat_diakses_oleh_admin()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $response = $this->get(url('/gedung'));

        $response->assertStatus(200);
        $response->assertViewIs('gedung.index');
        $response->assertViewHas(['gedung', 'breadcrumbs', 'page', 'activeMenu']);
    }

    /** @test */
    public function list_gedung_mengembalikan_json_datatables()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $response = $this->get(url('/gedung/list'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'draw',
            'recordsTotal',
            'recordsFiltered'
        ]);
    }

    /** @test */
    public function admin_dapat_melihat_detail_gedung_via_ajax()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $gedung = GedungModel::first();

        // Route: /gedung/{id}/show
        $response = $this->getJson(url('/gedung/' . $gedung->gedung_id . '/show'));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'html'
                 ])
                 ->assertJson(['status' => 'success']);
    }

    /** @test */
    public function admin_dapat_menambah_gedung_baru()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $dataBaru = [
            'gedung_nama' => 'Gedung Test Baru',
            'gedung_kode' => 'G-TEST-01',
        ];

        // Route: POST /gedung/store
        $response = $this->postJson(url('/gedung/store'), $dataBaru);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Gedung berhasil ditambahkan'
                 ]);

        $this->assertDatabaseHas('m_gedung', [
            'gedung_nama' => 'Gedung Test Baru',
            'gedung_kode' => 'G-TEST-01'
        ]);
    }

    /** @test */
    public function validasi_gagal_jika_kode_gedung_sudah_ada()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        // Ambil gedung yang sudah ada
        $existingGedung = GedungModel::first();

        $dataDuplikat = [
            'gedung_nama' => 'Gedung Duplikat',
            'gedung_kode' => $existingGedung->gedung_kode, // Kode sama
        ];

        $response = $this->postJson(url('/gedung/store'), $dataDuplikat);

        $response->assertStatus(422) // Unprocessable Entity
                 ->assertJsonValidationErrors(['gedung_kode']);
    }

    /** @test */
    public function admin_dapat_mengupdate_gedung()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        $gedung = GedungModel::first();

        $dataUpdate = [
            'gedung_nama' => 'Gedung Updated',
            'gedung_kode' => $gedung->gedung_kode, // Kode boleh sama jika milik sendiri
        ];

        // Route: PUT /gedung/{id}/update
        $response = $this->putJson(url('/gedung/' . $gedung->gedung_id . '/update'), $dataUpdate);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Data gedung berhasil diperbarui!'
                 ]);

        $this->assertDatabaseHas('m_gedung', [
            'gedung_id' => $gedung->gedung_id,
            'gedung_nama' => 'Gedung Updated'
        ]);
    }

    /** @test */
    public function admin_dapat_menghapus_gedung()
    {
        $user = $this->getAdminUser();
        $this->actingAs($user);

        // Buat data dummy untuk dihapus
        $gedung = GedungModel::create([
            'gedung_nama' => 'Gedung To Delete',
            'gedung_kode' => 'DEL-999'
        ]);

        // Route: DELETE /gedung/destroy_ajax/{id}
        $response = $this->deleteJson(url('/gedung/destroy_ajax/' . $gedung->gedung_id));

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Gedung deleted successfully'
                 ]);

        $this->assertDatabaseMissing('m_gedung', [
            'gedung_id' => $gedung->gedung_id
        ]);
    }
}