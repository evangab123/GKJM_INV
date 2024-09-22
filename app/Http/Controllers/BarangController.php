<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetilKeteranganBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use App\Models\KategoriBarang;
use App\Models\KondisiBarang;
use App\Models\Ruang;


class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Factory|View
    {
        $query = Barang::with('ruang', 'kondisi', 'kategori');
        $kondisi = KondisiBarang::all();
        $Ruang = Ruang::all();
        $kategori = KategoriBarang::all();

        // Check if there's a search query
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'LIKE', "%$search%")
                    ->orWhere('merek_barang', 'LIKE', "%$search%")
                    ->orWhere('status_barang', 'LIKE', "%$search%")
                    ->orWhere('perolehan_barang', 'LIKE', "%$search%")
                    ->orWhere('tahun_pembelian', 'LIKE', "%$search%");
            });
        }

        $data = $query->paginate(7);

        return view('barang.listbarang', [
            'title' => 'Daftar Barang',
            'barang' => $data,
            'kondisi' => $kondisi,
            'kategori' => $kategori,
            'ruang' => $Ruang,
        ]);
    }

    public function edit($kode_barang)
    {
        $barang = Barang::where('kode_barang', $kode_barang)->firstOrFail();
        $kondisi = KondisiBarang::all();
        $Ruang = Ruang::all();
        $kategori = KategoriBarang::all();
        return view('barang.detailbarang', [
            'barang' => $barang,
            'isEditing' => true,
            'kondisi' => $kondisi,
            'kategori' => $kategori,
            'ruang' => $Ruang,
        ]);
    }

    public function show($kode_barang)
    {
        $barang = Barang::where('kode_barang', $kode_barang)->firstOrFail();

        // Generate QR Code URL
        // $qrCodeController = new QrCodeController();
        // $qrCodeUrl = $qrCodeController->generateQrCode($barang->kode_barang);

        return view('barang.detailbarang', [
            'title' => 'Detail Barang',
            'barang' => $barang,
            'isEditing' => false,
            // 'qrCodeUrl' => $qrCodeUrl,
        ]);
    }


    public function update(Request $request, $kode_barang)
    {
        // Ambil form_type untuk menentukan validasi yang akan dipakai
        $formType = $request->input('form_type');

        // Buat validasi yang berbeda berdasarkan form_type
        $data = $request->validate([
            'jumlah' => 'required|numeric',
            'status_barang' => 'required|in:Ada,Dipinjam,Dipakai,Dihapus,Diperbaiki',
            'keterangan' => 'required|string|max:255',
            'ruang_id' => 'required|exists:ruang,ruang_id',
            'kondisi_id' => 'required|exists:kondisibarang,kondisi_id',
            'kategori_barang_id' => 'required|exists:kategoribarang,kategori_barang_id',
        ]);

        // Update Barang dengan data yang sudah divalidasi
        $barang = Barang::where('kode_barang', $kode_barang)->firstOrFail();
        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }
    public function update_detail(Request $request, $kode_barang)
    {
        // Buat validasi yang berbeda berdasarkan form_type

        $data = $request->validate([
            'merek_barang' => 'required|string|max:255',
            'harga_pembelian' => 'required|numeric',
            'tahun_pembelian' => 'required|numeric',
            'nilai_ekonomis_barang' => 'required|numeric',
            'perolehan_barang' => 'required|in:Hibah,Pembelian',
            'jumlah' => 'required|numeric',
            'status_barang' => 'required|in:Ada,Dipinjam,Dipakai,Dihapus,Diperbaiki',
            'keterangan' => 'required|string|max:255',
            'ruang_id' => 'required|exists:ruang,ruang_id',
            'kondisi_id' => 'required|exists:kondisibarang,kondisi_id',
            'kategori_barang_id' => 'required|exists:kategoribarang,kategori_barang_id',
        ]);

        // Update Barang dengan data yang sudah divalidasi
        $barang = Barang::where('kode_barang', $kode_barang)->firstOrFail();
        $barang->update($data);

        return redirect()->route('barang.show', $kode_barang)->with('success', 'Barang updated successfully');
    }
    public function showKeterangan($kode_barang)
    {
        $barang = Barang::where('kode_barang', $kode_barang)->firstOrFail();
        $keteranganList = DetilKeteranganBarang::where('kode_barang', $kode_barang)->get();

        return view('barang.detilketerangan', [
            'barang' => $barang,
            'keteranganList' => $keteranganList,
            'isEditing' => false,
        ]);
    }
    public function editKeterangan($id)
    {
        $keteranganToEdit = DetilKeteranganBarang::findOrFail($id);
        $barang = Barang::where('kode_barang', $keteranganToEdit->kode_barang)->firstOrFail();

        return view('barang.detilketerangan', [
            'barang' => $barang,
            'keteranganList' => DetilKeteranganBarang::where('kode_barang', $barang->kode_barang)->get(),
            'keteranganToEdit' => $keteranganToEdit,
            'isEditing' => true,
        ]);
    }

    public function updateKeterangan(Request $request, $id)
    {
        $data = $request->validate([
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $keterangan = DetilKeteranganBarang::findOrFail($id);
        $keterangan->update($data);

        return redirect()->route('barang.keterangan', $keterangan->kode_barang)->with('success', 'Keterangan berhasil diperbarui');
    }
    public function storeKeterangan(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        // Create a new entry in the DetilKeteranganBarang table
        DetilKeteranganBarang::create([
            'kode_barang' => $id,
            'keterangan' => $request->input('keterangan'),
            'tanggal' => $request->input('tanggal'),
        ]);

        // Redirect back to the keterangan detail page for the specific barang
        return redirect()->route('barang.keterangan', $id)->with('message', 'Keterangan berhasil ditambahkan!');
    }
    public function create()
    {
        $ruang = Ruang::all();
        $kondisi = KondisiBarang::all();
        $kategori = KategoriBarang::all();

        return view('barang.create', compact('ruang', 'kondisi', 'kategori'));
    }
    public function store(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        // Create a new entry in the DetilKeteranganBarang table
        Barang::create([
            'kode_barang' => $id,
            'keterangan' => $request->input('keterangan'),
            'tanggal' => $request->input('tanggal'),
        ]);

        // Redirect back to the keterangan detail page for the specific barang
        return redirect()->route('barang.keterangan', $id)->with('message', 'Barang berhasil ditambahkan!');
    }
}
