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
            ['nama_role' => 'SuperAdmin','created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),],
            ['nama_role' => 'Majelis','created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),],
            ['nama_role' => 'AdminRuang','created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),],
            ['nama_role' => 'Pengguna','created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),],
        ]);
    }
}

