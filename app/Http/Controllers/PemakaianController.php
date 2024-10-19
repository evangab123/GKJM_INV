<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use Illuminate\Http\Request;

class PemakaianController extends Controller
{
    public function index()
    {
        $riwayatPemakaian = Pemakaian::with('barang', 'pengguna')->paginate(10);

        return view('pemakaian.list', compact('riwayatPemakaian'));
    }
}
