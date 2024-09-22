<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;

    protected $table = 'KategoriBarang';

    protected $primaryKey = 'kategori_barang_id';

    protected $fillable = [
        'nama_kategori',
    ];

    public $timestamps = true;

    public function barang()
    {
        return $this->hasMany(Barang::class, 'kategori_barang_id', 'kategori_barang_id');
    }
}
