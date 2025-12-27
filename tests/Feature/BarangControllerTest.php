<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Database\Seeders\{
    UsersSeeder,
    KategoriSeeder,
    BarangSeeder,
    LevelSeeder // Tambahkan jika ada LevelSeeder
};
use App\Models\UserModel;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\LevelModel; // Asumsi ada model level

class BarangControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed data yang dibutuhkan
        $this->seed([
            UsersSeeder::class,
            KategoriSeeder::class,
            BarangSeeder::class,
        ]);

        // SETUP USER ADMIN
        // Pastikan kita login sebagai user yang memenuhi syarat 'authorize:admin'
        // Sesuaikan logika ini dengan cara aplikasi Anda menentukan admin (misal level_id = 1)
        $this->adminUser = UserModel::whereHas('level', function($q) {
            $q->where('level_kode', 'ADM'); // Atau sesuaikan dengan kode admin di DB Anda
        })->first();

        // Jika tidak ketemu via query di atas, ambil user pertama (fallback)
        if (!$this->adminUser) {
            $this->adminUser = UserModel::first();
        }
    }

    /** @test */
    public function halaman_index_barang_dapat_diakses_oleh_admin()
    {
        $this->actingAs($this->adminUser);

        // Route: Route::get('/', [BarangController::class, 'index']);
        $response = $this->get(url('/barang'));

        $response->assertStatus(200);
        $response->assertViewIs('barang.index');
        $response->assertViewHas(['kategori', 'breadcrumbs', 'page', 'activeMenu']);
    }

    /** @test */
    public function list_barang_mengembalikan_json_datatables()
    {
        $this->actingAs($this->adminUser);

        // Route: Route::get('/list', [BarangController::class, 'list']);
        // Perhatikan: Menggunakan GET sesuai web.php Anda
        $response = $this->get(url('/barang/list')); 

        $response->assertStatus(200);
        
        // Pastikan format JSON DataTables
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data'
        ]);
    }

    /** @test */
    public function admin_dapat_menambah_barang_baru_via_ajax()
    {
        $this->actingAs($this->adminUser);

        $kategori = KategoriModel::first();

        $dataBaru = [
            'barang_nama' => 'Laptop Test Unit',
            'kategori_id' => $kategori->kategori_id,
            'spesifikasi' => 'Core i7, 16GB RAM',
        ];

        // Route: Route::post('/store_ajax', ...);
        $response = $this->postJson(url('/barang/store_ajax'), $dataBaru);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Barang created successfully'
                 ]);

        $this->assertDatabaseHas('m_barang', [
            'barang_nama' => 'Laptop Test Unit'
        ]);
    }

    /** @test */
    public function admin_dapat_mengupdate_barang_via_ajax()
    {
        $this->actingAs($this->adminUser);

        $barang = BarangModel::first();
        $kategori = KategoriModel::first();

        $dataUpdate = [
            'barang_nama' => 'Barang Updated',
            'kategori_id' => $kategori->kategori_id,
            'spesifikasi' => 'Spek baru'
        ];

        // Route: Route::put('/update_ajax/{id}', ...);
        $response = $this->putJson(url('/barang/update_ajax/' . $barang->barang_id), $dataUpdate);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Barang updated successfully'
                 ]);

        $this->assertDatabaseHas('m_barang', [
            'barang_id' => $barang->barang_id,
            'barang_nama' => 'Barang Updated'
        ]);
    }

    /** @test */
    public function admin_dapat_menghapus_barang_via_ajax()
    {
        $this->actingAs($this->adminUser);

        // Buat dummy barang agar aman dihapus
        $barang = BarangModel::create([
            'barang_nama' => 'To Be Deleted',
            'kategori_id' => KategoriModel::first()->kategori_id,
            'barang_kode' => 'DEL-999', 
            'spesifikasi' => '-'
        ]);

        // Route: Route::delete('/destroy_ajax/{id}', ...);
        $response = $this->deleteJson(url('/barang/destroy_ajax/' . $barang->barang_id));

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Barang deleted successfully'
                 ]);

        $this->assertDatabaseMissing('m_barang', [
            'barang_id' => $barang->barang_id
        ]);
    }
    
    /** @test */
    public function user_non_admin_tidak_bisa_akses_halaman_barang()
    {
        // Simulasi login sebagai mahasiswa/user biasa
        // Asumsi user_id 2 bukan admin, atau cari user dengan level member
        $nonAdmin = UserModel::where('user_id', '!=', $this->adminUser->user_id)->first();
        
        // Hati-hati jika semua user di seeder adalah admin, test ini mungkin fail
        if($nonAdmin) {
            $this->actingAs($nonAdmin);
            
            // Mencoba akses route admin
            // Karena middleware 'authorize:admin', harusnya return 403 atau 401
            $response = $this->get(url('/barang'));
            
            // Assert Forbidden
            $response->assertStatus(403); 
        }
    }
}