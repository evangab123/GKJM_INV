<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogHelper;

class RuangController extends Controller
{

    public function index(Request $request)
    {
        $query = Ruang::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_ruang', 'LIKE', "%$search%");
            });
        }
        $data = $query->withCount('barang')
                        ->paginate(7)
                            ->appends($request->only('search'));

        return view('tambah.ruang', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruang' => 'required|string|max:255',
        ]);

        $ruang = Ruang::create($request->all());

        ActivityLogHelper::log(
            'buat',
            null,
            null,
            'ruang',
            $ruang->ruang_id
        );

        return redirect()->route('ruang.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }


    public function update(Request $request, $id)
    {
        $ruang = Ruang::findOrFail($id);
        $prev = $ruang->toArray();
        // if ($ruang->barang()->exists()) {
        //     return redirect()->route('ruang.index')->with('warning', 'Ruangan tidak dapat diedit karena sedang digunakan oleh barang lain.');
        // }

        $request->validate([
            'nama_ruang' => 'required|string|max:255',
        ]);

        $ruang = Ruang::findOrFail($id);
        $ruang->update($request->all());
        $new = $ruang->toArray();
        ActivityLogHelper::log(
            'perbarui',
            $new,
            $prev,
            'ruang',
            $ruang->ruang_id
        );

        return redirect()->route('ruang.index')->with('success', 'Ruangan berhasil diperbarui!');
    }



    public function destroy($id)
    {
        $ruang = Ruang::findOrFail($id);

        if ($ruang->barang()->exists()) {
            return redirect()->route('ruang.index')->with('warning', 'Ruangan tidak dapat dihapus karena sedang digunakan oleh barang lain.');
        }

        $ruang->delete();
        ActivityLogHelper::log(
            'hapus',
            null,
            null,
            'ruang',
            $ruang->ruang_id
        );
        return redirect()->route('ruang.index')->with('success', 'Ruangan berhasil dihapus.');
    }

}
