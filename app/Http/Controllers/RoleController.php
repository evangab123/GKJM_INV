<?php

namespace App\Http\Controllers;

use App\Events\RolePermissionsUpdated;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\View\Factory;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        $query = Role::with('permissions');
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%");
            });
        }
        $data = $query->paginate(7)->appends($request->only('search'));
        return view('role.list', [
            'title' => 'Master Data Role',
            'Roles' => $data,
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

        ]);

        Role::create(['name' => $request->input('nama_role'),]);
        ActivityLogHelper::log('Buat Role "'.$request->input('nama_role').'"');

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
        $prev = $role->toArray();
        $role->name = $request->nama_role;
        $role->save();
        $new = $role->toArray();
        ActivityLogHelper::log('Role "'.$request->input('nama_role').'" Diperbaharui!',$new,$prev);
        return redirect()->route('role.index')->with('message', 'Role dan Hak/Permission berhasil diupdate!');
    }

    function givePermission(Request $request, Role $role)
    {
        $permisiId = $request->input('permissions');
        $perm = Permission::find($permisiId);

        if ($role->hasPermissionTo($request->permissions)) {
            return back()->with('warning', 'Sudah memiliki hak');
        }
        $role->givePermissionTo($request->permissions);
        ActivityLogHelper::log('Beri Hak Akses Role "'.$role->name.'"', $request->permissions);
        return back()->with('success', 'Hak Role sudah diperbaharui');
    }
    public function removePermission($roleId, $permissionId)
    {
        $role = Role::findById($roleId);
        $permission = Permission::findById($permissionId);

        if ($role && $permission) {
            $role->revokePermissionTo($permission); // Menghapus permission dari role
            event(new RolePermissionsUpdated($role, $permission));
            ActivityLogHelper::log('Copot Hak Akeses Role "'.$role->name.'"', $permission->name);
            return back()->with('success', 'Salah satu Hak Role berhasil dihapus');

        }

        return back()->with('message', 'Gagal menghapus Hak Role');
    }

    public function getPermissionsByRole($roleId)
    {
        $role = Role::findOrFail($roleId);

        // Fetch permissions for the role
        $permissions = $role->permissions;

        // Return the permissions (will be empty if none found)
        return response()->json(['permissions' => $permissions]);
    }
    public function destroy($id)
    {
        // Find the role by ID
        $role = Role::find($id);

        if ($role) {
            // Log the activity before deletion
            ActivityLogHelper::log('Hapus Role "'.$role->name.'"');

            // Delete the role
            $role->delete();

            return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
        }

        return redirect()->route('role.index')->with('warning', 'Role tidak ditemukan.');
    }
}
