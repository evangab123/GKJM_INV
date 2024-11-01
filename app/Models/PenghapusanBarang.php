<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PenghapusanBarang extends Model
{
    use HasFactory;

    protected $table = 'penghapusanbarang';

    protected $primaryKey = 'penghapusan_id';

    // Allow mass assignment on the specified fields
    protected $fillable = [
        'kode_barang',
        'tanggal_penghapusan',
        'alasan_penghapusan',
        'nilai_sisa',
    ];

    public function barang()
    {
        // $results = DB::select('
        // SELECT b.*
        // FROM PenghapusanBarang as pb
        // JOIN Barang as b ON pb.kode_barang = b.kode_barang
        // ');
        // return $results;

        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }
}
