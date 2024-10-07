<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('hak.list', [
            'title' => 'Master Data Hak',
            'Roles' => Role::paginate(10),
            'permissions'=> Permission::all()
        ]);
    }

    public function create()
    {

        $roles = Role::all();
        return view('hak.create', [
            'pengguna' => Permission::paginate(10),
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_permission' => 'required|string|max:255|unique:permissions,name',
        ]);

        // Simpan permission ke database
        Permission::create(['name' => $request->nama_permission]);

        return redirect()->route('hak.index')->with('success', 'Permission berhasil ditambahkan!');
    }

    public function edit()
    {


        return view('hak.edit');
    }

    public function destroy(Permission $hak)
    {
        $hak->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('hak.index')->with('message', 'Hak/Permission berhasil dihapus!');
    }

    public function update()
    {
        return redirect()->route('hak.index')->with('message', 'Hak/Permission berhasil diperbaharui!');
    }


}
