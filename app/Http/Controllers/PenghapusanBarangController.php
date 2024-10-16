<?php

namespace App\Http\Controllers;

use App\Models\PenghapusanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ActivityLogHelper;

class PenghapusanBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        $penghapusan = PenghapusanBarang::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('kode_barang', 'LIKE', "%{$query}%")
                ->orWhere('alasan_penghapusan', 'LIKE', "%{$query}%");
        })->paginate(10); // Atur pagination sesuai kebutuhan

        return view('penghapusan.list', compact('penghapusan'));
    }
    public function destroy(PenghapusanBarang $penghapusan)
    {
        $barang = $penghapusan->barang;


        if ($barang) {
            $barang->status_barang = 'Ada';
            $barang->save();
        }

        // Hapus penghapusan
        $penghapusan->delete();

        ActivityLogHelper::log('Barang"' . $barang->kode_barang . '" ');

        return redirect()->route('penghapusan.index')->with('success', __('Data berhasil dihapus.'));
    }

}
