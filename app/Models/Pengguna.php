<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\RolePengguna;
use Spatie\Permission\Traits\HasRoles;


class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'pengguna';


    protected $primaryKey = 'pengguna_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_pengguna',
        'username',
        'jabatan',
        'email',
        'password',
        // 'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
