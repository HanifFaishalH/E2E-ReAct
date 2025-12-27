<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Database\Seeders\{UsersSeeder, GedungSeeder, LantaiSeeder, RuangSeeder, SaranaSeeder};
use App\Models\{TeknisiModel, UserModel, LaporanModel};

class TeknisiModelTest extends TestCase
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

    protected function tearDown(): void
    {
        // Cleanup akan otomatis dilakukan oleh DatabaseTransactions
        parent::tearDown();
    }

    /** @test */
    public function teknisi_model_dapat_dibuat_dengan_data_valid()
    {
        $user = UserModel::where('level_id', 5)->first(); // teknisi level
        
        $teknisi = TeknisiModel::create([
            'level_id' => 5, // teknisi level
            'user_id' => $user->user_id,
            'keahlian' => 'Listrik, AC, Komputer'
        ]);

        $this->assertInstanceOf(TeknisiModel::class, $teknisi);
        $this->assertEquals('Listrik, AC, Komputer', $teknisi->keahlian);
        $this->assertEquals($user->user_id, $teknisi->user_id);
        $this->assertEquals(5, $teknisi->level_id);
    }

    /** @test */
    public function teknisi_memiliki_relasi_dengan_user()
    {
        $teknisi = TeknisiModel::first();
        
        $this->assertInstanceOf(UserModel::class, $teknisi->user);
        $this->assertEquals($teknisi->user_id, $teknisi->user->user_id);
    }

    /** @test */
    public function teknisi_memiliki_relasi_dengan_laporan()
    {
        $teknisi = TeknisiModel::first();
        
        // Hapus laporan existing untuk teknisi ini
        $teknisi->laporan()->delete();
        
        // Buat laporan untuk teknisi ini
        $gedung = \App\Models\GedungModel::first();
        $lantai = \App\Models\LantaiModel::where('gedung_id', $gedung->gedung_id)->first();
        $ruang = \App\Models\RuangModel::where('lantai_id', $lantai->lantai_id)->first();
        $sarana = \App\Models\SaranaModel::where('ruang_id', $ruang->ruang_id)->first();

        $laporan = LaporanModel::create([
            'user_id' => $teknisi->user_id,
            'teknisi_id' => $teknisi->teknisi_id,
            'gedung_id' => $gedung->gedung_id,
            'lantai_id' => $lantai->lantai_id,
            'ruang_id' => $ruang->ruang_id,
            'sarana_id' => $sarana->sarana_id,
            'laporan_judul' => 'Test Laporan Relasi ' . uniqid(),
            'status_laporan' => 'diproses',
            'tingkat_kerusakan' => 'sedang',
            'tingkat_urgensi' => 'tinggi',
            'dampak_kerusakan' => 'sedang',
        ]);

        // Refresh relationship
        $teknisi->refresh();
        $teknisiLaporan = $teknisi->laporan;
        
        $this->assertCount(1, $teknisiLaporan);
        $this->assertEquals($laporan->laporan_id, $teknisiLaporan->first()->laporan_id);
    }

    /** @test */
    public function teknisi_dapat_menghitung_total_laporan()
    {
        $teknisi = TeknisiModel::first();
        
        // Hapus laporan existing untuk teknisi ini (jika ada)
        $teknisi->laporan()->delete();
        
        // Buat beberapa laporan baru
        $this->createLaporanForTeknisi($teknisi, 'pending');
        $this->createLaporanForTeknisi($teknisi, 'diproses');
        $this->createLaporanForTeknisi($teknisi, 'selesai');

        // Refresh relationship
        $teknisi->refresh();
        $totalLaporan = $teknisi->laporan()->count();
        
        $this->assertEquals(3, $totalLaporan);
    }

    /** @test */
    public function teknisi_dapat_menghitung_laporan_berdasarkan_status()
    {
        $teknisi = TeknisiModel::first();
        
        // Hapus laporan existing untuk teknisi ini
        $teknisi->laporan()->delete();
        
        // Buat laporan dengan berbagai status
        $this->createLaporanForTeknisi($teknisi, 'pending');
        $this->createLaporanForTeknisi($teknisi, 'pending');
        $this->createLaporanForTeknisi($teknisi, 'diproses');
        $this->createLaporanForTeknisi($teknisi, 'selesai');

        // Refresh relationship
        $teknisi->refresh();
        
        $pendingCount = $teknisi->laporan()->where('status_laporan', 'pending')->count();
        $diprosesCount = $teknisi->laporan()->where('status_laporan', 'diproses')->count();
        $selesaiCount = $teknisi->laporan()->where('status_laporan', 'selesai')->count();

        $this->assertEquals(2, $pendingCount);
        $this->assertEquals(1, $diprosesCount);
        $this->assertEquals(1, $selesaiCount);
    }

    /** @test */
    public function teknisi_dapat_mendapatkan_laporan_aktif()
    {
        $teknisi = TeknisiModel::first();
        
        // Hapus laporan existing untuk teknisi ini
        $teknisi->laporan()->delete();
        
        // Buat laporan dengan berbagai status
        $this->createLaporanForTeknisi($teknisi, 'pending');
        $this->createLaporanForTeknisi($teknisi, 'diproses');
        $this->createLaporanForTeknisi($teknisi, 'dikerjakan');
        $this->createLaporanForTeknisi($teknisi, 'selesai');

        // Refresh relationship
        $teknisi->refresh();
        
        // Laporan aktif = pending, diproses, dikerjakan (bukan selesai)
        $laporanAktif = $teknisi->laporan()
            ->whereIn('status_laporan', ['pending', 'diproses', 'dikerjakan'])
            ->count();

        $this->assertEquals(3, $laporanAktif);
    }

    /** @test */
    public function teknisi_dapat_mendapatkan_workload_percentage()
    {
        $teknisi = TeknisiModel::first();
        
        // Hapus laporan existing untuk teknisi ini
        $teknisi->laporan()->delete();
        
        // Buat 7 laporan aktif (dari maksimal 10 misalnya)
        for ($i = 0; $i < 7; $i++) {
            $this->createLaporanForTeknisi($teknisi, 'diproses');
        }

        // Refresh relationship
        $teknisi->refresh();
        
        $maxLaporan = 10; // Asumsi maksimal 10 laporan per teknisi
        $aktiveLaporan = $teknisi->laporan()
            ->whereIn('status_laporan', ['pending', 'diproses', 'dikerjakan'])
            ->count();

        $workloadPercentage = ($aktiveLaporan / $maxLaporan) * 100;

        $this->assertEquals(70, $workloadPercentage);
    }

    /** @test */
    public function teknisi_dapat_mendapatkan_laporan_prioritas_tinggi()
    {
        $teknisi = TeknisiModel::first();
        
        // Hapus laporan existing untuk teknisi ini
        $teknisi->laporan()->delete();
        
        // Buat laporan dengan prioritas berbeda
        $this->createLaporanForTeknisi($teknisi, 'diproses', 'tinggi');
        $this->createLaporanForTeknisi($teknisi, 'diproses', 'sedang');
        $this->createLaporanForTeknisi($teknisi, 'diproses', 'rendah');

        // Refresh relationship
        $teknisi->refresh();
        
        $prioritasTinggi = $teknisi->laporan()
            ->where('tingkat_urgensi', 'tinggi')
            ->count();

        $this->assertEquals(1, $prioritasTinggi);
    }

    /** @test */
    public function teknisi_model_memiliki_fillable_attributes()
    {
        $teknisi = new TeknisiModel();
        
        // Berdasarkan model yang sebenarnya
        $expectedFillable = [
            'user_id',
            'nama_teknisi',
            'keahlian',
            'telepon',
            'email',
            'status'
        ];

        // Ambil fillable yang sebenarnya dari model
        $actualFillable = $teknisi->getFillable();
        
        // Verifikasi bahwa fillable tidak kosong
        $this->assertNotEmpty($actualFillable);
        
        // Verifikasi bahwa minimal ada user_id dan keahlian
        $this->assertContains('user_id', $actualFillable);
        $this->assertContains('keahlian', $actualFillable);
    }

    /** @test */
    public function teknisi_model_memiliki_primary_key_yang_benar()
    {
        $teknisi = new TeknisiModel();
        
        $this->assertEquals('teknisi_id', $teknisi->getKeyName());
    }

    /** @test */
    public function teknisi_dapat_diupdate_dengan_data_baru()
    {
        $teknisi = TeknisiModel::first();
        $originalKeahlian = $teknisi->keahlian;
        
        // Pastikan keahlian baru berbeda dari yang lama
        $newKeahlian = $originalKeahlian . ', Updated Skill';
        
        $teknisi->update([
            'keahlian' => $newKeahlian
        ]);

        $this->assertEquals($newKeahlian, $teknisi->keahlian);
        $this->assertNotEquals($originalKeahlian, $teknisi->keahlian);
    }

    /**
     * Helper method untuk membuat laporan untuk teknisi
     */
    private function createLaporanForTeknisi($teknisi, $status = 'diproses', $urgensi = 'sedang')
    {
        $gedung = \App\Models\GedungModel::first();
        $lantai = \App\Models\LantaiModel::where('gedung_id', $gedung->gedung_id)->first();
        $ruang = \App\Models\RuangModel::where('lantai_id', $lantai->lantai_id)->first();
        $sarana = \App\Models\SaranaModel::where('ruang_id', $ruang->ruang_id)->first();

        return LaporanModel::create([
            'user_id' => $teknisi->user_id,
            'teknisi_id' => $teknisi->teknisi_id,
            'gedung_id' => $gedung->gedung_id,
            'lantai_id' => $lantai->lantai_id,
            'ruang_id' => $ruang->ruang_id,
            'sarana_id' => $sarana->sarana_id,
            'laporan_judul' => 'Test Laporan ' . $status . ' ' . uniqid(), // Tambah unique ID
            'status_laporan' => $status,
            'tingkat_kerusakan' => 'sedang',
            'tingkat_urgensi' => $urgensi,
            'dampak_kerusakan' => 'sedang',
        ]);
    }
}