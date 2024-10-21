<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%");
            });
        }
        $data = $query->paginate(7)->appends($request->only('search'));
        return view('hak.list', [
            'title' => 'Master Data Hak',
            'Roles' => Role::all(),
            'permissions' => $data,
        ]);
    }


    public function create()
    {

        $roles = Role::all();
        return view('hak.create', [
            'pengguna' => Permission::paginate(10),
            'roles' => $roles,
            'ruangs' => Ruang::all(),
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_hak_slug' => 'required|string|max:255|unique:permissions,name',
        ], [
            'nama_hak_slug.unique' => 'Hak sudah ada, silakan pilih yang lain.',
        ]);

        // Simpan permission ke database
        Permission::create(['name' => $request->nama_hak_slug]);
        ActivityLogHelper::log('Buat Hak "'.$request->input('nama_hak_slug').'"');
        return redirect()->route('hak.index')->with('success', 'Permission berhasil ditambahkan!');
    }



    public function destroy(Permission $hak)
    {
        ActivityLogHelper::log('Hapus Hak "'.$hak->name.'"');
        $hak->delete();
        ActivityLogHelper::log('Hapus Hak "' . $hak->name . '"');
        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('hak.index')->with('message', 'Hak/Permission berhasil dihapus!');
    }
}
