<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KondisiSeeder extends Seeder
{
    /**
     * Seed the RolePengguna table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('KondisiBarang')->insert([
            ['deskripsi_kondisi' => 'Baru','created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),],
            ['deskripsi_kondisi' => 'Baik','created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),],
            ['deskripsi_kondisi' => 'Cukup','created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),],
            ['deskripsi_kondisi' => 'Bekas','created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),],
            ['deskripsi_kondisi' => 'Rusak','created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),],
        ]);
    }
}

