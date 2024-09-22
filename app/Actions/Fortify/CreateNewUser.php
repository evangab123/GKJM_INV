<?php

namespace App\Actions\Fortify;

use App\Models\Pengguna; // Ensure to use the correct namespace
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\Pengguna
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'nama_pengguna' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Pengguna::class, 'email'),
            ],
            'password' => $this->passwordRules(),
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ])->validate();

        return Pengguna::create([
            'nama_pengguna' => $input['nama_pengguna'],
            'jabatan' => $input['jabatan'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role_id' => $input['role_id'],
        ]);
    }
}
