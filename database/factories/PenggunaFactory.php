<?php

namespace Database\Factories;

use App\Models\Pengguna;
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
            'jabatan' => $this->faker->word,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => $this->faker->boolean ? now() : null,
            'password' => Hash::make('password'), // Use a default password or generate a random one
            'role_id' => $this->faker->numberBetween(1, 4), // Adjust as needed based on your roles
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
