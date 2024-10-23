<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengadaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengadaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengadaan::with('pengguna');
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'LIKE', "%$search%")
                    ->orWhere('tanggal_pengajuan', 'LIKE', "%$search%")
                    ->orWhere('jumlah', 'LIKE', "%$search%")
                    ->orWhere('status_pengajuan', 'LIKE', "%$search%")
                    ->orWhereHas('pengguna', function ($q) use ($search) {
                        $q->where('nama_pengguna', 'LIKE', "%$search%");
                    });
            });
        }
        $data = $query->paginate(7)->appends($request->only('search'));
        $barang = Barang::all();

        return view('pengadaan.list',[
            'pengadaan' => $data,
            'barang'=>$barang
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            'referensi' => 'required|string|max:255',
            'jumlah' => 'required|numeric',
        ]);

        Pengadaan::create([
            'tanggal_pengajuan' => now(),
            'pengaju_id' => Auth::user()->pengguna_id,
            'status_pengajuan' => "Diajukan",
            'nama_barang' => $request->input('nama_barang'),
            'jumlah' => $request->input('jumlah'),
            'referensi'=>$request->input('referensi'),
            'keterangan' => $request->input('keterangan'),
        ]);

        return redirect()->route('pengadaan.index')->with('message', 'Pengadaan barang berhasil disimpan.');
    }

    // Metode untuk menyetujui pengadaan
    public function approve($pengajuan_id)
    {
        $pengadaan = Pengadaan::findOrFail($pengajuan_id);
        $pengadaan->status_pengajuan = 'Disetujui';
        $pengadaan->save();
        return redirect()->route('barang.create')->with('message', 'Pengadaan barang berhasil disetujui lanjutkan untuk membuat barang.');
    }
    // Metode untuk menolak pengadaan
    public function reject($id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->status_pengajuan = 'Ditolak';
        $pengadaan->save();

        return redirect()->route('pengadaan.index')->with('message', 'Pengadaan barang berhasil ditolak.');
    }

    public function edit($id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        return view('pengadaan.index', compact('pengadaan'));
    }

    //Metode untuk mengupdate pengadaan setelah diedit
    public function update(Request $request, $id)
    {
        $data=$request->validate([
            'nama_barang' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            'referensi' => 'required|string|max:255',
            'jumlah' => 'required|numeric',
        ]);

        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->update($data);

        return redirect()->route('pengadaan.index')->with('message', 'Pengadaan barang berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->delete();

        return redirect()->route('pengadaan.index')->with('message', 'Pengadaan barang berhasil dihapus.');
    }

}

