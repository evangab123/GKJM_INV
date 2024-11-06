<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|Factory
    {
        $query = Pengguna::with('roles', 'permissions');
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_pengguna', 'LIKE', "%$search%")
                    ->orWhere('username', 'LIKE', "%$search%")
                    ->orWhere('jabatan', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhereHas('roles', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('permissions', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%$search%");
                    });
            });
        }

        if ($request->filled('permission')) {
            $query->whereHas('permissions', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->permission . '%');
            });
        }

        if ($request->has('roles') && !empty($request->input('roles'))) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->input('roles'));
            });
        }

        $permissions = Permission::all();
        $roles = Role::all();
        $data = $query->paginate(7)->appends($request->only('search'))->appends($request->only('permission'))->appends($request->only('roles'));
        return view('pengguna.list', [
            'title' => 'Master Data Pengguna',
            'pengguna' => $data,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): Factory|View
    {

        $roles = Role::all();
        return view('pengguna.create', [
            'pengguna' => Pengguna::paginate(10),
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddUserRequest $request): RedirectResponse
    {
        $pengguna = Pengguna::create([
            'nama_pengguna' => $request->input('nama_pengguna'),
            'jabatan' => $request->input('jabatan'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $logData = [
            'nama_pengguna' => $request->input('nama_pengguna'),
            'jabatan' => $request->input('jabatan'),
            'username' => $request->input('username'),
            'email' => $request->input('email')
        ];

        if ($request->input('role_id')) {
            $role = Role::findById($request->input('role_id'));
            $pengguna->assignRole($role);
            $logData['role'] = $role->name; // Log assigned role
        }

        if ($request->has('permissions')) {
            $permissionsToSync = [];
            foreach ($request->input('permissions') as $permission) {
                if (Permission::where('name', $permission)->exists()) {
                    $permissionsToSync[] = $permission;
                }
            }

            $pengguna->syncPermissions($permissionsToSync);
            $logData['permissions'] = $permissionsToSync;
        }
        ActivityLogHelper::log('Buat Pengguna Baru "' . $request->input('username') . '"', $logData);

        return redirect()->route('pengguna.index')->with('message', 'Pengguna berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    public function edit($pengguna_id): Factory|View
    {
        $user = Pengguna::findOrFail($pengguna_id);
        $roles = Role::all();
        $title = "Edit Pengguna";

        return view('pengguna.edit', compact('user', 'roles', 'title'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(EditUserRequest $request, Pengguna $pengguna): RedirectResponse
    {
        $prev = [
            'nama_pengguna' => $pengguna->nama_pengguna,
            'username' => $pengguna->username,
            'email' => $pengguna->email,
            'jabatan' => $pengguna->jabatan,
            'roles' => $pengguna->roles->pluck('name')->toArray(),
            'permissions' => $pengguna->permissions->pluck('name')->toArray()
        ];

        // Update pengguna
        $pengguna->update([
            'username' => $request->input('username'),
            'nama_pengguna' => $request->input('nama_pengguna'),
            'email' => $request->input('email'),
            'jabatan' => $request->input('jabatan'),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : $pengguna->password,
        ]);

        $roleId = $request->input('role_id');
        $role = Role::find($roleId);

        if ($role) {
            $pengguna->syncRoles([$role->name]);
            $permissions = $role->permissions->pluck('name')->toArray();
            $newPermissions = $request->input('permissions'); // Retrieve permissions from the request
            $pengguna->syncPermissions($newPermissions);
        } else {
            return redirect()->back()->withErrors(['role_id' => 'Selected role does not exist.']);
        }
        $new = [
            'nama_pengguna' => $pengguna->nama_pengguna,
            'username' => $pengguna->username,
            'email' => $pengguna->email,
            'jabatan' => $pengguna->jabatan,
            'roles' => $pengguna->roles->pluck('name')->toArray(), // New roles
            'permissions' => $pengguna->permissions->pluck('name')->toArray() // New permissions
        ];

        ActivityLogHelper::log('Perbarui Pengguna: "' . $pengguna->username . '"', $new, $prev);

        return redirect()->route('pengguna.index')->with('message', 'Pengguna Berhasil Diperbaharui!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengguna $pengguna): RedirectResponse
    {
        if (Auth::id() == $pengguna->getKey()) {
            return redirect()->route('pengguna.index')->with('warning', 'Anda tidak dapat menghapus diri sendiri!');
        }
        $pengguna->delete();
        ActivityLogHelper::log('Hapus Pengguna: "' . $pengguna->username . '", "' . $pengguna->email . '"');
        return redirect()->route('pengguna.index')->with('message', 'Pengguna Berhasil Dihapus!');
    }
    public function getPermissionsByUser(Request $request, Pengguna $pengguna)
    {
        $roleId = $request->query('roleId');
        $role = Role::find($roleId);

        $rolePermissions = $role ? $role->permissions : [];

        $userPermissions = $pengguna->permissions;

        return response()->json([
            'permissions' => $rolePermissions,
            'userPermissions' => $userPermissions,
            'pengguna' => $pengguna,
        ]);
    }
}
