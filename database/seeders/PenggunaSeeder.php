<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;

class PenggunaSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the Pengguna table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Pengguna')->insert([
            [
                'nama_pengguna' => 'John Doe',
                'jabatan' => 'Manager',
                'email' => 'john.doe@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pengguna' => 'Jane Smith',
                'jabatan' => 'Staff',
                'email' => 'jane.smith@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'role_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_pengguna' => 'admin',
                'jabatan' => 'admin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin'),
                'role_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            //Pengguna::factory()->count(10)->create();
        ]);
    }
}
