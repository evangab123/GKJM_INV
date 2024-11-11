<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamanbarang';

    protected $primaryKey = 'peminjaman_id';

    protected $fillable = [
        'kode_barang',
        'jumlah',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'peminjam_id',
        'keterangan',
        'status_peminjaman',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'peminjam_id', 'pengguna_id');
    }
}
