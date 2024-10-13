<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;


class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View|Factory
    {
        // dd(Auth::user()->role);
        return view('pengguna.list', [
            'title' => 'Master Data Pengguna',
            'pengguna' => Pengguna::paginate(10)
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
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Assign role using the role ID
        if ($request->input('role_id')) {
            $role = Role::findById($request->input('role_id')); // Find the role by ID
            $pengguna->assignRole($role); // Assign the role to the user
        }

        return redirect()->route('pengguna.index')->with('message', 'Pengguna berhasil ditambahkan!');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

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

        // Update pengguna
        $pengguna->update([
            'nama_pengguna' => $request->input('nama_pengguna'),
            'email' => $request->input('email'),
            'jabatan'=>$request->input('jabatan'),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : $pengguna->password,
        ]);

        $roleId = $request->input('role_id');
        $role = Role::find($roleId);

        if ($role) {
            $pengguna->syncRoles([$role->name]);
        } else {
            return redirect()->back()->withErrors(['role_id' => 'Selected role does not exist.']);
        }


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

        return redirect()->route('pengguna.index')->with('message', 'Pengguna Berhasil Dihapus!');
    }
}
