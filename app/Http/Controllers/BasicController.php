<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Models\Pengguna;
use App\Models\RolePengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;

class BasicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View|Factory
    {
        // dd(Auth::user()->role);
        $this->authorize('role-check', 'SuperAdmin');
        return view('basic.list', [
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
        $this->authorize('role-check', 'SuperAdmin');
        $roles = RolePengguna::all();
        return view('basic.create', [
            'title' => 'Buat Pengguna Inventaris',
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
        $this->authorize('role-check', 'SuperAdmin');
        Pengguna::create([
            'nama_pengguna' => $request->input('nama_pengguna'),
            'jabatan' => $request->input('jabatan'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $request->input('role_id'),
        ]);

        return redirect()->route('basic.index')->with('message', 'Pengguna berhasil ditambahkan!');
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
        $this->authorize('role-check', 'SuperAdmin');
        $user = Pengguna::find($pengguna_id);
        $roles = RolePengguna::all();
        $title = "Edit Pengguna"; // Atau judul sesuai konteks

        return view('basic.edit', compact('user', 'roles', 'title'));
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */



    //  public function update(EditUserRequest $request, Pengguna $basic): RedirectResponse
    //  {
    //      if($request->filled('password')) {
    //          $basic->password = Hash::make($request->password);
    //      }
    //      $basic->name = $request->name;
    //      $basic->email = $request->email;
    //      $basic->role_id=$request->role_id;
    //      $basic->save();

    //      return redirect()->route('basic.index')->with('message', 'Pengguna Berhasil Diperbaharui!');
    //  }
    public function update(Request $request, Pengguna $pengguna): RedirectResponse
    {
        $this->authorize('role-check', 'SuperAdmin');
        $pengguna->update([
            'nama_pengguna' => $request->input('nama_pengguna'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role_id'),
            'password' => $request->filled('password') ? bcrypt($request->input('password')) : $pengguna->password,
        ]);

        return redirect()->route('basic.index')->with('message', 'Pengguna Berhasil Diperbaharui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengguna $basic): RedirectResponse
    {
        $this->authorize('role-check', 'SuperAdmin');
        if (Auth::id() == $basic->getKey()) {
            return redirect()->route('basic.index')->with('warning', 'Anda tidak dapat menghapus diri sendiri!');
        }

        $basic->delete();

        return redirect()->route('basic.index')->with('message', 'Pengguna Berhasil Dihapus!');
    }
}
