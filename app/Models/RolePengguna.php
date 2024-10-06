<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePengguna extends Model
{
    use HasFactory;

    protected $table = 'RolePengguna';

    protected $primaryKey = 'role_id';


    protected $fillable = [
        'nama_role',
        'slug'
    ];

    public $timestamps = true;

    // Relasi ke model Pengguna
    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'role_id', 'role_id');
    }
}
