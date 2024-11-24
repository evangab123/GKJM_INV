<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionHelper;
use App\Models\PenghapusanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ActivityLogHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenghapusanBarangExport;

class PenghapusanBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = PenghapusanBarang::with('barang');
        $accessResult = PermissionHelper::AnyCanAccessPenghapusan();
        if (!$accessResult['access']) {
            abort(403, 'Unauthorized action.');
        }

        if (!empty($accessResult['room'])) {
            $query->whereIn('kode_barang', function ($q) use ($accessResult) {
                $q->select('kode_barang')
                    ->from('barang')
                    ->whereIn('ruang_id', function ($subQuery) use ($accessResult) {
                        $subQuery->select('ruang_id')->from('ruang')->whereIn('nama_ruang', $accessResult['room']);
                    });
            });
            //$namaRuang = implode("','", $accessResult['room']);
            //$penghapusan = DB::select("
            //                             SELECT *
            //                             FROM penghapusan_barang
            //                             WHERE kode_barang IN (
            //                                 SELECT kode_barang
            //                                 FROM barang
            //                                 WHERE ruang_id IN (
            //                                     SELECT id
            //                                     FROM ruang
            //                                     WHERE nama_ruang IN ('$namaRuang')
            //                                 )
            //                             )
            //                         ");
        }

        if ($request->filled('tanggal_penghapusan')) {
            $query->where('tanggal_penghapusan', $request->tanggal_penghapusan);
        }

        if ($request->filled('nilai_sisa_min') && $request->filled('nilai_sisa_max')) {
            $query->whereBetween('nilai_sisa', [
                $request->input('nilai_sisa_min'),
                $request->input('nilai_sisa_max')
            ]);
        } elseif ($request->filled('nilai_sisa_min')) {
            $query->where('nilai_sisa', '>=', $request->input('nilai_sisa_min'));
        } elseif ($request->filled('nilai_sisa_max')) {
            $query->where('nilai_sisa', '<=', $request->input('nilai_sisa_max'));
        }


        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'LIKE', "%$search%")
                    ->orWhere('alasan_penghapusan', 'LIKE', "%$search%")
                    ->orWhere('alasan_penghapusan', 'LIKE', "%$search%")
                    ->orWhere('nilai_sisa', 'LIKE', "%$search%");
            });
        }

        $penghapusan = $query->paginate(10)->appends($request->only('search'));
        return view('penghapusan.list', [
            'penghapusan' => $penghapusan,
        ]);
    }




    public function destroy(PenghapusanBarang $penghapusan)
    {
        $accessResult = PermissionHelper::AnyCanDeletePenghapusan();
        if (!$accessResult['delete']) {
            abort(403, 'Unauthorized action.');
        }

        $createdAt = $penghapusan->created_at;
        $deletePeriodDays = (int) env('DELETE_PERIOD_DAYS', 7);
        $cutoffDate = now()->subDays($deletePeriodDays);

        if ($createdAt < $cutoffDate) {
            return redirect()->route('penghapusan.index')->with('error', __('Data tidak dapat dihapus setelah :days hari.', ['days' => $deletePeriodDays]));
        }

        $barang = $penghapusan->barang;


        if ($barang) {
            $barang->status_barang = 'Ada';
            $barang->save();
        }

        // Hapus penghapusan
        $penghapusan->delete();

        ActivityLogHelper::log('Barang"' . $barang->kode_barang . '" Penghapusan Dibatalkan');

        return redirect()->route('penghapusan.index')->with('success', __('Data berhasil dihapus.'));
    }

    public function export(Request $request)
    {
        $query = PenghapusanBarang::with('barang');
        $accessResult = PermissionHelper::AnyCanAccessPenghapusan();
        if (!$accessResult['access']) {
            abort(403, 'Unauthorized action.');
        }

        if (!empty($accessResult['room'])) {
            $query->whereIn('kode_barang', function ($q) use ($accessResult) {
                $q->select('kode_barang')
                    ->from('barang')
                    ->whereIn('ruang_id', function ($subQuery) use ($accessResult) {
                        $subQuery->select('ruang_id')->from('ruang')->whereIn('nama_ruang', $accessResult['room']);
                    });
            });

        }

        if ($request->filled('tanggal_penghapusan')) {
            $query->where('tanggal_penghapusan', $request->tanggal_penghapusan);
        }

        if ($request->filled('nilai_sisa_min') && $request->filled('nilai_sisa_max')) {
            $query->whereBetween('nilai_sisa', [
                $request->input('nilai_sisa_min'),
                $request->input('nilai_sisa_max')
            ]);
        } elseif ($request->filled('nilai_sisa_min')) {
            $query->where('nilai_sisa', '>=', $request->input('nilai_sisa_min'));
        } elseif ($request->filled('nilai_sisa_max')) {
            $query->where('nilai_sisa', '<=', $request->input('nilai_sisa_max'));
        }


        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'LIKE', "%$search%")
                    ->orWhere('alasan_penghapusan', 'LIKE', "%$search%")
                    ->orWhere('alasan_penghapusan', 'LIKE', "%$search%")
                    ->orWhere('nilai_sisa', 'LIKE', "%$search%");
            });
        }


        $data = $query->get();

        return Excel::download(new PenghapusanBarangExport($data), 'penghapusan_'.now()->format('d-m-Y').'.xlsx');
    }
}
