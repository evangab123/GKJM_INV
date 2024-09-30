<?php

namespace App\Http\Controllers;

use App\Models\RolePengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View|Factory
    {
        // dd(Auth::user()->role);
        return view('role.list', [
            'title' => 'Master Data Role',
            'Roles' => RolePengguna::paginate(10)
        ]);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): Factory|View
    {
        $roles = RolePengguna::all();
        return view('role.create', [
            'roles' => $roles,
        ]);
    }
}
