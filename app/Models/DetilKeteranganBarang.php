<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetilKeteranganBarang extends Model
{
    use HasFactory;

    protected $table = 'DetilKeteranganBarang'; // Nama tabel

    protected $primaryKey = 'keterangan_id'; // Primary key

    protected $fillable = [
        'kode_barang',
        'tanggal',
        'keterangan',
    ];

    // Relasi ke model Barang
    public function barang()
    {
        return $this->hasMany(Barang::class, 'kode_barang', 'kode_barang');
    }
}
