<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Peminjaman;
use App\Models\Pengadaan;
use App\Models\PenghapusanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = Pengguna::count();

        $widget = [
            'users' => $users,
            //...
        ];

        $jenisBarang = Barang::count();
        $totalBarang = Barang::sum('jumlah');
        $barangPinjam = Peminjaman::where('status_peminjaman', '=', 'Dipinjam' )
        ->count();
        $statusBarang = Barang::selectRaw('status_barang, COUNT(*) as jumlah')
            ->groupBy('status_barang')
            ->get();
        $katBarang = Barang::selectRaw('kategoribarang.nama_kategori as kat, COUNT(barang.kategori_barang_id) as jumlah')
            ->join('kategoribarang', 'barang.kategori_barang_id','=','kategoribarang.kategori_barang_id')
            ->groupBy('kat')
            ->orderBy('jumlah')
            ->get();
        $trenPeminjaman = Barang::selectRaw('MONTH(peminjamanbarang.tanggal_peminjaman) as bulan, COUNT(*) as total')
            ->join('peminjamanbarang', 'barang.kode_barang', '=', 'peminjamanbarang.kode_barang')
            ->groupBy('bulan')
            ->get();
        
        $trenPenghapusan = Barang::selectRaw('MONTH(penghapusanbarang.tanggal_penghapusan) as bulan, COUNT(*) as total')
            ->join('penghapusanbarang', 'barang.kode_barang', '=', 'penghapusanbarang.kode_barang')
            ->groupBy('bulan')
            ->get();

        $perolehan = Barang::selectRaw('perolehan_barang, COUNT(*) as jumlah')
            ->groupBy('perolehan_barang')
            ->get();

        
        $months = collect(range(1, 12))->map(function ($month) {
            return date('F', mktime(0, 0, 0, $month, 1)); 
        });

        $peminjamanData = $months->map(function ($monthName, $index) use ($trenPeminjaman) {
            $data = $trenPeminjaman->firstWhere('bulan', $index + 1); 
            return $data ? $data->total : 0; 
        }); 
        
        $penghapusanData = $months->map(function ($monthName, $index) use ($trenPenghapusan) {
            $data = $trenPenghapusan->firstWhere('bulan', $index + 1);
            return $data ? $data->total : 0;
        });
        

        $perolehanLabels = $perolehan->pluck('perolehan_barang');
        $perolehanData = $perolehan->pluck('jumlah');
        $statusLabels = $statusBarang->pluck('status_barang');
        $statusData = $statusBarang->pluck('jumlah');
        $katLabels = $katBarang->pluck('kat');
        $katCounts = $katBarang->pluck('jumlah');
        $penghapusanLabel = $months;

        return view('home', compact('widget', 'perolehanLabels', 'perolehanData', 'jenisBarang', 'totalBarang', 'barangPinjam', 'statusLabels','statusData', 'katLabels', 'katCounts', 'peminjamanData', 'penghapusanLabel', 'penghapusanData'));
    }
}
