<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pengadaan extends Model
{
    use HasFactory;

    protected $table = 'PengajuanPengadaan';

    protected $primaryKey = 'pengadaan_id';

    protected $fillable = [
        'nama_barang',
        'jumlah',
        'tanggal_pengajuan',
        'status_pengajuan',
        'pengaju_id',
        'referensi',
        'keterangan',
    ];

    public function pengguna()
    {
        // $result = DB::select("
        // SELECT Pengguna.*
        // FROM PengajuanPengadaan
        // JOIN Pengguna ON PengajuanPengadaan.pengaju_id = Pengguna.pengguna_id
        // ");
        return $this->belongsTo(Pengguna::class, 'pengaju_id', 'pengguna_id');
    }
}
