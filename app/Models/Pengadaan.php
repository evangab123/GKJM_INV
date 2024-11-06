<?php

namespace App\Models;

use App\Http\Controllers\BarangController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pengadaan extends Model
{
    use HasFactory;

    protected $table = 'pengajuanpengadaan';

    protected $primaryKey = 'pengadaan_id';

    protected $fillable = [
        'merek_barang',
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

    public function barang()
    {
        // $result = DB::select("
        // SELECT barang.*
        // FROM PengajuanPengadaan
        // JOIN barang ON PengajuanPengadaan.kode_barang = barang.kode_barang
        // ");
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }

}
