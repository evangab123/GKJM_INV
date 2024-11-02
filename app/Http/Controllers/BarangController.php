<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionHelper;
use App\Models\Barang;
use App\Models\BarangTerkunci;
use App\Models\DetilKeteranganBarang;
use App\Models\Pengadaan;
use App\Models\PenghapusanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use App\Models\KategoriBarang;
use App\Models\KondisiBarang;
use App\Models\Ruang;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ActivityLogHelper;


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
        $accessResult = PermissionHelper::AnyHasAccessToBarang();
        if (!$accessResult['access']) {
            abort(403, 'Unauthorized action.');
        }
        $ruangs=[];
        if (!empty($accessResult['room'])) {
            $query->whereIn('ruang_id', function ($q) use ($accessResult) {
                $q->select('ruang_id')->from('ruang')->whereIn('nama_ruang', $accessResult['room']);
            });
            $ruangs = Ruang::whereIn('nama_ruang', $accessResult['room'])->get();
        }else{
            $ruangs= Ruang::all();
        }
        $kondisi = KondisiBarang::all();
        $kategori = KategoriBarang::all();

        if ($request->filled('ruang')) {
            $query->whereHas('ruang', function ($q) use ($request) {
                $q->where('nama_ruang', 'like', '%' . $request->ruang . '%');
            });
        }

        if ($request->filled('kondisi')) {
            $query->whereHas('kondisi', function ($q) use ($request) {
                $q->where('deskripsi_kondisi', 'like', '%' . $request->kondisi . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('nama_kategori', 'like', '%' . $request->kategori . '%');
            });
        }

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

        $data = $query->paginate(7)->appends($request->only('search'))->appends($request->only('ketegori'))->appends($request->only('kondisi'))->appends($request->only('ruang'));

        return view('barang.listbarang', [
            'barang' => $data,
            'kondisi' => $kondisi,
            'kategori' => $kategori,
            'ruangs' => $ruangs,
        ]);
    }



    public function edit($kode_barang)
    {
        $accessResult = PermissionHelper::AnyCanEditBarang();
        if (!$accessResult['edit']) {
            abort(403, 'Unauthorized action.');
        }
        $barang = Barang::where('kode_barang', $kode_barang)->firstOrFail();
        $kondisi = KondisiBarang::all();
        $Ruang = Ruang::all();
        $kategori = KategoriBarang::all();
        $barangTerkunci = BarangTerkunci::where('kode_barang', $kode_barang)->first();


        return view('barang.detailbarang', [
            'barang' => $barang,
            'isEditing' => true,
            'kondisi' => $kondisi,
            'kategori' => $kategori,
            'ruang' => $Ruang,
            'barangTerkunci'=>$barangTerkunci,
        ]);
    }

    public function show($kode_barang)
    {
        $accessResult = PermissionHelper::AnyHasAccessToBarang();
        if (!$accessResult['access']) {
            abort(403, 'Unauthorized action.');
        }
        $barang = Barang::where('kode_barang', $kode_barang)->firstOrFail();
        $barangTerkunci = BarangTerkunci::where('kode_barang', $kode_barang)->first();
        // Generate QR Code URL
        // $qrCodeController = new QrCodeController();
        // $qrCodeUrl = $qrCodeController->generateQrCode($barang->kode_barang);

        return view('barang.detailbarang', [
            'title' => 'Detail Barang',
            'barang' => $barang,
            'isEditing' => false,
            'barangTerkunci'=>$barangTerkunci,
            // 'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    public function update_detail(Request $request, $kode_barang)
    {
        $accessResult = PermissionHelper::AnyCanEditBarang();
        if (!$accessResult['edit']) {
            abort(403, 'Unauthorized action.');
        }
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
            'path_gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $barang = Barang::where('kode_barang', $kode_barang)->firstOrFail();
        $prev = $barang->toArray();

        if ($request->hasFile('path_gambar')) {
            $file = $request->file('path_gambar');

            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('img/barang'), $filename);

            if ($barang->path_gambar && file_exists(public_path('img/barang/' . $barang->path_gambar))) {
                unlink(public_path('img/barang/' . $barang->path_gambar));
            }
            $barang->path_gambar = $filename;
        }

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
        ]));

        $barang->save();

        $new = $barang->toArray();
        ActivityLogHelper::log('Perbarui Barang "' . $kode_barang . '"', $new, $prev);
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
        $accessResult = PermissionHelper::AnyHasAccessToBarang();
        if (!$accessResult['access']) {
            abort(403, 'Unauthorized action.');
        }
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
        $accessResult = PermissionHelper::AnyCanCreateBarang();
        if (!$accessResult['buat']) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        DetilKeteranganBarang::create([
            'kode_barang' => $id,
            'keterangan' => $request->input('keterangan'),
            'tanggal' => $request->input('tanggal'),
        ]);

        ActivityLogHelper::log('Buat Keterangan "' . $id . '"');
        // Redirect back to the keterangan detail page for the specific barang
        return redirect()->route('barang.keterangan', $id)->with('message', 'Keterangan berhasil ditambahkan!');
    }
    public function create(Request $request)
    {
        $accessResult = PermissionHelper::AnyCanCreateBarang();

        if (!$accessResult['buat']) {
            abort(403, 'Unauthorized action.');
        }
        if (!empty($accessResult['room'])) {
            $ruang = Ruang::whereIn('nama_ruang', $accessResult['room'])->get();
            $kondisi = KondisiBarang::all();
            $kategori = KategoriBarang::all();
            return view('barang.create', compact('ruang', 'kondisi', 'kategori'));
        }else{
            $ruang = Ruang::all();
            $kondisi = KondisiBarang::all();
            $kategori = KategoriBarang::all();
            $fromApprove = $request->get('from') === 'approve';
            $idp = $request->get('idp');
            if($fromApprove){
                $pengadaan = Pengadaan::findOrFail($idp);
                return view('barang.create', compact('ruang', 'kondisi', 'kategori', 'fromApprove','idp','pengadaan'));
            }
            return view('barang.create', compact('ruang', 'kondisi', 'kategori'));
        }

    }
    public function store(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            // 'kode_barang' => 'required|string|unique:barang,kode_barang',
            'merek_barang' => 'required|string|max:255',
            'perolehan_barang' => 'required|string',
            'harga_pembelian' => 'required|numeric',
            'tahun_pembelian' => 'required|numeric|',
            'nilai_ekonomis_barang' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string|max:255',
            'ruang_id' => 'required|exists:ruang,ruang_id',
            'kondisi_id' => 'required|exists:kondisibarang,kondisi_id',
            'kategori_barang_id' => 'required|exists:kategoribarang,kategori_barang_id',
            // 'status_barang' => 'required|string',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        #handle kode barang
        // $ruang = Ruang::find($request->ruang_id);
        $kategori = KategoriBarang::find($request->kategori_barang_id);

        $tahunBeli = $request->tahun_pembelian;
        $kategoriNama = $kategori->nama_kategori;

        // Memisahkan nama kategori berdasarkan spasi
        $kataArray = explode(' ', $kategoriNama);

        // Mengambil huruf pertama dari setiap kata
        $singkatanKategori = '';
        foreach ($kataArray as $kata) {
            $singkatanKategori .= strtoupper(substr($kata, 0, 1));
        }

        // Jika hanya ada satu kata, gunakan 3 huruf pertama
        if (count($kataArray) == 1) {
            $singkatanKategori = strtoupper(substr($kategoriNama, 0, 3));
        }

        $lastBarang = Barang::where('kategori_barang_id', $request->kategori_barang_id)
            ->orderBy('id', 'desc')
            ->first();



        // Mengambil nomor urut terakhir untuk kategori yang sama
        $nomorUrut = $lastBarang ? intval(substr($lastBarang->kode_barang, -4)) + 1 : 1;
        $nomorUrutFormatted = str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

        // Membentuk kode barang berdasarkan format yang diinginkan
        $kodeBarang = "GKJM" . '-' . $tahunBeli . '-' . $singkatanKategori . '-' . $nomorUrutFormatted;


        // Handle the uploaded file
        $pathFoto = null;
        if ($request->hasFile('path_gambar')) {
            $file = $request->file('path_gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = 'img/barang/' . $filename;
            $file->move(public_path('img/barang'), $filename);
            $pathFoto = $filename;
        }


        // Create the new Barang entry
        Barang::create([
            'kode_barang' => $kodeBarang,
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
            'status_barang' => "Ada",
            'path_gambar' => $pathFoto,
        ]);

        if ($request->get('from') === 'approve') {
            $pengadaan = Pengadaan::findOrFail($request->input('idp'));
            $pengadaan->kode_barang = $kodeBarang;
            $pengadaan->save();
            ActivityLogHelper::log('Buat Barang "' . $kodeBarang . '"');
            return redirect()->route('pengadaan.index')->with('message', 'Barang berhasil disimpan dari persetujuan.');
        }

        ActivityLogHelper::log('Buat Barang "' . $kodeBarang . '"');

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function penghapusanbarang(Request $request,$id)
    {
        $barang = Barang::findOrFail($id);
        if ($barang->status_barang !== 'Ada') {
            return redirect()->route('barang.index')->with('warning', __('Barang tidak bisa dihapus karena statusnya bukan Ada.'));
        }
        $accessResult = PermissionHelper::AnyCanDeleteBarang();

        if (!$accessResult['delete']) {
            abort(403, 'Unauthorized action.');
        }

        $pb = PenghapusanBarang::create([
            'kode_barang' => $barang->kode_barang,
            'tanggal_penghapusan' => $request->input('tanggal_penghapusan'),
            'alasan_penghapusan' => $request->input('alasan'),
            'nilai_sisa' => $barang->nilai_ekonomis_barang,
        ]);

        $barang->status_barang = 'Dihapus';
        $barang->save();
        ActivityLogHelper::log('Hapus Barang "' . $barang->kode_barang . '" dengan id penghapusan "'.$pb->penghapusan_id.'"');

        return redirect()->route('barang.index')->with('success', 'Barang telah dihapus.');
    }

    public function destroy(Request $request,$id)
    {
        $barang = Barang::findOrFail($id);
        if ($barang->status_barang !== 'Ada') {
            return redirect()->route('barang.index')->with('warning', __('Barang tidak bisa dihapus karena statusnya bukan Ada.'));
        }
        $accessResult = PermissionHelper::AnyCanDeleteBarang();

        if (!$accessResult['delete']) {
            abort(403, 'Unauthorized action.');
        }

        $barang->delete();
        ActivityLogHelper::log('Hapus Database Barang "' . $barang->kode_barang . '"');

        return redirect()->route('barang.index')->with('success', 'Barang telah dihapus.');
    }

    public function delKeterangan($id)
    {
        $keterangan = DetilKeteranganBarang::findOrFail($id);

        $accessResult = PermissionHelper::AnyCanDeleteBarang();

        if (!$accessResult['delete']) {
            abort(403, 'Unauthorized action.');
        }

        $keterangan->delete();
        ActivityLogHelper::log('Hapus Keterangan "' . $id . '"');

        return redirect()->back()->with('success', 'Keterangan telah dihapus.');
    }
}
