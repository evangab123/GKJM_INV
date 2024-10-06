<?php

namespace Database\Factories;

use App\Models\Pengguna;
use App\Models\RolePengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;

    public function definition()
    {
        return [
            'nama_pengguna' => $this->faker->name,
            // 'jabatan' => $this->faker->word,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => $this->faker->boolean ? now() : null,
            'password' => Hash::make('password'),
            'role_id' => RolePengguna::inRandomOrder()->first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
