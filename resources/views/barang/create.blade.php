@extends('layouts.admin')

@section('title', 'Tambah Barang | Inventaris GKJM')

@section('main-content')
    <div class="row mb-3">
        <div class="d-flex">
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Tambah Barang</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="kode_barang">Kode Barang</label>
                            <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
                        </div>
                        <div class="form-group">
                            <label for="merek_barang">Merek Barang</label>
                            <input type="text" class="form-control" id="merek_barang" name="merek_barang" required>
                        </div>
                        <div class="form-group">
                            <label for="perolehan_barang">Perolehan</label>
                            <select class="form-control" id="perolehan_barang" name="perolehan_barang">
                                <option value="Hibah">Hibah</option>
                                <option value="Pembelian">Pembelian</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="harga_pembelian">Harga Beli</label>
                            <input type="number" class="form-control" id="harga_pembelian" name="harga_pembelian" required>
                        </div>
                        <div class="form-group">
                            <label for="tahun_pembelian">Tahun Beli</label>
                            <input type="text" class="form-control" id="tahun_pembelian" name="tahun_pembelian" required>
                        </div>
                        <div class="form-group">
                            <label for="nilai_ekonomis_barang">Nilai Ekonomis</label>
                            <input type="number" class="form-control" id="nilai_ekonomis_barang"
                                name="nilai_ekonomis_barang" required>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah/Stok</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan">
                        </div>
                        <div class="form-group">
                            <label for="ruang_id">Ruang</label>
                            <select class="form-control" name="ruang_id" id="ruang_id">
                                @foreach ($ruang as $rua)
                                    <option value="{{ $rua->ruang_id }}">{{ $rua->nama_ruang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kondisi_id">Kondisi</label>
                            <select class="form-control" name="kondisi_id" id="kondisi_id">
                                @foreach ($kondisi as $kon)
                                    <option value="{{ $kon->kondisi_id }}">{{ $kon->deskripsi_kondisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kategori_barang_id">Kategori</label>
                            <select class="form-control" name="kategori_barang_id" id="kategori_barang_id">
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->kategori_barang_id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="foto_barang">Foto Barang</label>
                            <input type="file" class="form-control" name="foto_barang" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-success">Tambah Barang</button>

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
