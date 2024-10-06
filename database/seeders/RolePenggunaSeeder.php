<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolePenggunaSeeder extends Seeder
{
    /**
     * Seed the RolePengguna table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('RolePengguna')->insert([
            ['nama_role' => 'Super Admin','slug'=>'super-admin','created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),],
            ['nama_role' => 'Majelis','slug'=>'majelis','created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),],
            ['nama_role' => 'Admin Ruang','slug'=>'admin-ruang-1','created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),],
            ['nama_role' => 'Pengguna','slug'=>'pengguna','created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),],
        ]);
    }
}

