<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\View\Factory;

class RoleController extends Controller
{

    public function index()
    {
        return view('role.list', [
            'title' => 'Master Data Role',
            'Roles' => Role::paginate(10),
            'permission' => Permission::all()
        ]);
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array|min:1', // Minimal 1 permission
            'permissions.*' => 'exists:permissions,name', // Validasi untuk setiap permission
        ]);

        $role = Role::create(['name' => $request->input('nama_role'),]);

        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('role.index')->with('success', 'Role berhasil dibuat dengan permission.');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all(); // Assuming you are also fetching permissions

        return view('role.edit', compact('role', 'permissions'));
    }


    public function update(Request $request, Role $role)
    {
        // Validasi input
        $request->validate([
            'nama_role' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->name = $request->nama_role;
        $role->save();

        return redirect()->route('role.index')->with('message', 'Role dan Hak/Permission berhasil diupdate!');
    }

    function givePermission(Request $request, Role $role)
    {
        $permisiId = $request->input('permissions');
        $perm = Permission::find($permisiId);

        if($role->hasPermissionTo($request->permissions)){
            return back()->with('warning','Sudah memiliki hak');
        }
        $role->givePermissionTo($request->permissions);
        return back()->with('success','Hak Role sudah diperbaharui');
    }
    public function removePermission($roleId, $permissionId)
    {
        $role = Role::findById($roleId);
        $permission = Permission::findById($permissionId);

        if ($role && $permission) {
            $role->revokePermissionTo($permission); // Menghapus permission dari role
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
