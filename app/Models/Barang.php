<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'kode_barang';

    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'kode_barang',
        'merek_barang',
        'perolehan_barang',
        'harga_pembelian',
        'tahun_pembelian',
        'nilai_ekonomis_barang',
        'jumlah',
        'keterangan',
        'ruang_id',
        'kondisi_id',
        'kategori_barang_id',
        'status_barang',
        'path_gambar',
    ];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id', 'ruang_id');
    }

    public function kondisi()
    {
        return $this->belongsTo(KondisiBarang::class, 'kondisi_id', 'kondisi_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_barang_id', 'kategori_barang_id');
    }
    public function detilketerangan()
    {
        return $this->hasMany(DetilKeteranganBarang::class, 'kode_barang', 'kode_barang');
    }

    public function barangTerkunci()
    {
        return $this->hasOne(BarangTerkunci::class, 'kode_barang', 'kode_barang');
    }

    public function pengadaan()
    {
        return $this->hasMany(Pengadaan::class, 'kode_barang', 'kode_barang');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'kode_barang', 'kode_barang');
    }

    public function pemakaian()
    {
        return $this->hasMany(pemakaian::class, 'kode_barang', 'kode_barang');
    }
}
