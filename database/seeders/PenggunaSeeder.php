<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use App\Models\Pengguna;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        // Pastikan roles sudah ada
        $roles = Role::all();
        $permission = Permission::all();

        // Memasukkan pengguna
        DB::table('pengguna')->insert([
            [
                'nama_pengguna' => 'Supa Admin',
                'username'=>'admin',
                'jabatan' => 'Manager',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'remember_token' => null,
            ],
            [
                'nama_pengguna' => 'Jane Smith',
                'username'=>'js123',
                'jabatan' => 'Staff',
                'email' => 'jane.smith@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password1234'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'remember_token' => null,
            ],
            [
                'nama_pengguna' => 'Evander Gabriel',
                'username'=>'evgb123',
                'jabatan' => 'Magang',
                'email' => 'evgb@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12321'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'remember_token' => null,
            ],
        ]);

        // Mengassign role ke pengguna
        $pengguna = Pengguna::where('email', 'admin@admin.com')->first();
        $pengguna->assignRole($roles->find(1));
        $pengguna->givePermissionTo($permission->find(1));

        $pengguna = Pengguna::where('email', 'jane.smith@example.com')->first();
        $pengguna->assignRole($roles->find(2));

        $pengguna = Pengguna::where('email', 'evgb@gmail.com')->first();
        $pengguna->assignRole($roles->find(1));
        $pengguna->givePermissionTo($permission->find(1));

    }
}
