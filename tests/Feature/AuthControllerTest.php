<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Database\Seeders\UsersSeeder;
use App\Models\UserModel;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function user_dapat_login_dengan_data_dari_seeder()
    {
        $this->seed(UsersSeeder::class);

        $user = UserModel::where('username', 'mahasiswa')->first();
        $this->assertNotNull($user, 'User mahasiswa tidak ditemukan di DB react_test');

        $response = $this->postJson('/login', [
            'username' => 'mahasiswa',
            'password' => 'mahasiswa',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Login Berhasil',
                 ]);
    }

    /** @test */
    public function user_tidak_bisa_login_dengan_password_salah()
    {
        $this->seed(UsersSeeder::class);

        $response = $this->postJson('/login', [
            'username' => 'mahasiswa',
            'password' => 'salah123',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Login Gagal',
                 ]);
    }

    /** @test */
    public function user_dapat_logout_dengan_benar()
    {
        $this->seed(UsersSeeder::class);
        $user = UserModel::where('username', 'mahasiswa')->first();

        $this->actingAs($user);
        $response = $this->get('/logout');

        $response->assertRedirect('login');
        $this->assertGuest();
    }
}
