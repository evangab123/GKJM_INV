<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
use App\Helpers\PermissionHelper;
use App\Models\Barang;
use App\Models\Pengadaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengadaanController extends Controller
{
    public function index(Request $request)
    {
        $accessResult = PermissionHelper::AnyCanAccessPengadaan();
        //dd($accessResult);
        if (!$accessResult['access']) {
            abort(403, 'Unauthorized action.');
        }
        $query = Pengadaan::with('pengguna');
        if (!auth()->user()->hasRole(['Super Admin', 'Majelis'])) {
            $query->where('pengaju_id', auth()->user()->pengguna_id);
        }
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

        return view('pengadaan.list', [
            'pengadaan' => $data,
            'barang' => $barang
        ]);
    }

    public function store(Request $request)
    {
        $accessResult = PermissionHelper::AnyCanCreatePengadaan();
        if (!$accessResult['buat']) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            'referensi' => 'required|string|max:255',
            'jumlah' => 'required|numeric',
        ]);

        $pengadaan = Pengadaan::create([
            'tanggal_pengajuan' => now(),
            'pengaju_id' => Auth::user()->pengguna_id,
            'status_pengajuan' => "Diajukan",
            'nama_barang' => $request->input('nama_barang'),
            'jumlah' => $request->input('jumlah'),
            'referensi' => $request->input('referensi'),
            'keterangan' => $request->input('keterangan'),
        ]);
        ActivityLogHelper::log('Buat Pengadaan "' . $pengadaan->pengadaan_id . '"');

        return redirect()->route('pengadaan.index')->with('message', 'Pengadaan barang berhasil disimpan.');
    }

    // Metode untuk menyetujui pengadaan
    public function approve($id)
    {
        if (!auth()->user()->hasRole(['Super Admin', 'Majelis'])) {
            abort(403, 'Unauthorized action.');
        }
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->status_pengajuan = 'Disetujui';
        $pengadaan->save();
        ActivityLogHelper::log('Setuju akan pengadaan "' . $pengadaan->pengadaan_id . '"');
        return redirect()->route('pengadaan.index',[
            'from' => 'approve',
            'idp' => $id,])
            ->with('message', 'Pengadaan barang berhasil disetujui. Lanjutkan untuk membuat barang.');
    }

    public function CreateBarang($id)
    {
        if (!auth()->user()->hasRole(['Super Admin', 'Majelis'])) {
            abort(403, 'Unauthorized action.');
        }
        $pengadaan = Pengadaan::findOrFail($id);

        if ($pengadaan->status_pengajuan !== 'Disetujui') {
            return redirect()->route('pengadaan.index')->with('warning', 'Pengadaan ini belum disetujui, tidak dapat membuat barang.');
        }
        return redirect()->route('barang.create',[
            'from' => 'approve',
            'idp' => $id,])
            ->with('message', 'Membuat Barang dari Pengadaan yang sudah disetujui.');
    }
    // Metode untuk menolak pengadaan
    public function reject($id)
    {
        if (!auth()->user()->hasRole(['Super Admin', 'Majelis'])) {
            abort(403, 'Unauthorized action.');
        }
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->status_pengajuan = 'Ditolak';
        $pengadaan->save();
        ActivityLogHelper::log('Tolak akan pengadaan "' . $pengadaan->pengadaan_id . '"');
        return redirect()->route('pengadaan.index')->with('message', 'Pengadaan barang berhasil ditolak.');
    }

    //Metode untuk mengupdate pengadaan setelah diedit
    public function update(Request $request, $id)
    {
        $accessResult = PermissionHelper::AnyCanEditPengadaan();
        if (!$accessResult['edit']) {
            abort(403, 'Unauthorized action.');
        }
        $data = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            'referensi' => 'required|string|max:255',
            'jumlah' => 'required|numeric',
        ]);

        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->update($data);
        ActivityLogHelper::log('Perbarui akan pengadaan "' . $pengadaan->pengadaan_id . '"');

        return redirect()->route('pengadaan.index')->with('message', 'Pengadaan barang berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $accessResult = PermissionHelper::AnyCanDeletePengadaan();
        if (!$accessResult['delete']) {
            abort(403, 'Unauthorized action.');
        }
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->delete();
        ActivityLogHelper::log('Hapus akan pengadaan "' . $pengadaan->pengadaan_id . '"');

        return redirect()->route('pengadaan.index')->with('message', 'Pengadaan barang berhasil dihapus.');
    }

}

