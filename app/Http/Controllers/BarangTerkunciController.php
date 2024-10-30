<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
use App\Models\Barang;
use App\Models\BarangTerkunci;
use Illuminate\Http\Request;

class BarangTerkunciController extends Controller
{
    // Menampilkan daftar barang terkunci
    public function index()
    {
        $barangTerkunci = BarangTerkunci::paginate(7);
        $barangs = Barang::all();
        // $kodeBarangTerkunci = BarangTerkunci::pluck('kode_barang')->toArray();
        return view('barang.terkunci.list', compact('barangTerkunci', 'barangs'));
    }

    // Menampilkan form untuk menambah barang terkunci
    public function store(Request $request)
    {
        // Validasi dan penyimpanan data
        $request->validate([
            'kode_barang' => 'required||exists:barang,kode_barang',
            'alasan_terkunci' => 'nullable|string',
        ]);

        BarangTerkunci::create($request->all());
        ActivityLogHelper::log('Buat Kunci untuk "' . $request->input('kode_barang') . '"');
        return redirect()->back()->with('success', 'Barang berhasil dikunci silahkan liat di Master Data Barang Tekunci.');
    }

    // Menghapus barang terkunci
    public function destroy($kode_barang)
    {
        $barangTerkunci = BarangTerkunci::where('kode_barang', $kode_barang)->firstOrFail();
        $barangTerkunci->delete();

        ActivityLogHelper::log('Hapus Kunci untuk "' . $kode_barang . '"');

        return redirect()->back()->with('sucess', 'Barang terkunci berhasil dilepas.');
    }


}
