<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaranaModel extends Model
{
    use HasFactory;

    protected $table = 'm_sarana';
    protected $primaryKey = 'sarana_id';
    protected $fillable = [
        'sarana_kode',
        'ruang_id',
        'kategori_id',
        'barang_id',
        'gedung_id',
        'jumlah_laporan',
        'nomor_urut',
        'frekuensi_penggunaan',
        'tanggal_operasional',
        'tingkat_kerusakan_tertinggi',
        'skor_prioritas',
    ];

    public function ruang()
    {
        return $this->belongsTo(RuangModel::class, 'ruang_id', 'ruang_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }

    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }

    public function lantai()
    {
        return $this->belongsTo(LantaiModel::class, 'lantai_id', 'lantai_id');
    }

    // Tambahkan relasi ke GedungModel
    public function gedung()
    {
        return $this->belongsTo(GedungModel::class, 'gedung_id', 'gedung_id');
    }
}
