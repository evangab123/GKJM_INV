<?php

namespace App\Http\Controllers;

use App\Models\RolePengguna;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = RolePengguna::all();
        return view('roles.index',[
            'title' => 'New Pengguna',
            'roles' => $roles, // Kirim data roles ke view
        ]);
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
        ]);

        RolePengguna::create([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index')->with('message', 'RolePengguna created successfully!');
    }

    public function edit(RolePengguna $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, RolePengguna $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index')->with('message', 'RolePengguna updated successfully!');
    }

    public function destroy(RolePengguna $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('message', 'RolePengguna deleted successfully!');
    }
}
