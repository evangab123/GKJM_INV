<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriBarang::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_kategori', 'LIKE', "%$search%");
            });
        }
        $data = $query->withCount('barang')
                        ->paginate(7)
                            ->appends($request->only('search'));

        return view('tambah.kategori', compact('data'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori = new KategoriBarang();
        $kategori->fill($request->only([
            'nama_kategori',
        ]));
        $kategori->save();

        ActivityLogHelper::log(
            'buat',
            null,
            null,
            'kategori',
            $kategori->kategori_barang_id
        );

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori barang berhasil ditambahkan.');
    }


    // Memperbarui kategori yang sudah ada
    public function update(Request $request, $kategori_barang_id)
    {
        $kategori = KategoriBarang::where('kategori_barang_id', $kategori_barang_id)->firstOrFail();

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $prev = $kategori->toArray();

        $kategori->fill($request->only([
            'nama_kategori',
        ]));
        $kategori->save();

        $new = $kategori->toArray();
        ActivityLogHelper::log(
            'perbarui',
            $new,
            $prev,
            'kategori',
            $kategori_barang_id
        );

        return redirect()->route('kategori.index', $kategori->kategori_barang_id)
            ->with('success', 'Kategori barang berhasil diperbarui.');
    }

    // Menghapus kategori
    public function destroy($kategori_barang_id)
    {
        $kategori = KategoriBarang::findOrFail($kategori_barang_id);
        if ($kategori->barang()->exists()) {
            return redirect()->route('kategori.index')->with('warning', 'Kategori tidak dapat dihapus karena sedang digunakan oleh barang lain.');
        }

        ActivityLogHelper::log(
            'hapus',
            null,
            null,
            'kategori',
            $kategori_barang_id
        );

        $kategori->delete();

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori barang berhasil dihapus.');
    }
}
