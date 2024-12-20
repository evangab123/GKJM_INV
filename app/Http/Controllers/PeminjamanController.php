<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
use App\Helpers\PermissionHelper;
use App\Models\Peminjaman;
use App\Models\PeminjamanBarang;
use App\Models\Barang;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PeminjamanExport;

class PeminjamanController extends Controller
{
    // Tampilkan daftar peminjaman
    public function index(Request $request)
    {
        $accessResult = PermissionHelper::AnyCanAccessPeminjaman();
        if (!$accessResult['access']) {
            abort(403, 'Unauthorized action.');
        }
        $query = Peminjaman::with('barang', 'pengguna');
        if (!auth()->user()->hasRole(['Super Admin', 'Majelis'])) {
            $query->where('pengguna_id', auth()->user()->pengguna_id);
        }
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->Where('tanggal_peminjaman', 'LIKE', "%$search%")
                    ->orWhere('tanggal_kembali', 'LIKE', "%$search%")
                    ->orWhere('tanggal_pengembalian', 'LIKE', "%$search%")
                    ->orWhere('keterangan', 'LIKE', "%$search%")
                    ->orWhereHas('pengguna', function ($q) use ($search) {
                        $q->where('nama_pengguna', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('barang', function ($q) use ($search) {
                        $q->where('kode_barang', 'LIKE', "%$search%")
                            ->orWhere('merek_barang', 'LIKE', "%$search%");
                    });
            });
        }
        if ($request->filled('tanggal_peminjaman')) {
            $query->where('tanggal_peminjaman', $request->tanggal_peminjaman);
        }
        if ($request->filled('tanggal_kembali')) {
            $query->where('tanggal_kembali', $request->tanggal_kembali);
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
        $accessResult = PermissionHelper::AnyCanCreatePeminjaman();
        if (!$accessResult['buat']) {
            abort(403, 'Unauthorized action.');
        }
        $validated = $request->validate([
            'kode_barang' => 'required|exists:barang,kode_barang',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_peminjaman',
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

        // $jumlahBaru = $barang->jumlah - $validated['jumlah'];
        // // dd($jumlahBaru);
        // $barang->update(['jumlah' => $jumlahBaru]);

        $barang->update(['status_barang' => 'Dipinjam']);

        $peminjaman = Peminjaman::create([
            'kode_barang' => $validated['kode_barang'],
            'peminjam_id' => auth()->user()->pengguna_id,
            'tanggal_peminjaman' => $validated['tanggal_peminjaman'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'tanggal_pengembalian' => null,
            'status_peminjaman' => 'Dipinjam',
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);
        //dd($request);

        ActivityLogHelper::log(
            'buat',
            null,
            null,
            'peminjaman',
            $barang->kode_barang
        );

        return redirect()->route('peminjaman.index')->with('message', __('Peminjaman barang berhasil ditambahkan!'));
    }


    // Hapus peminjaman
    public function destroy($id)
    {
        $accessResult = PermissionHelper::AnyCanDeletePeminjaman();
        if (!$accessResult['delete']) {
            abort(403, 'Unauthorized action.');
        }
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();


        if (!$peminjaman) {
            return redirect()->route('peminjaman.index')->withErrors(__('Peminjaman tidak ditemukan.'));
        }

        $barang = Barang::where('kode_barang', $peminjaman->kode_barang)->first();

        if ($barang) {
            // $barang->jumlah += $peminjaman->jumlah;
            $barang->status_barang = 'Ada';
            $barang->save();
        }
        ActivityLogHelper::log(
            'hapus',
            null,
            null,
            'peminjaman',
            $barang->kode_barang
        );
        return redirect()->route('peminjaman.index')->with('message', __('Peminjaman berhasil dihapus.'));
    }

    public function kembalikan($id)
    {
        if (!auth()->user()->hasRole(['Super Admin'])) {
            abort(403, 'Unauthorized action.');
        }
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status_peminjaman == 'Dipinjam') {

            $peminjaman->status_peminjaman = 'Dikembalikan';
            $peminjaman->tanggal_pengembalian = now();
            $peminjaman->save();

            $barang = $peminjaman->barang;
            // $barang->jumlah += $peminjaman->jumlah;
            $barang->status_barang = 'Ada';
            $barang->save();
            ActivityLogHelper::log(
                'kembalikan',
                null,
                null,
                'peminjaman',
                $barang->kode_barang
            );

            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dikembalikan!');
        }
        return redirect()->route('peminjaman.index')->with('warning', 'Peminjaman Error');
    }

    public function export(Request $request)
    {
        $accessResult = PermissionHelper::AnyCanAccessPeminjaman();
        if (!$accessResult['access']) {
            abort(403, 'Unauthorized action.');
        }

        $query = Peminjaman::with('barang', 'pengguna');

        if (!auth()->user()->hasRole(['Super Admin', 'Majelis'])) {
            $query->where('pengguna_id', auth()->user()->pengguna_id);
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->Where('tanggal_peminjaman', 'LIKE', "%$search%")
                    ->orWhere('tanggal_kembali', 'LIKE', "%$search%")
                    ->orWhere('tanggal_pengembalian', 'LIKE', "%$search%")
                    ->orWhere('keterangan', 'LIKE', "%$search%")
                    ->orWhereHas('pengguna', function ($q) use ($search) {
                        $q->where('nama_pengguna', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('barang', function ($q) use ($search) {
                        $q->where('kode_barang', 'LIKE', "%$search%")
                            ->orWhere('merek_barang', 'LIKE', "%$search%");
                    });
            });
        }

        if ($request->filled('tanggal_peminjaman')) {
            $query->where('tanggal_peminjaman', $request->tanggal_peminjaman);
        }
        if ($request->filled('tanggal_kembali')) {
            $query->where('tanggal_kembali', $request->tanggal_kembali);
        }
        if ($request->filled('status')) {
            $query->where('status_peminjaman', $request->status);
        }

        $data = $query->get();

        return Excel::download(new PeminjamanExport($data), 'peminjaman_'.now()->format('d-m-Y').'.xlsx');
    }

}
