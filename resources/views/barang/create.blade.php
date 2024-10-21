@extends('layouts.admin')

@section('title', 'Tambah Barang | Inventaris GKJM')

@section('main-content')
    <div class="row mb-3">
        <div class="d-flex">
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> {{ __('Kembali') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Tambah Barang') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="merek_barang">{{ __('Merek Barang') }}</label>
                            <input type="text" class="form-control" id="merek_barang" name="merek_barang">
                        </div>
                        <div class="form-group">
                            <label for="perolehan_barang">{{ __('Perolehan') }}</label>
                            <select class="form-control" id="perolehan_barang" name="perolehan_barang">
                                <option value="Persembahan">{{ __('Persembahan') }}</option>
                                <option value="Pembelian">{{ __('Pembelian') }}</option>
                                <option value="Pembuatan">{{ __('Pembuatan') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="harga_pembelian">{{ __('Harga Beli') }}</label>
                            <input type="number" class="form-control" id="harga_pembelian" name="harga_pembelian"
                                onchange="calculateNilaiEkonomis()">
                        </div>
                        <div class="form-group">
                            <label for="tahun_pembelian">{{ __('Tahun Beli') }}</label>
                            <input type="text" class="form-control" id="tahun_pembelian" name="tahun_pembelian"
                                onchange="calculateNilaiEkonomis()">
                        </div>

                        <div class="form-group">
                            <label for="nilai_ekonomis_barang">{{ __('Nilai Ekonomis') }}</label>
                            <input type="number" class="form-control" id="nilai_ekonomis_barang"
                                name="nilai_ekonomis_barang" readonly>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">{{ __('Jumlah/Stok') }}</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">{{ __('Keterangan') }}</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan">
                        </div>
                        <div class="form-group">
                            <label for="ruang_id">{{ __('Ruang') }}</label>
                            <select class="form-control" name="ruang_id" id="ruang_id">
                                @foreach ($ruang as $rua)
                                    <option value="{{ $rua->ruang_id }}">{{ $rua->nama_ruang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kondisi_id">{{ __('Kondisi') }}</label>
                            <select class="form-control" name="kondisi_id" id="kondisi_id">
                                @foreach ($kondisi as $kon)
                                    <option value="{{ $kon->kondisi_id }}">{{ $kon->deskripsi_kondisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kategori_barang_id">{{ __('Kategori') }}</label>
                            <select class="form-control" name="kategori_barang_id" id="kategori_barang_id">
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->kategori_barang_id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status_barang">{{ __('Status') }}</label>
                            <select class="form-control" name="status_barang" id="status_barang">
                                <option value="Ada">{{ __('Ada') }}</option>
                                <option value="Dipinjam">{{ __('Dipinjam') }}</option>
                                <option value="Diperbaiki">{{ __('Diperbaiki') }}</option>
                                <option value="Dihapus">{{ __('Dihapus') }}</option>
                                <option value="Dipakai">{{ __('Dipakai') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="foto_barang">{{ __('Foto Barang') }}</label>
                            <input type="file" class="form-control" name="path_gambar" accept="image/*" id="foto_barang">
                        </div>
                        <button type="submit" class="btn btn-success">{{ __('Tambah Barang') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('notif')
    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endpush

<script>
    function calculateNilaiEkonomis() {
        const hargaPembelianInput = document.getElementById('harga_pembelian');
        const tahunPembelianInput = document.getElementById('tahun_pembelian');
        const nilaiEkonomisInput = document.getElementById('nilai_ekonomis_barang');

        const hargaPembelian = parseFloat(hargaPembelianInput.value) || 0;
        const tahunPembelian = parseFloat(tahunPembelianInput.value) || new Date().getFullYear();

        const umurEkonomis = 10;
        const nilaiSisa = 100;

        const totalDepreciation = (hargaPembelian - nilaiSisa) / umurEkonomis;

        const currentYear = new Date().getFullYear();
        const yearsUsed = currentYear - tahunPembelian;

        let nilaiEkonomis = hargaPembelian - (totalDepreciation * yearsUsed);
        nilaiEkonomis = nilaiEkonomis >= 0 ? nilaiEkonomis : 0;

        nilaiEkonomisInput.value = nilaiEkonomis.toFixed(2);
    }
</script>
