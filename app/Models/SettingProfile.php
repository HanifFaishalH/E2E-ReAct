<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class SettingProfile extends Authenticatable
{
    protected $table = 'm_users';
    protected $primaryKey = 'user_id';
    public $timestamps = false; // atau true jika kamu pakai created_at & updated_at

    protected $fillable = [
        'nama', 'username', 'no_induk', 'foto', 'password'
    ];
}
