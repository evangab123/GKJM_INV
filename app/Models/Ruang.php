<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    protected $table = 'ruang';

    protected $primaryKey = 'ruang_id';


    protected $fillable = [
        'nama_ruang',
    ];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'ruang_id', 'ruang_id');
    }
}
