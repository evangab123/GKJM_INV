<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_pengguna' => 'required|string|max:255',
             'jabatan' => 'required|string|max:255',
             'username'=>'required|string|unique:pengguna,username,'. $this->pengguna_id . ',pengguna_id',
             'email' => 'required|string|email|max:255|unique:pengguna,email,' . $this->pengguna_id . ',pengguna_id',
            //  'password' => 'required|string|min:8|confirmed',
             'role_id' => 'required|exists:roles,id',
             'permissions' => 'array',
             'permissions.*' => 'string',
         ];
    }
}
