<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat role
        $Roles =Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Admin Ruang']);
        Role::create(['name' => 'Majelis']);
        Role::create(['name' => 'Pengguna Normal']);
        $perm = Permission::create(['name'=>'semua-semua-semua']);
        $Roles->givePermissionTo($perm);

    }
}
