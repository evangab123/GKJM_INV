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
        $ruang = Ruang::all();
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

        // Adding the search query to pagination links
        $data = $query->paginate(7)->appends($request->only('search'));

        return view('barang.listbarang', [
            'title' => 'Daftar Barang',
            'barang' => $data,
            'kondisi' => $kondisi,
            'kategori' => $kategori,
            'ruang' => $ruang,
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
        // Validasi data
        $request->validate([
            'merek_barang' => 'required|string|max:255',
            'perolehan_barang' => 'required|string',
            'harga_pembelian' => 'required|numeric',
            'tahun_pembelian' => 'required|numeric',
            'nilai_ekonomis_barang' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string|max:255',
            'ruang_id' => 'required|exists:ruang,ruang_id',
            'kondisi_id' => 'required|exists:kondisibarang,kondisi_id',
            'kategori_barang_id' => 'required|exists:kategoribarang,kategori_barang_id',
            'status_barang' => 'required|string',
            'path_gambar' => 'nullable',
        ]);

        // Cari barang berdasarkan kode_barang
        $barang = Barang::where('kode_barang', $kode_barang)->firstOrFail();
        // Mengunggah dan menyimpan foto jika ada
        if ($request->hasFile('path_gambar')) {

            $file = $request->file('path_gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/barang'), $filename);

            // Hapus foto lama jika ada
            if ($barang->path_gambar && file_exists(public_path('img/barang/' . $barang->path_gambar))) {
                unlink(public_path('img/barang/' . $barang->path_gambar));
            }

            // Simpan nama foto baru ke model
            $barang->path_gambar = $filename;
        }


        // Update data barang dengan data baru
        $barang->fill($request->only([
            'merek_barang',
            'perolehan_barang',
            'harga_pembelian',
            'tahun_pembelian',
            'nilai_ekonomis_barang',
            'jumlah',
            'keterangan',
            'ruang_id',
            'kondisi_id',
            'kategori_barang_id',
            'status_barang',
            'path_gambar',
        ]));

        // Jika path_gambar diupdate, simpan perubahan
        $barang->save();

        return redirect()->route('barang.show', $barang->kode_barang)
            ->with('success', 'Detail barang berhasil diperbarui.');
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
            'kode_barang' => 'required|string|max:255',
            'merek_barang' => 'required|string|max:255',
            'perolehan_barang' => 'required|date',
            'harga_pembelian' => 'required|numeric',
            'tahun_pembelian' => 'required|numeric',
            'nilai_ekonomis_barang' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'ruang_id' => 'required|exists:ruang,id',
            'kondisi_id' => 'required|exists:kondisi_barang,id',
            'kategori_barang_id' => 'required|exists:kategori_barang,id',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle the uploaded file
        $pathFoto = null;
        if ($request->hasFile('foto_barang')) {
            $file = $request->file('foto_barang');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = 'img/barang/' . $filename;
            $file->move(public_path('img/barang'), $filename);
            $pathFoto = $filename;
        }


        // Create the new Barang entry
        Barang::create([
            'kode_barang' => $request->input('kode_barang'),
            'merek_barang' => $request->input('merek_barang'),
            'perolehan_barang' => $request->input('perolehan_barang'),
            'harga_pembelian' => $request->input('harga_pembelian'),
            'tahun_pembelian' => $request->input('tahun_pembelian'),
            'nilai_ekonomis_barang' => $request->input('nilai_ekonomis_barang'),
            'jumlah' => $request->input('jumlah'),
            'keterangan' => $request->input('keterangan'),
            'ruang_id' => $request->input('ruang_id'),
            'kondisi_id' => $request->input('kondisi_id'),
            'kategori_barang_id' => $request->input('kategori_barang_id'),
            'path_gambar' => $pathFoto,
        ]);

        // Redirect back to the keterangan detail page for the specific barang
        return redirect()->route('barang.keterangan', $id)->with('message', 'Barang berhasil ditambahkan!');
    }
}
