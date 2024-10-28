<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTerkunci extends Model
{
    use HasFactory;

    protected $table = 'barang_terkunci';

    protected $fillable = [
        'kode_barang',
        'alasan_terkunci',
    ];
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang'); 
    }
}
