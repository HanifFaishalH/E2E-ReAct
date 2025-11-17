<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LaporanKerusakan extends Model
{
    use HasFactory;

    protected $table = 't_laporan_kerusakan';
    protected $primaryKey = 'laporan_id';

    protected $fillable = [
        'user_id',
        'barang_id',
        'kategori_id',
        'deskripsi',
        'status',
        'tanggal_laporan',
        'tanggal_diproses',
        'prioritas',
        'teknisi_id',
        'catatan_teknisi',
        'estimasi_biaya',
        'feedback_rating',
        'feedback_komentar'
    ];

    protected $dates = [
        'tanggal_laporan',
        'tanggal_diproses',
        'created_at',
        'updated_at'
    ];

    // Atau gunakan $casts untuk Laravel versi terbaru
    protected $casts = [
        'tanggal_laporan' => 'datetime',
        'tanggal_diproses' => 'datetime',
        'estimasi_biaya' => 'decimal:2',
        'feedback_rating' => 'integer'
    ];

    /**
     * Relasi ke tabel m_barang
     */
    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }

    /**
     * Relasi ke tabel m_kategori
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }

    /**
     * Relasi ke tabel m_user (pelapor)
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke tabel m_user (teknisi)
     */
    public function teknisi()
    {
        return $this->belongsTo(UserModel::class, 'teknisi_id', 'user_id');
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeByYear($query, $year)
    {
        if ($year) {
            return $query->whereYear('tanggal_diproses', $year);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan bulan
     */
    public function scopeByMonth($query, $month)
    {
        if ($month) {
            return $query->whereMonth('tanggal_diproses', $month);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan barang
     */
    public function scopeByBarang($query, $barangId)
    {
        if ($barangId) {
            return $query->where('barang_id', $barangId);
        }
        return $query;
    }

    /**
     * Scope untuk laporan yang sudah diproses
     */
    public function scopeProcessed($query)
    {
        return $query->whereNotNull('tanggal_diproses');
    }

    /**
     * Accessor untuk format tanggal indonesia
     */
    public function getTanggalLaporanFormattedAttribute()
    {
        return $this->tanggal_laporan ? 
            Carbon::parse($this->tanggal_laporan)->translatedFormat('d F Y') : null;
    }

    public function getTanggalDiprosesFormattedAttribute()
    {
        return $this->tanggal_diproses ? 
            Carbon::parse($this->tanggal_diproses)->translatedFormat('d F Y') : null;
    }
}