<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RuangSeeder extends Seeder
{
    /**
     * Seed the RuangPengguna table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Ruang')->insert([
            ['nama_ruang' => 'Gedung Gereja', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'K. Atas Utara', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'K. Atas Selatan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'K. Atas Tengah', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'Gamelan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'R. Bawah Lonceng', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'R. Kantor Gereja Bawah', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'R. Konsistori', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'R. Sidang', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'R. Aula Sidang Utara & Selatan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'R. Dapur', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'R. Pastori', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'R. Koster', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_ruang' => 'R. Mulmed', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}

