<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use App\Models\Barang;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    // Tampilkan daftar peminjaman
    public function index(Request $request)
    {
        $query = Peminjaman::with('barang', 'pengguna');
        if (!auth()->user()->hasRole(['Super Admin', 'Majelis'])) {
            $query->where('pengguna_id', auth()->user()->pengguna_id);
        }
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'LIKE', "%$search%")
                    ->orWhereHas('barang', function ($q) use ($search) {
                        $q->where('merek_barang', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('pengguna', function ($q) use ($search) {
                        $q->where('nama_pengguna', 'LIKE', "%$search%");
                    });
            });
        }
        if ($request->filled('tanggal_peminjaman')) {
            $query->where('tanggal_peminjaman', $request->tanggal_peminjaman);
        }
        if ($request->filled('tanggal_pengembalian')) {
            $query->where('tanggal_pengembalian', $request->tanggal_pengembalian);
        }

        if ($request->filled('status')) {
            $query->where('status_peminjaman', $request->status);
        }

        $barang = Barang::where('status_barang', 'Ada')
            ->whereNotIn('kode_barang', function ($query) {
                $query->select('kode_barang')->from('barang_terkunci');
            })
            ->get();

        $data = $query->paginate(7);
        return view('peminjaman.list', compact('data', 'barang'));
    }

    // Simpan peminjaman barang
    public function store(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'kode_barang' => 'required|exists:barang,kode_barang',
            'tanggal_peminjaman' => 'nullable|date',
            // 'tanggal_pengembalian' => 'nullable|date|after_or_equal:tanggal_peminjaman',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);
        $barang = Barang::where('kode_barang', $validated['kode_barang'])->first();

        if (!$barang) {
            return redirect()->route('peminjaman.index')->withErrors(__('Barang tidak ditemukan.'));
        }

        if ($validated['jumlah'] > $barang->jumlah) {
            return redirect()->route('peminjaman.index')->withErrors(__('Jumlah yang dipinjam melebihi stok yang tersedia.'));
        }
        $jumlahBaru = $barang->jumlah - $validated['jumlah'];
        // dd($jumlahBaru);
        $barang->update(['jumlah' => $jumlahBaru]);

        $barang->update(['status_barang' => 'Dipinjam']);

        $peminjaman = Peminjaman::create([
            'kode_barang' => $validated['kode_barang'],
            'peminjam_id' => auth()->user()->pengguna_id,
            'tanggal_peminjaman' => $validated['tanggal_peminjaman'],
            'tanggal_pengembalian' => null,
            'status_peminjaman' => 'Dipinjam',
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? '',
        ]);

        ActivityLogHelper::log('Buat pemakaian "' . $peminjaman->peminjam_id . '"');

        return redirect()->route('peminjaman.index')->with('message', __('Peminjaman barang berhasil ditambahkan!'));
    }


    // Hapus peminjaman
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();


        if (!$peminjaman) {
            return redirect()->route('peminjaman.index')->withErrors(__('Peminjaman tidak ditemukan.'));
        }

        $barang = Barang::where('kode_barang', $peminjaman->kode_barang)->first();

        if ($barang) {
            $barang->jumlah += $peminjaman->jumlah;
            $barang->status_barang = 'Ada';
            $barang->save();
        }

        ActivityLogHelper::log('Buat pemakaian "' . $peminjaman->peminjam_idd . '"');
        return redirect()->route('peminjaman.index')->with('message', __('Peminjaman berhasil dihapus.'));
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status_peminjaman == 'Dipinjam') {

            $peminjaman->status_peminjaman = 'Dikembalikan';
            $peminjaman->save();

            $barang = $peminjaman->barang;
            $barang->jumlah += $peminjaman->jumlah;
            $barang->status_barang = 'Ada';
            $barang->save();
            ActivityLogHelper::log('Kembalikan barang Pemakaian "' . $peminjaman->peminjam_id . '"');

            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dikembalikan!');
        }
        return redirect()->route('peminjaman.index')->with('warning', 'Peminjaman Error');
    }


}
