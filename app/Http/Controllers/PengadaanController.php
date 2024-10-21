<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    public function index()
    {
        $pengadaan = Pengadaan::paginate(10);

        return view('pengadaan.list', compact('pengadaan'));
    }
}

