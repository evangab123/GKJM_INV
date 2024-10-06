<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RolePenggunaSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(PenggunaSeeder::class);
        $this->call(RuangSeeder::class);
        $this->call(KondisiSeeder::class);
        $this->call(KategoriSeeder::class);
        $this->call(BarangSeeder::class);
    }
}
