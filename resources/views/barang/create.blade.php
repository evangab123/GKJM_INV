@extends('layouts.admin')

@section('title', 'Tambah Barang | Inventaris GKJM')

@section('main-content')
    <div class="row mb-3">
        <div class="d-flex">
            <a href="javascript:history.back()"class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> {{ __('Kembali') }}
            </a>
        </div>
    </div>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

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
                            <label for="merek_barang">{{ __('Merek Barang') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="merek_barang" name="merek_barang"
                            value="{{ old('harga_pembelian', $pengadaan->merek_barang ?? '') }}">
                        </div>
                        <!-- Hidden input untuk mengirimkan status fromApprove jika tersedia -->
                        @if (isset($fromApprove))
                            <input type="hidden" name="from" value="{{ $fromApprove ? 'approve' : '' }}">
                        @endif
                        <!-- Hidden input untuk mengirimkan ID pengadaan jika tersedia -->
                        @if (isset($idp))
                            <input type="hidden" name="idp" value="{{ $idp }}">
                        @endif

                        <div class="form-group">
                            <label for="perolehan_barang">{{ __('Perolehan') }} <span class="text-danger">*</span></label>
                            <select class="form-control" id="perolehan_barang" name="perolehan_barang">
                                <option value="Persembahan">{{ __('Persembahan') }}</option>
                                <option value="Pembelian">{{ __('Pembelian') }}</option>
                                <option value="Pembuatan">{{ __('Pembuatan') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="harga_pembelian">{{ __('Harga') }} <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="harga_pembelian" name="harga_pembelian"
                                onchange="calculateNilaiEkonomis()" oninput="formatRupiah(this)"
                                onblur="sanitizeInput(this)">
                        </div>
                        <div class="form-group">
                            <label for="tahun_pembelian">{{ __('Tahun') }} <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="tahun_pembelian" name="tahun_pembelian"
                                onchange="calculateNilaiEkonomis()">
                        </div>

                        <div class="form-group">
                            <label for="nilai_ekonomis_barang">{{ __('Nilai Ekonomis') }}</label>
                            <input type="number" class="form-control" id="nilai_ekonomis_barang"
                                name="nilai_ekonomis_barang" readonly>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">{{ __('Jumlah/Stok') }} <span class="text-danger">*</span> </label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah"
                            value="{{ old('harga_pembelian', $pengadaan->jumlah ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">{{ __('Keterangan') }}</label>
                            <input type="textarea" class="form-control" id="keterangan" name="keterangan"
                                value="{{ old('harga_pembelian', $pengadaan->keterangan ?? '') }}">

                        </div>
                        <div class="form-group">
                            <label for="ruang_id">{{ __('Ruang') }} <span class="text-danger">*</span></label>
                            <select class="form-control" name="ruang_id" id="ruang_id">
                                @foreach ($ruang as $rua)
                                    <option value="{{ $rua->ruang_id }}">{{ $rua->nama_ruang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kondisi_id">{{ __('Kondisi') }} <span class="text-danger">*</span></label>
                            <select class="form-control" name="kondisi_id" id="kondisi_id">
                                @foreach ($kondisi as $kon)
                                    <option value="{{ $kon->kondisi_id }}">{{ $kon->deskripsi_kondisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kategori_barang_id">{{ __('Kategori') }} <span class="text-danger">*</span></label>
                            <select class="form-control" name="kategori_barang_id" id="kategori_barang_id">
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->kategori_barang_id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="form-group">
                            <label for="status_barang">{{ __('Status') }}</label>
                            <select class="form-control" name="status_barang" id="status_barang">
                                <option value="Ada">{{ __('Ada') }}</option>
                                <option value="Dipinjam">{{ __('Dipinjam') }}</option>
                                <option value="Diperbaiki">{{ __('Diperbaiki') }}</option>
                                <option value="Dihapus">{{ __('Dihapus') }}</option>
                                <option value="Dipakai">{{ __('Dipakai') }}</option>
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label for="foto_barang">{{ __('Foto Barang') }}</label>
                            <input type="file" class="form-control" name="path_gambar" accept="image/*"
                                id="foto_barang">
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
            const hargaPembelianInput = document.querySelector('input[name="harga_pembelian"]');
            const tahunPembelianInput = document.querySelector('input[name="tahun_pembelian"]');
            const nilaiEkonomisInput = document.querySelector('input[name="nilai_ekonomis_barang"]');

            const hargaPembelianString = hargaPembelianInput.value.replace(/[^\d]/g, '');
            const hargaPembelian = parseFloat(hargaPembelianString) || 0;

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

<script>
    function formatRupiah(input) {
        let value = input.value.replace(/[^\d,]/g, '');
        let parts = value.split('.');

        if (parts[1] && parts[1] === '00') {
            value = parts[0];
        }

        let numberFormat = new Intl.NumberFormat('id-ID');
        let formattedValue = numberFormat.format(value.replace(/[^\d]/g, ''));

        input.value = formattedValue;
    }

    function sanitizeInput(input) {
        let value = input.value.replace(/[^\d]/g, '');

        input.value = value;
    }

    document.querySelector('form').addEventListener('submit', function(event) {
        var hargaInput = document.getElementById('harga_pembelian');

        var cleanValue = hargaInput.value.replace(/[^\d]/g, '');

        hargaInput.value = cleanValue;
    });
</script>

