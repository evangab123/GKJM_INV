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
        $RolesSuper = Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Admin Ruang']);
        $RoleMajelis= Role::create(['name' => 'Majelis']);
        $usernormal = Role::create(['name' => 'Pengguna Normal']);
        $perm = Permission::create(['name'=>'semua-semua-semua']);
        $perm2=Permission::create(['name'=>'lihat-semua-semua']);
        $perm3 = Permission::create(['name'=>'lihat-pengadaan-semua']);
        $perm4 = Permission::create(['name'=>'buat-pengadaan-semua']);
        $perm5 = Permission::create(['name'=>'hapus-pengadaan-semua']);
        $perm6 = Permission::create(['name'=>'perbarui-pengadaan-semua']);

        $RolesSuper->givePermissionTo($perm);
        $RoleMajelis->givePermissionTo($perm2);
        $usernormal->givePermissionTo($perm3);
        $usernormal->givePermissionTo($perm4);
        $usernormal->givePermissionTo($perm5);
        $usernormal->givePermissionTo($perm6);

    }
}
