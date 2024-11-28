<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
use App\Models\Barang;
use App\Models\BarangTerkunci;
use Illuminate\Http\Request;

class BarangTerkunciController extends Controller
{
    // Menampilkan daftar barang terkunci
    public function index(Request $request)
    {
        $query = BarangTerkunci::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'LIKE', "%$search%")
                    ->orWhere('alasan_terkunci', 'LIKE', "%$search%");
            });
        }
        $data = $query->paginate(7)->appends($request->only('search'));
        $barangs = Barang::all();
        $barangTerkunci=$data;
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
        ActivityLogHelper::log(
            'buat',
            null,
            null,
            'kunci',
            $request->input('kode_barang')
        );
        return redirect()->back()->with('success', 'Barang berhasil dikunci silahkan liat di Master Data Barang Tekunci.');
    }

    // Menghapus barang terkunci
    public function destroy($kode_barang)
    {
        $barangTerkunci = BarangTerkunci::where('kode_barang', $kode_barang)->firstOrFail();
        $barangTerkunci->delete();
        ActivityLogHelper::log(
            'hapus',
            null,
            null,
            'kunci',                      
            $kode_barang
        );

        return redirect()->back()->with('sucess', 'Barang terkunci berhasil dilepas.');
    }


}
