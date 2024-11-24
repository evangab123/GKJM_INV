<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
use App\Helpers\PermissionHelper;
use App\Models\Barang;
use App\Models\Pemakaian;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PemakaianExport;

class PemakaianController extends Controller
{
    public function index(Request $request)
    {
        $accessResult = PermissionHelper::AnyCanAccessPemakaian();
        if (!$accessResult['access']) {
            abort(403, 'Unauthorized action.');
        }
        $query = Pemakaian::with('barang', 'pengguna');
        if (!auth()->user()->hasRole(['Super Admin', 'Majelis'])) {
            $query->where('pengguna_id', auth()->user()->pengguna_id);
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->Where('tanggal_mulai', 'LIKE', "%$search%")
                    ->orWhere('tanggal_selesai', 'LIKE', "%$search%")
                    ->orWhere('keterangan', 'LIKE', "%$search%")
                    ->orWhereHas('pengguna', function ($q) use ($search) {
                        $q->where('nama_pengguna', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('barang', function ($q) use ($search) {
                        $q->where('kode_barang', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('barang', function ($q) use ($search) {
                        $q->where('merek_barang', 'LIKE', "%$search%");
                    });
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal_mulai', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal_selesai', $request->tanggal_mulai);
        }

        $barang = Barang::where('status_barang', 'Ada')
        ->whereNotIn('kode_barang', function ($query) {
            $query->select('kode_barang')->from('barang_terkunci');
        })
        ->get();



        $data = $query->paginate(7);
        return view('pemakaian.list', compact('data', 'barang'));
    }

    public function store(Request $request)
    {
        $accessResult = PermissionHelper::AnyCanCreatePemakaian();
        if (!$accessResult['buat']) {
            abort(403, 'Unauthorized action.');
        }
        $validated = $request->validate([
            'kode_barang' => 'required|exists:barang,kode_barang',
            'tanggal_mulai' => 'required|date|before_or_equal:tanggal_selesai',
            // 'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $barang = Barang::where('kode_barang', $validated['kode_barang'])->first();

        if (!$barang) {
            return redirect()->route('pemakaian.index')->withErrors(__('Barang tidak ditemukan.'));
        }
        if ($validated['jumlah'] > $barang->jumlah) {
            return redirect()->route('pemakaian.index')->withErrors(__('Jumlah yang dipakai melebihi stok yang tersedia.'));
        }

        $barang->update(['status_barang' => 'Dipakai']);

        $pemakaian = Pemakaian::create([
            'kode_barang' => $validated['kode_barang'],
            'pengguna_id' => auth()->user()->pengguna_id,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            // 'tanggal_selesai' => $validated['tanggal_selesai'],
            'status_pemakaian' => 'Dipakai',
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? '',
        ]);

        ActivityLogHelper::log('Buat pemakaian "' . $pemakaian->riwayat_id . '"');

        return redirect()->route('pemakaian.index')->with('message', __('Pemakaian barang berhasil ditambahkan!'));
    }

    public function destroy($id)
    {
        $accessResult = PermissionHelper::AnyCanDeletePemakaian();
        if (!$accessResult['delete']) {
            abort(403, 'Unauthorized action.');
        }
        $pemakaian = Pemakaian::find($id);

        if (!$pemakaian) {
            return redirect()->route('pemakaian.index')->withErrors(__('Pemakaian tidak ditemukan.'));
        }

        $barang = Barang::where('kode_barang', $pemakaian->kode_barang)->first();

        if ($barang) {
            $barang->update(['status_barang' => 'Ada']);
        }

        $pemakaian->delete();

        ActivityLogHelper::log('Hapus pemakaian "' . $pemakaian->riwayat_id . '"');

        return redirect()->route('pemakaian.index')->with('message', __('Pemakaian barang berhasil dihapus!'));
    }

    public function kembalikan($id)
    {
        if (!auth()->user()->hasRole(['Super Admin'])) {
            abort(403, 'Unauthorized action.');
        }
        $pemakaian = Pemakaian::findOrFail($id);

        if ($pemakaian->status_pemakaian == 'Dipakai') {
            $pemakaian->status_pemakaian = 'Dikembalikan';
            $pemakaian->tanggal_selesai = now();
            $pemakaian->save();
            $barang = $pemakaian->barang;
            $barang->status_barang = 'Ada';
            $barang->save();
            ActivityLogHelper::log('Kembalikan barang Pemakaian "' . $pemakaian->riwayat_id . '"');
            return redirect()->route('pemakaian.index')->with('success', 'Pemakaian berhasil dikembalikan!');
        }

        return redirect()->route('pemakaian.index')->with('warning', 'Pemakaian Error');
    }

    public function export(Request $request)
    {
        $accessResult = PermissionHelper::AnyCanAccessPemakaian();
        if (!$accessResult['access']) {
            abort(403, 'Unauthorized action.');
        }

        $query = Pemakaian::with('barang', 'pengguna');

        if (!auth()->user()->hasRole(['Super Admin', 'Majelis'])) {
            $query->where('pengguna_id', auth()->user()->pengguna_id);
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->Where('tanggal_mulai', 'LIKE', "%$search%")
                    ->orWhere('tanggal_selesai', 'LIKE', "%$search%")
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

        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal_mulai', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal_selesai', $request->tanggal_selesai);
        }

        $data = $query->get();
        
        return Excel::download(new PemakaianExport($data), 'pemakaian_'.now()->format('Y-m-d').'.xlsx');
    }


}
