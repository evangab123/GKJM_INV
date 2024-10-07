@extends('layouts.admin')

@section('title', 'Detail Barang | Inventaris GKJM')

@section('main-content')
    <!-- Main Content -->
    <div class="row mb-3">
        <!-- Button Kembali -->
        <div class="d-flex">
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <!-- Button Edit/Close -->
            @if ($isEditing)
                <!-- Tombol Close (keluar dari mode edit) -->
                <a href="{{ route('barang.show', $barang->kode_barang) }}" class="btn btn-secondary ml-2">
                    <i class="fa-solid fa-times"></i> Close
                </a>
            @else
                <!-- Tombol Edit -->
                @if (Auth::user()->hasRole('Super Admin'))
                    <a href="{{ route('barang.edit', $barang->kode_barang) }}" class="btn btn-primary ml-2">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                @endif
            @endif
            <a href="{{ route('barang.keterangan', $barang->kode_barang) }}" class="btn btn-info ml-2">Lihat Keterangan</a>

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
                    <h5 class="card-title">Detail Barang</h5>
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
                                            <th>Kode</th>
                                            <td><input type="text" class="form-control" name="kode_barang"
                                                    value="{{ $barang->kode_barang }}" readonly></td>
                                        </tr>
                                        <tr>
                                            <th>Merek</th>
                                            <td><input type="text" class="form-control" name="merek_barang"
                                                    value="{{ $barang->merek_barang }}"></td>
                                        </tr>
                                        <tr>
                                            <th>Perolehan</th>
                                            <td>
                                                <select class="form-control" id="perolehan_barang" name="perolehan_barang">
                                                    <option value="Hibah"
                                                        {{ $barang['perolehan_barang'] == 'Hibah' ? 'selected' : '' }}>
                                                        Hibah</option>
                                                    <option value="Pembelian"
                                                        {{ $barang['perolehan_barang'] == 'Pembelian' ? 'selected' : '' }}>
                                                        Pembelian
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Harga Beli</th>
                                            <td>
                                                <input type="text" class="form-control" name="harga_pembelian"
                                                    value="{{ $barang->harga_pembelian }}"
                                                    onchange="calculateNilaiEkonomis()">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tahun Beli</th>
                                            <td>
                                                <input type="text" class="form-control" name="tahun_pembelian"
                                                    value="{{ $barang->tahun_pembelian }}"
                                                    onchange="calculateNilaiEkonomis()">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Nilai Ekonomis</th>
                                            <td><input type="text" class="form-control" name="nilai_ekonomis_barang"
                                                    value="{{ $barang->nilai_ekonomis_barang }}" readonly></td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah/Stok</th>
                                            <td><input type="text" class="form-control" name="jumlah"
                                                    value="{{ $barang->jumlah }}"></td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td><input type="text" class="form-control" name="keterangan"
                                                    value="{{ $barang->keterangan }}"></td>
                                        </tr>
                                        <tr>
                                            <th>Ruang</th>
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
                                            <th>Kondisi</th>
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
                                            <th>Kategori</th>
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
                                            <th>Status</th>
                                            <td>
                                                <select class="form-control" name="status_barang">
                                                    <option value="Ada"
                                                        {{ $barang->status_barang == 'Ada' ? 'selected' : '' }}>Ada
                                                    </option>
                                                    <option value="Dipinjam"
                                                        {{ $barang->status_barang == 'Dipinjam' ? 'selected' : '' }}>
                                                        Dipinjam
                                                    </option>
                                                    <option value="Diperbaiki"
                                                        {{ $barang->status_barang == 'Diperbaiki' ? 'selected' : '' }}>
                                                        Diperbaiki
                                                    </option>
                                                    <option value="Dihapus"
                                                        {{ $barang->status_barang == 'Dihapus' ? 'selected' : '' }}>Dihapus
                                                    </option>
                                                    <option value="Dipakai"
                                                        {{ $barang->status_barang == 'Dipakai' ? 'selected' : '' }}>Dipakai
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Foto Barang</th>
                                            <td>
                                                <input type="file" class="form-control" name="path_gambar"
                                                    accept="image/*">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        @else
                            <!-- Detail Barang -->
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Kode</th>
                                        <td>{{ $barang->kode_barang }}</td>
                                    </tr>
                                    <tr>
                                        <th>Merek</th>
                                        <td>{{ $barang->merek_barang }}</td>
                                    </tr>
                                    <tr>
                                        <th>Perolehan</th>
                                        <td>{{ $barang->perolehan_barang }}</td>
                                    </tr>
                                    <tr>
                                        <th>Harga Beli</th>
                                        <td>Rp {{ number_format($barang->harga_pembelian, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tahun Beli</th>
                                        <td>{{ $barang->tahun_pembelian }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nilai Ekonomis</th>
                                        <td>Rp {{ number_format($barang->nilai_ekonomis_barang, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah/Stok</th>
                                        <td>{{ $barang->jumlah }}</td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td>{{ $barang->keterangan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ruang</th>
                                        <td>{{ $barang->ruang->nama_ruang }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kondisi</th>
                                        <td>{{ $barang->kondisi->deskripsi_kondisi }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kategori</th>
                                        <td>{{ $barang->kategori->nama_kategori }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $barang->status_barang }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
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
