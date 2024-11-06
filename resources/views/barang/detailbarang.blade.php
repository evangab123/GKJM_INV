@extends('layouts.admin')

@section('title', 'Detail Barang | Inventaris GKJM')

@section('main-content')
    @php
        use App\Helpers\PermissionHelper;
        $hasCreate = PermissionHelper::AnyCanCreateBarang();
        $hasEdit = PermissionHelper::AnyCanEditBarang();
        $hasAccess = PermissionHelper::AnyHasAccesstoBarang();
        $hasDelete = PermissionHelper::AnyCanDeleteBarang();
    @endphp
    <!-- Main Content -->
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <div class="row mb-3">

        <!-- Button Kembali -->
        <div>
            <!-- Tombol Kembali -->
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> {{ __('Kembali') }}
            </a>

            <a href="{{ route('barang.index') }}" class="btn btn-success ml-2">
                <i class="fa-solid fa-house"></i> {{ __('Barang') }}
            </a>

            <!-- Button Edit/Close -->
            @if ($isEditing)
                <!-- Tombol Tutup (keluar dari mode edit) -->
                <a href="{{ route('barang.show', $barang->kode_barang) }}" class="btn btn-secondary ml-2">
                    <i class="fa-solid fa-times"></i> {{ __('Tutup') }}
                </a>
            @else
                <!-- Tombol Edit -->
                @if ($hasEdit['edit'])
                    <a href="{{ route('barang.edit', $barang->kode_barang) }}" class="btn btn-primary ml-2">
                        <i class="fa-solid fa-pen-to-square"></i> {{ __('Edit') }}
                    </a>
                @endif
            @endif

            <!-- Tombol Lihat Keterangan -->
            @if ($hasAccess['access'])
                <a href="{{ route('barang.keterangan', $barang->kode_barang) }}" class="btn btn-info ml-2">
                    {{ __('Lihat Keterangan') }}
                </a>
            @endif

            <!-- Tombol Hapus -->
            @if ($hasDelete['delete'])
                @if ($barang->status_barang === 'Ada')
                    <button type="button" class="btn btn-danger ml-2"
                        onclick="openDeleteModal('{{ $barang['kode_barang'] }}', '{{ $barang['merek_barang'] }}')">
                        <i class="fas fa-trash"></i> {{ __('Penghapusan Barang!') }}
                    </button>
                @else
                    <button type="button" class="btn btn-danger ml-2" disabled>
                        <i class="fas fa-trash"></i> {{ __('Penghapusan Barang!') }}
                    </button>
                @endif
            @endif


            <!-- Tombol Kunci atau Lepas Kunci -->
            @if (auth()->user()->hasRole('Super Admin||Majelis'))
                @if ($barangTerkunci)
                    <form action="{{ route('terkunci.destroy', $barangTerkunci->kode_barang) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger ml-2"
                            onclick="return confirm('Apakah Anda yakin ingin melepas kunci barang ini?')">
                            <i class='fas fa-lock'></i> {{ __('Lepas Kunci') }}
                        </button>
                    </form>
                @else
                    <!-- Tombol Kunci Barang -->
                    <button type="button" class="btn btn-warning ml-2" data-toggle="modal" data-target="#addModal">
                        <i class="fas fa-lock-open"></i> {{ __('Kunci Barang') }}
                    </button>
                @endif
            @endif
        </div>

    </div>

    <div class="row">
        <!-- Foto Barang -->
        <div class="col-md-4">
            <div class="card h-100">
                <!-- Foto Barang -->
                <img src="{{ asset('img/barang/' . $barang->path_gambar) }}" class="card-img-top img-fluid w-100 mb-3"
                    alt="Foto Barang" style="object-fit: cover; height: 300px;">


                <!-- QR Code -->
                {{-- <img src="{{ $qrCodeUrl }}" class="card-img-top img-fluid w-100" alt="QR Code"
                    style="object-fit: contain; height: 300px;"> --}}


                <div class="card-body text-center">
                    <h5 class="card-title">{{ $barang->nama_barang }}</h5>
                </div>
            </div>
        </div>


        <!-- Detail Barang -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Detail Barang') }}</h5>
                    <div class="card-body">
                        @if ($isEditing)
                            <!-- Form Edit -->
                            <form action="{{ route('barang.update_detail', $barang->kode_barang) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>{{ __('Kode') }}</th>
                                            <td><input type="text" class="form-control" name="kode_barang"
                                                    value="{{ $barang->kode_barang }}" readonly></td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Merek') }}</th>
                                            <td><input type="text" class="form-control" name="merek_barang"
                                                    value="{{ $barang->merek_barang }}"></td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Perolehan') }}</th>
                                            <td>
                                                <select class="form-control" id="perolehan_barang" name="perolehan_barang">
                                                    <option value="Persembahan"
                                                        {{ $barang['perolehan_barang'] == 'Persembahan' ? 'selected' : '' }}>
                                                        {{ __('Persembahan') }}
                                                    </option>

                                                    <option value="Pembuatan"
                                                        {{ $barang['perolehan_barang'] == 'Pembuatan' ? 'selected' : '' }}>
                                                        {{ __('Pembuatan') }}
                                                    </option>

                                                    <option value="Pembelian"
                                                        {{ $barang['perolehan_barang'] == 'Pembelian' ? 'selected' : '' }}>
                                                        {{ __('Pembelian') }}
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Harga') }}</th>
                                            <td>
                                                <input type="text" class="form-control" name="harga_pembelian"
                                                    value="{{ $barang->harga_pembelian }}"
                                                    onchange="calculateNilaiEkonomis()" oninput="formatRupiah(this)"
                                                    onblur="sanitizeInput(this)">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Tahun') }}</th>
                                            <td>
                                                <input type="text" class="form-control" name="tahun_pembelian"
                                                    value="{{ $barang->tahun_pembelian }}"
                                                    onchange="calculateNilaiEkonomis()">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Nilai Ekonomis') }}</th>
                                            <td>
                                                <input type="text" class="form-control" name="nilai_ekonomis_barang"
                                                    value="{{ $barang->nilai_ekonomis_barang }}" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Jumlah/Stok') }}</th>
                                            <td>
                                                <input type="text" class="form-control" name="jumlah"
                                                    value="{{ $barang->jumlah }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Keterangan') }}</th>
                                            <td>
                                                <input type="text" class="form-control" name="keterangan"
                                                    value="{{ $barang->keterangan }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Ruang') }}</th>
                                            <td>
                                                <select class="form-control" name="ruang_id">
                                                    @foreach ($ruang as $rua)
                                                        <option value="{{ $rua->ruang_id }}"
                                                            {{ $rua->ruang_id == $barang->ruang_id ? 'selected' : '' }}>
                                                            {{ $rua->nama_ruang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Kondisi') }}</th>
                                            <td>
                                                <select class="form-control" name="kondisi_id">
                                                    @foreach ($kondisi as $kon)
                                                        <option value="{{ $kon->kondisi_id }}"
                                                            {{ $kon->kondisi_id == $barang->kondisi_id ? 'selected' : '' }}>
                                                            {{ $kon->deskripsi_kondisi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Kategori') }}</th>
                                            <td>
                                                <select class="form-control" name="kategori_barang_id">
                                                    @foreach ($kategori as $kat)
                                                        <option value="{{ $kat->kategori_barang_id }}"
                                                            {{ $kat->kategori_barang_id == $barang->kategori_barang_id ? 'selected' : '' }}>
                                                            {{ $kat->nama_kategori }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>{{ __('Status') }}</th>
                                            <td>
                                                <select class="form-control" name="status_barang">
                                                    <option value="Ada"
                                                        {{ $barang->status_barang == 'Ada' ? 'selected' : '' }}>
                                                        {{ __('Ada') }}
                                                    </option>
                                                    {{-- <option value="Dipinjam"
                                                        {{ $barang->status_barang == 'Dipinjam' ? 'selected' : '' }}>
                                                        {{ __('Dipinjam') }}
                                                    </option> --}}
                                                    <option value="Diperbaiki"
                                                        {{ $barang->status_barang == 'Diperbaiki' ? 'selected' : '' }}>
                                                        {{ __('Diperbaiki') }}
                                                    </option>
                                                    {{-- <option value="Dihapus"
                                                        {{ $barang->status_barang == 'Dihapus' ? 'selected' : '' }}>
                                                        {{ __('Dihapus') }}
                                                    </option>
                                                    <option value="Dipakai"
                                                        {{ $barang->status_barang == 'Dipakai' ? 'selected' : '' }}>
                                                        {{ __('Dipakai') }} --}}
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('Foto Barang') }}</th>
                                            <td>
                                                <input type="file" class="form-control" name="path_gambar"
                                                    accept="image/*">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary">{{ __('Perbarui') }}</button>
                            </form>
                        @else
                            <!-- Detail Barang -->
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>{{ __('Kode') }}</th>
                                        <td>{{ $barang->kode_barang }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Merek') }}</th>
                                        <td>{{ $barang->merek_barang }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Perolehan') }}</th>
                                        <td>{{ $barang->perolehan_barang }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Harga') }}</th>
                                        <td>Rp {{ number_format($barang->harga_pembelian, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Tahun') }}</th>
                                        <td>{{ $barang->tahun_pembelian }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Nilai Ekonomis') }}</th>
                                        <td>Rp {{ number_format($barang->nilai_ekonomis_barang, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Jumlah/Stok') }}</th>
                                        <td>{{ $barang->jumlah }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Keterangan') }}</th>
                                        <td>{{ $barang->keterangan }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Ruang') }}</th>
                                        <td>{{ $barang->ruang->nama_ruang }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Kondisi') }}</th>
                                        <td>{{ $barang->kondisi->deskripsi_kondisi }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Kategori') }}</th>
                                        <td>{{ $barang->kategori->nama_kategori }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Status') }}</th>
                                        <td>{{ $barang->status_barang }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Hapus -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">{{ __('Konfirmasi Penghapusan') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ __('Apakah Anda yakin ingin melakukan penghapusan barang?') }}

                        <div class="mb-3">
                            <label for="alasan" class="form-label">{{ __('Alasan Penghapusan:') }}</label>
                            <input type="text" class="form-control" id="alasan" name="alasan" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_penghapusan" class="form-label">{{ __('Tanggal Penghapusan:') }}</label>
                            <input type="date" class="form-control" id="tanggal_penghapusan"
                                name="tanggal_penghapusan" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Batal') }}</button>
                        <form action="" method="POST" class="d-inline" id="deleteForm">
                            @csrf
                            <input type="hidden" name="alasan" id="hiddenAlasan">
                            <input type="hidden" name="kode_barang" id="hiddenKodeBarang">
                            <input type="hidden" name="tanggal_penghapusan" id="hiddenTanggalPenghapusan">
                            <button type="button" class="btn btn-danger"
                                onclick="submitDeleteForm()">{{ __('Hapuskan!') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Kunci -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('terkunci.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">{{ __('Tambah Barang Terkunci') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Input Hidden untuk Kode Barang -->
                            <input type="hidden" id="hidden_kode_barang" name="kode_barang"
                                value="{{ $barang->kode_barang }}">

                            <div class="form-group">
                                <label for="alasan_terkunci">{{ __('Alasan Terkunci') }}</label>
                                <textarea class="form-control" id="alasan_terkunci" name="alasan_terkunci" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ __('Tutup') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- End of Main Content -->
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

        @if (session('status'))
            <div class="alert alert-success border-left-success" role="alert">
                {{ session('status') }}
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
        function openDeleteModal(kode_barang, merek_barang) {
            $('#hiddenKodeBarang').val(kode_barang);
            $('#deleteModal').modal('show');
        }

        function submitDeleteForm() {
            const alasan = $('#alasan').val();
            const tanggalPenghapusan = $('#tanggal_penghapusan').val();
            const kodeBarang = $('#hiddenKodeBarang').val();

            $('#hiddenAlasan').val(alasan);
            $('#hiddenTanggalPenghapusan').val(tanggalPenghapusan);

            $('#deleteForm').attr('action', '{{ route('barang.penghapusanbarang', ':kode_barang') }}'.replace(
                ':kode_barang', kodeBarang));

            $('#deleteForm').submit();
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
