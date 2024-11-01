<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    use HasFactory;

    protected $table = 'riwayatpemakaibarang';


    protected $primaryKey = 'riwayat_id';


    protected $fillable = [
        'kode_barang',
        'pengguna_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id', 'pengguna_id');
    }
}
