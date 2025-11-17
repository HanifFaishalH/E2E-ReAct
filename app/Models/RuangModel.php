<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuangModel extends Model
{
    use HasFactory;

    protected $table = 'm_ruang';
    protected $primaryKey = 'ruang_id';
    protected $fillable = [
        'lantai_id',
        'ruang_nama',
        'ruang_kode',
        'ruang_tipe',
        'gedung_id',
    ];
    public $timestamps = true;

    public function lantai()
    {
        return $this->belongsTo(LantaiModel::class, 'lantai_id', 'lantai_id');
    }

    public function sarana()
    {
        return $this->hasMany(SaranaModel::class, 'ruang_id', 'ruang_id');
    }

    // Tambahkan relasi ke GedungModel
    public function gedung()
    {
        return $this->belongsTo(GedungModel::class, 'gedung_id', 'gedung_id');
    }
}
