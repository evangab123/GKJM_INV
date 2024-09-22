<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePengguna extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'RolePengguna';

    // Primary key yang digunakan
    protected $primaryKey = 'role_id';

    // Fields yang dapat diisi (mass assignable)
    protected $fillable = [
        'nama_role',
    ];

    // Jika tidak menggunakan timestamp, bisa disable
    public $timestamps = true;

    // Relasi ke model Pengguna
    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'role_id', 'role_id');
    }
}
