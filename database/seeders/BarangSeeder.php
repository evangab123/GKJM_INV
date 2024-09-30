<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Seed the barang table with 2 different kinds of items.
     *
     * @return void
     */
    public function run()
    {
        // Barang::factory()->count(100)->create();
        // DB::table('barang')->insert([
        //     [
        //         'kode_barang' => 'BRG-001',
        //         'merek_barang' => 'Dell XPS 13',
        //         'harga_pembelian' => 15000000.00,
        //         'tahun_pembelian' => 2021,
        //         'perolehan_barang' => 'Pembelian',
        //         'nilai_ekonomis_barang' => 12000000.00,
        //         'jumlah' => 10,
        //         'status_barang' => 'Ada',
        //         'keterangan' => 'Laptop untuk karyawan',
        //         'ruang_id' => 1,
        //         'kondisi_id' => 1,
        //         'kategori_barang_id' => 1,
        //         // 'pengguna_id' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'kode_barang' => 'BRG-002',
        //         'merek_barang' => 'Samsung Smart TV',
        //         'harga_pembelian' => 8000000.00,
        //         'tahun_pembelian' => 2020,
        //         'perolehan_barang' => 'Pembelian',
        //         'nilai_ekonomis_barang' => 6000000.00,
        //         'jumlah' => 5,
        //         'status_barang' => 'Ada',
        //         'keterangan' => 'Televisi ruang rapat',
        //         'ruang_id' => 2,
        //         'kondisi_id' => 2,
        //         'kategori_barang_id' => 1,
        //         // 'pengguna_id' => 2,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ]
        // ]);
    }
}
