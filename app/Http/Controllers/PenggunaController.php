<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function update(Request $request, Pengguna $pengguna)
    {
        $pengguna->update([
            'nama_pengguna' => $request->input('nama_pengguna'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role_id'),
            'password' => $request->filled('password') ? bcrypt($request->input('password')) : $pengguna->password,
        ]);

        return redirect()->route('pengguna.show', $pengguna->id)->with('message', 'Pengguna updated successfully!');
    }

    // Tambahkan metode lainnya sesuai kebutuhan
}
