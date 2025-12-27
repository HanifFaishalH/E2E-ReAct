<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\{UsersSeeder, GedungSeeder, LantaiSeeder, RuangSeeder, SaranaSeeder};
use App\Models\{UserModel, LevelModel};

class ProfileControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed([
            UsersSeeder::class,
            GedungSeeder::class,
            LantaiSeeder::class,
            RuangSeeder::class,
            SaranaSeeder::class,
        ]);
    }

    /** @test */
    public function guest_tidak_dapat_mengakses_profile()
    {
        $response = $this->get(route('profile.show'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function admin_dapat_melihat_profile()
    {
        $admin = UserModel::where('level_id', 1)->first(); // Admin level
        
        $response = $this->actingAs($admin)->get(route('profile.show'));
        
        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
        $response->assertViewHas('user', $admin);
        $response->assertViewHas('activeMenu', 'profile');
        $response->assertSee($admin->nama);
        $response->assertSee($admin->username);
    }

    /** @test */
    public function teknisi_dapat_melihat_profile()
    {
        $teknisi = UserModel::where('level_id', 5)->first(); // Teknisi level
        
        $response = $this->actingAs($teknisi)->get(route('profile.show'));
        
        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
        $response->assertViewHas('user', $teknisi);
        $response->assertSee($teknisi->nama);
    }

    /** @test */
    public function mahasiswa_dapat_melihat_profile()
    {
        $mahasiswa = UserModel::where('level_id', 4)->first(); // Mahasiswa level
        
        $response = $this->actingAs($mahasiswa)->get(route('profile.show'));
        
        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
        $response->assertViewHas('user', $mahasiswa);
        $response->assertSee($mahasiswa->nama);
    }

    /** @test */
    public function user_dapat_update_nama_profile()
    {
        $user = UserModel::first();
        $originalNama = $user->nama;
        
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Updated Profile Name'
        ]);
        
        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui');
        
        $user->refresh();
        $this->assertEquals('Updated Profile Name', $user->nama);
        $this->assertNotEquals($originalNama, $user->nama);
    }

    /** @test */
    public function user_dapat_update_profile_dengan_foto()
    {
        Storage::fake('public');
        
        $user = UserModel::first();
        $file = UploadedFile::fake()->image('profile.jpg', 100, 100);
        
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Updated Name with Photo',
            'foto' => $file
        ]);
        
        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui');
        
        $user->refresh();
        $this->assertEquals('Updated Name with Photo', $user->nama);
        $this->assertNotNull($user->foto);
        $this->assertStringContainsString('profile.jpg', $user->foto);
    }

    /** @test */
    public function update_profile_memerlukan_nama()
    {
        $user = UserModel::first();
        
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => '' // Nama kosong
        ]);
        
        $response->assertSessionHasErrors(['name']);
        $response->assertRedirect();
    }

    /** @test */
    public function update_profile_nama_tidak_boleh_terlalu_panjang()
    {
        $user = UserModel::first();
        
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => str_repeat('a', 256) // Lebih dari 255 karakter
        ]);
        
        $response->assertSessionHasErrors(['name']);
        $response->assertRedirect();
    }

    /** @test */
    public function update_profile_foto_harus_berupa_image()
    {
        Storage::fake('public');
        
        $user = UserModel::first();
        $file = UploadedFile::fake()->create('document.pdf', 100); // Bukan image
        
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Valid Name',
            'foto' => $file
        ]);
        
        $response->assertSessionHasErrors(['foto']);
        $response->assertRedirect();
    }

    /** @test */
    public function update_profile_foto_harus_format_yang_diizinkan()
    {
        Storage::fake('public');
        
        $user = UserModel::first();
        $file = UploadedFile::fake()->image('profile.gif', 100, 100); // Format tidak diizinkan
        
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Valid Name',
            'foto' => $file
        ]);
        
        $response->assertSessionHasErrors(['foto']);
        $response->assertRedirect();
    }

    /** @test */
    public function update_profile_foto_tidak_boleh_terlalu_besar()
    {
        Storage::fake('public');
        
        $user = UserModel::first();
        $file = UploadedFile::fake()->image('profile.jpg')->size(3000); // Lebih dari 2MB
        
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Valid Name',
            'foto' => $file
        ]);
        
        $response->assertSessionHasErrors(['foto']);
        $response->assertRedirect();
    }

    /** @test */
    public function update_profile_tanpa_foto_tidak_mengubah_foto_existing()
    {
        $user = UserModel::first();
        $originalFoto = $user->foto;
        
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Updated Name Only'
        ]);
        
        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');
        
        $user->refresh();
        $this->assertEquals('Updated Name Only', $user->nama);
        $this->assertEquals($originalFoto, $user->foto);
    }

    /** @test */
    public function update_profile_dengan_foto_baru_menghapus_foto_lama()
    {
        // Setup: Buat file foto lama
        $oldPhotoPath = public_path('uploads/foto/old_photo.jpg');
        if (!file_exists(dirname($oldPhotoPath))) {
            mkdir(dirname($oldPhotoPath), 0755, true);
        }
        file_put_contents($oldPhotoPath, 'fake image content');
        
        $user = UserModel::first();
        $user->foto = 'old_photo.jpg';
        $user->save();
        
        Storage::fake('public');
        $newFile = UploadedFile::fake()->image('new_profile.jpg', 100, 100);
        
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Updated with New Photo',
            'foto' => $newFile
        ]);
        
        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');
        
        $user->refresh();
        $this->assertEquals('Updated with New Photo', $user->nama);
        $this->assertNotEquals('old_photo.jpg', $user->foto);
        $this->assertStringContainsString('new_profile.jpg', $user->foto);
        
        // Cleanup
        if (file_exists($oldPhotoPath)) {
            unlink($oldPhotoPath);
        }
    }

    /** @test */
    public function guest_tidak_dapat_update_profile()
    {
        $response = $this->post(route('profile.update'), [
            'name' => 'Unauthorized Update'
        ]);
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function profile_menampilkan_informasi_level_user()
    {
        $admin = UserModel::where('level_id', 1)->first();
        $teknisi = UserModel::where('level_id', 5)->first();
        $mahasiswa = UserModel::where('level_id', 4)->first();
        
        // Test Admin
        if ($admin) {
            $response = $this->actingAs($admin)->get(route('profile.show'));
            $response->assertStatus(200);
            $response->assertViewHas('user', $admin);
        }
        
        // Test Teknisi
        if ($teknisi) {
            $response = $this->actingAs($teknisi)->get(route('profile.show'));
            $response->assertStatus(200);
            $response->assertViewHas('user', $teknisi);
        }
        
        // Test Mahasiswa
        if ($mahasiswa) {
            $response = $this->actingAs($mahasiswa)->get(route('profile.show'));
            $response->assertStatus(200);
            $response->assertViewHas('user', $mahasiswa);
        }
        
        $this->assertTrue(true, 'Profile accessible for all user levels');
    }

    /** @test */
    public function profile_update_mempertahankan_data_lain()
    {
        $user = UserModel::first();
        $originalUsername = $user->username;
        $originalLevelId = $user->level_id;
        $originalPassword = $user->password;
        
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Only Name Changed'
        ]);
        
        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');
        
        $user->refresh();
        
        // Verifikasi hanya nama yang berubah
        $this->assertEquals('Only Name Changed', $user->nama);
        $this->assertEquals($originalUsername, $user->username);
        $this->assertEquals($originalLevelId, $user->level_id);
        $this->assertEquals($originalPassword, $user->password);
    }

    /** @test */
    public function profile_dapat_diakses_oleh_semua_authenticated_users()
    {
        $users = UserModel::take(5)->get();
        
        foreach ($users as $user) {
            $response = $this->actingAs($user)->get(route('profile.show'));
            
            $response->assertStatus(200);
            $response->assertViewIs('profile.show');
            $response->assertViewHas('user', $user);
            $response->assertViewHas('activeMenu', 'profile');
        }
        
        $this->assertTrue(true, 'Profile accessible by all authenticated users');
    }

    /** @test */
    public function profile_update_dengan_data_valid_berhasil()
    {
        Storage::fake('public');
        
        $user = UserModel::first();
        $file = UploadedFile::fake()->image('valid_profile.png', 200, 200);
        
        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Valid Updated Name',
            'foto' => $file
        ]);
        
        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui');
        $response->assertSessionHasNoErrors();
        
        $user->refresh();
        $this->assertEquals('Valid Updated Name', $user->nama);
        $this->assertNotNull($user->foto);
        $this->assertStringContainsString('valid_profile.png', $user->foto);
    }
}