<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Database\Seeders\{
    UsersSeeder,
    GedungSeeder,
    LantaiSeeder,
    RuangSeeder,
    SaranaSeeder
};
use App\Models\{LaporanModel, UserModel, TeknisiModel, SaranaModel, GedungModel, LantaiModel, RuangModel};

class LaporanModelTest extends TestCase
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
    public function laporan_model_dapat_dibuat_dengan_data_valid()
    {
        $user = UserModel::first();
        $gedung = GedungModel::first();
        $lantai = LantaiModel::where('gedung_id', $gedung->gedung_id)->first();
        $ruang = RuangModel::where('lantai_id', $lantai->lantai_id)->first();
        $sarana = SaranaModel::where('ruang_id', $ruang->ruang_id)->first();

        $laporan = LaporanModel::create([
            'user_id' => $user->user_id,
            'gedung_id' => $gedung->gedung_id,
            'lantai_id' => $lantai->lantai_id,
            'ruang_id' => $ruang->ruang_id,
            'sarana_id' => $sarana->sarana_id,
            'laporan_judul' => 'Test Laporan Unit Test',
            'status_laporan' => 'pending',
            'tingkat_kerusakan' => 'sedang',
            'tingkat_urgensi' => 'tinggi',
            'dampak_kerusakan' => 'sedang',
        ]);

        $this->assertInstanceOf(LaporanModel::class, $laporan);
        $this->assertEquals('Test Laporan Unit Test', $laporan->laporan_judul);
        $this->assertEquals('pending', $laporan->status_laporan);
        $this->assertEquals($user->user_id, $laporan->user_id);
    }

    /** @test */
    public function laporan_memiliki_relasi_dengan_user()
    {
        $laporan = LaporanModel::first();
        
        $this->assertInstanceOf(UserModel::class, $laporan->user);
        $this->assertEquals($laporan->user_id, $laporan->user->user_id);
    }

    /** @test */
    public function laporan_memiliki_relasi_dengan_teknisi()
    {
        $laporan = LaporanModel::whereNotNull('teknisi_id')->first();
        
        if ($laporan && $laporan->teknisi_id) {
            $this->assertInstanceOf(TeknisiModel::class, $laporan->teknisi);
            $this->assertEquals($laporan->teknisi_id, $laporan->teknisi->teknisi_id);
        } else {
            $this->assertTrue(true, 'No laporan with teknisi assignment found');
        }
    }

    /** @test */
    public function laporan_memiliki_relasi_dengan_sarana()
    {
        $laporan = LaporanModel::first();
        
        $this->assertInstanceOf(SaranaModel::class, $laporan->sarana);
        $this->assertEquals($laporan->sarana_id, $laporan->sarana->sarana_id);
    }

    /** @test */
    public function laporan_memiliki_relasi_dengan_gedung_lantai_ruang()
    {
        $laporan = LaporanModel::first();
        
        // Test relasi gedung
        $this->assertInstanceOf(GedungModel::class, $laporan->gedung);
        $this->assertEquals($laporan->gedung_id, $laporan->gedung->gedung_id);
        
        // Test relasi lantai
        $this->assertInstanceOf(LantaiModel::class, $laporan->lantai);
        $this->assertEquals($laporan->lantai_id, $laporan->lantai->lantai_id);
        
        // Test relasi ruang
        $this->assertInstanceOf(RuangModel::class, $laporan->ruang);
        $this->assertEquals($laporan->ruang_id, $laporan->ruang->ruang_id);
    }

    /** @test */
    public function laporan_dapat_difilter_berdasarkan_status()
    {
        // Buat laporan dengan berbagai status
        $this->createLaporan('pending');
        $this->createLaporan('diproses');
        $this->createLaporan('dikerjakan');
        $this->createLaporan('selesai');

        $pendingCount = LaporanModel::where('status_laporan', 'pending')->count();
        $diprosesCount = LaporanModel::where('status_laporan', 'diproses')->count();
        $dikerjakanCount = LaporanModel::where('status_laporan', 'dikerjakan')->count();
        $selesaiCount = LaporanModel::where('status_laporan', 'selesai')->count();

        $this->assertGreaterThan(0, $pendingCount);
        $this->assertGreaterThan(0, $diprosesCount);
        $this->assertGreaterThan(0, $dikerjakanCount);
        $this->assertGreaterThan(0, $selesaiCount);
    }

    /** @test */
    public function laporan_dapat_difilter_berdasarkan_prioritas()
    {
        // Buat laporan dengan berbagai prioritas
        $this->createLaporan('pending', 'tinggi');
        $this->createLaporan('pending', 'sedang');
        $this->createLaporan('pending', 'rendah');

        $prioritasTinggi = LaporanModel::where('tingkat_urgensi', 'tinggi')->count();
        $prioritasSedang = LaporanModel::where('tingkat_urgensi', 'sedang')->count();
        $prioritasRendah = LaporanModel::where('tingkat_urgensi', 'rendah')->count();

        $this->assertGreaterThan(0, $prioritasTinggi);
        $this->assertGreaterThan(0, $prioritasSedang);
        $this->assertGreaterThan(0, $prioritasRendah);
    }

    /** @test */
    public function laporan_dapat_dihitung_berdasarkan_user()
    {
        $user = UserModel::first();
        
        // Buat beberapa laporan untuk user ini
        $this->createLaporanForUser($user, 'pending');
        $this->createLaporanForUser($user, 'diproses');

        $userLaporanCount = LaporanModel::where('user_id', $user->user_id)->count();
        
        $this->assertGreaterThanOrEqual(2, $userLaporanCount);
    }

    /** @test */
    public function laporan_dapat_dihitung_berdasarkan_teknisi()
    {
        $teknisi = TeknisiModel::first();
        
        // Buat beberapa laporan untuk teknisi ini
        $this->createLaporanForTeknisi($teknisi, 'diproses');
        $this->createLaporanForTeknisi($teknisi, 'dikerjakan');

        $teknisiLaporanCount = LaporanModel::where('teknisi_id', $teknisi->teknisi_id)->count();
        
        $this->assertGreaterThanOrEqual(2, $teknisiLaporanCount);
    }

    /** @test */
    public function laporan_model_memiliki_fillable_attributes()
    {
        $laporan = new LaporanModel();
        
        $actualFillable = $laporan->getFillable();
        
        // Verifikasi bahwa fillable tidak kosong
        $this->assertNotEmpty($actualFillable);
        
        // Verifikasi field-field penting ada
        $this->assertContains('user_id', $actualFillable);
        $this->assertContains('laporan_judul', $actualFillable);
        $this->assertContains('status_laporan', $actualFillable);
    }

    /** @test */
    public function laporan_model_memiliki_primary_key_yang_benar()
    {
        $laporan = new LaporanModel();
        
        $this->assertEquals('laporan_id', $laporan->getKeyName());
    }

    /** @test */
    public function laporan_dapat_diupdate_status()
    {
        $laporan = LaporanModel::first();
        $originalStatus = $laporan->status_laporan;
        
        // Update status
        $newStatus = $originalStatus === 'pending' ? 'diproses' : 'pending';
        $laporan->update([
            'status_laporan' => $newStatus
        ]);

        $this->assertEquals($newStatus, $laporan->status_laporan);
        $this->assertNotEquals($originalStatus, $laporan->status_laporan);
    }

    /** @test */
    public function laporan_dapat_dihitung_berdasarkan_tanggal()
    {
        // Buat laporan dengan tanggal berbeda
        $today = now();
        $yesterday = now()->subDay();
        
        $laporanToday = $this->createLaporanWithDate($today);
        $laporanYesterday = $this->createLaporanWithDate($yesterday);

        $todayCount = LaporanModel::whereDate('created_at', $today->toDateString())->count();
        $yesterdayCount = LaporanModel::whereDate('created_at', $yesterday->toDateString())->count();

        $this->assertGreaterThanOrEqual(1, $todayCount);
        $this->assertGreaterThanOrEqual(1, $yesterdayCount);
    }

    /**
     * Helper methods
     */
    private function createLaporan($status = 'pending', $urgensi = 'sedang')
    {
        $user = UserModel::first();
        $gedung = GedungModel::first();
        $lantai = LantaiModel::where('gedung_id', $gedung->gedung_id)->first();
        $ruang = RuangModel::where('lantai_id', $lantai->lantai_id)->first();
        $sarana = SaranaModel::where('ruang_id', $ruang->ruang_id)->first();

        return LaporanModel::create([
            'user_id' => $user->user_id,
            'gedung_id' => $gedung->gedung_id,
            'lantai_id' => $lantai->lantai_id,
            'ruang_id' => $ruang->ruang_id,
            'sarana_id' => $sarana->sarana_id,
            'laporan_judul' => 'Test Laporan ' . $status . ' ' . uniqid(),
            'status_laporan' => $status,
            'tingkat_kerusakan' => 'sedang',
            'tingkat_urgensi' => $urgensi,
            'dampak_kerusakan' => 'sedang',
        ]);
    }

    private function createLaporanForUser($user, $status = 'pending')
    {
        $gedung = GedungModel::first();
        $lantai = LantaiModel::where('gedung_id', $gedung->gedung_id)->first();
        $ruang = RuangModel::where('lantai_id', $lantai->lantai_id)->first();
        $sarana = SaranaModel::where('ruang_id', $ruang->ruang_id)->first();

        return LaporanModel::create([
            'user_id' => $user->user_id,
            'gedung_id' => $gedung->gedung_id,
            'lantai_id' => $lantai->lantai_id,
            'ruang_id' => $ruang->ruang_id,
            'sarana_id' => $sarana->sarana_id,
            'laporan_judul' => 'Test Laporan User ' . $status . ' ' . uniqid(),
            'status_laporan' => $status,
            'tingkat_kerusakan' => 'sedang',
            'tingkat_urgensi' => 'sedang',
            'dampak_kerusakan' => 'sedang',
        ]);
    }

    private function createLaporanForTeknisi($teknisi, $status = 'diproses')
    {
        $gedung = GedungModel::first();
        $lantai = LantaiModel::where('gedung_id', $gedung->gedung_id)->first();
        $ruang = RuangModel::where('lantai_id', $lantai->lantai_id)->first();
        $sarana = SaranaModel::where('ruang_id', $ruang->ruang_id)->first();

        return LaporanModel::create([
            'user_id' => $teknisi->user_id,
            'teknisi_id' => $teknisi->teknisi_id,
            'gedung_id' => $gedung->gedung_id,
            'lantai_id' => $lantai->lantai_id,
            'ruang_id' => $ruang->ruang_id,
            'sarana_id' => $sarana->sarana_id,
            'laporan_judul' => 'Test Laporan Teknisi ' . $status . ' ' . uniqid(),
            'status_laporan' => $status,
            'tingkat_kerusakan' => 'sedang',
            'tingkat_urgensi' => 'sedang',
            'dampak_kerusakan' => 'sedang',
        ]);
    }

    private function createLaporanWithDate($date)
    {
        $laporan = $this->createLaporan();
        $laporan->created_at = $date;
        $laporan->save();
        
        return $laporan;
    }
}