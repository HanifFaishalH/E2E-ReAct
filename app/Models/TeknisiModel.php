<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeknisiModel extends Model
{
    use HasFactory;

    protected $table = 'm_teknisi';
    protected $primaryKey = 'teknisi_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'nama_teknisi',
        'keahlian',     // contoh: listrik, AC, komputer
        'telepon',
        'email',
        'status',       // aktif / nonaktif
    ];

    public function level() {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    // Relasi ke laporan yang ditangani
    public function laporan()
    {
        return $this->hasMany(LaporanModel::class, 'teknisi_id');
    }

    // Jika ada tabel pivot untuk keahlian teknisi
    public function keahlianList()
    {
        return $this->belongsToMany(KategoriModel::class, 'teknisi_keahlian', 'teknisi_id', 'kategori_id');
    }
}
