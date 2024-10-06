<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat role
        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Admin Ruang']);
        Role::create(['name' => 'Majelis']);
        Role::create(['name' => 'Pengguna Normal']);
    }
}
