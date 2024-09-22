<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KondisiBarang extends Model
{
    use HasFactory;

    protected $table = 'KondisiBarang';

    protected $primaryKey = 'kondisi_id';

    protected $fillable = [
        'deskripsi_kondisi',
    ];

    public $timestamps = true;

    public function barang()
    {
        return $this->hasMany(Barang::class, 'kondisi_id', 'kondisi_id');
    }
}
