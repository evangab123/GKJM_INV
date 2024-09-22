<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pengguna,email,' . Auth::user()->pengguna_id . ',pengguna_id',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|max:12|required_with:current_password',
            'password_confirmation' => 'nullable|min:8|max:12|required_with:new_password|same:new_password'
        ]);

        // dd( Auth::user());
        $user = Pengguna::find(Auth::user()->pengguna_id);

        $user->nama_pengguna = $request->input('nama_pengguna');
        $user->email = $request->input('email');

        // Check if the user is updating their password
        if (!is_null($request->input('current_password'))) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = Hash::make($request->input('new_password'));
            } else {
                return redirect()->back()->withInput()->withErrors(['current_password' => 'Password Sekarang Salah']);
            }
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile Berhasil Diperbaharui!');
    }
}
