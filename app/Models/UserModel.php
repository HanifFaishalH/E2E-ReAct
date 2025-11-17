<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'level_id', 'username', 'password', 'no_induk', 'nama', 'unit', 'expertise', 'created_at', 'updated_at'
    ];

    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }

    public function hasRole($role): bool
    {
        return $this->level->level_nama == $role;
    }

    public function getRole()
    {
        return $this->level->level_kode;
    }

    public function hasPermission($permission): bool
    {
        // Map permissions to roles (customize this based on your application)
        $permissionMap = [
            'admin' => ['admin'],
            'sarpras' => ['sarpras', 'admin'],
            'mhs' => ['mhs'],
            'dosen' => ['dosen'],
            'tendik' => ['tendik'],
        ];

        // Check if user has the required permission
        if (array_key_exists($permission, $permissionMap)) {
            return in_array($this->getRole(), $permissionMap[$permission]);
        }

        return false;
    }

    public function laporan()
    {
        return $this->hasMany(LaporanModel::class, 'user_id', 'user_id');
    }
}