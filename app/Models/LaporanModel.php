<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanModel extends Model
{
    use HasFactory;

    protected $table = 't_laporan_kerusakan';
    protected $primaryKey = 'laporan_id';

    protected $fillable = [
        'user_id',
        'role',
        'gedung_id',
        'lantai_id',
        'ruang_id',
        'sarana_id',
        'teknisi_id',
        'laporan_judul',
        'laporan_foto',
        'tingkat_kerusakan',
        'tingkat_urgensi',
        'dampak_kerusakan',
        'status_laporan',
        'tanggal_diproses',
        'tanggal_selesai',
    ];

    protected $dates = [
        'tanggal_diproses',
        'tanggal_selesai',
        'created_at',
        'updated_at',
    ];

    // Relasi
    public function gedung()
    {
        return $this->belongsTo(GedungModel::class, 'gedung_id', 'gedung_id');
    }

    public function lantai()
    {
        return $this->belongsTo(LantaiModel::class, 'lantai_id', 'lantai_id');
    }

    public function ruang()
    {
        return $this->belongsTo(RuangModel::class, 'ruang_id', 'ruang_id');
    }

    public function sarana()
    {
        return $this->belongsTo(SaranaModel::class, 'sarana_id', 'sarana_id');
    }

    public function teknisi()
    {
        return $this->belongsTo(TeknisiModel::class, 'teknisi_id', 'teknisi_id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    // Akses foto lengkap URL
    public function getLaporanFotoAttribute($value)
    {
        if (!$value) return null;
    
        // Remove any duplicate path segments
        $value = str_replace('laporan_files/', '', $value);
        
        // Check if file exists in public path
        $publicPath = public_path('laporan_files/' . $value);
        if (file_exists($publicPath)) {
            return asset('laporan_files/' . $value);
        }
    
    return null; // or return a default image
    }

    public function scopeByUserLevel($query, $userLevel)
    {
        if ($userLevel === 'sarpras') {
            // Sarpras bisa melihat semua laporan, termasuk yang belum disetujui
            return $query;
        } elseif ($userLevel === 'teknisi') {
            // Teknisi hanya bisa melihat laporan yang ditugaskan ke dirinya
            $teknisiId = optional(auth()->user()->teknisi)->teknisi_id;
            return $query->where('teknisi_id', $teknisiId);
        } else {
            // Level lain (mhs, dosen, tendik) hanya bisa melihat laporan mereka sendiri
            return $query->where('user_id', auth()->id());
        }
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status_laporan', $status);
        }
        return $query;
    }

}
