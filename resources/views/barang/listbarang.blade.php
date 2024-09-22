@extends('layouts.admin')

@section('title', 'List Barang | Inventaris GKJM')

@section('main-content')
    <!-- Page Heading -->
    {{-- <h1 class="h3 mb-4 text-gray-800">{{ __('List Barang') }}</h1> --}}

    <!-- Main Content goes here -->
    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="GET"
        action="{{ route('barang.index') }}">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-1 small mb-3" placeholder="Cari Barang..." aria-label="search"
                aria-describedby="basic-addon2" name="search" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-primary mb-3" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary mb-3">
                    <i class="fa-solid fa-arrows-rotate"></i> Refresh
                </a>

            </div>
        </div>
    </form>

    <div class="d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100">
        <a href="#" class="btn btn-success">Tambah Barang</a>
    </div>

    <table class="table table-bordered table-stripped">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Merek</th>
                <th>Perolehan</th>
                <th>Harga Beli</th>
                <th>Tahun Beli</th>
                <th>Nilai Ekonomis</th>
                <th>Jumlah/Stok</th>
                <th>Keterangan</th>
                <th>Ruang</th>
                <th>Kondisi</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barang as $bar)
                <tr>
                    <td>{{ $bar['kode_barang'] }}</td>
                    <td>{{ $bar['merek_barang'] }}</td>
                    <td>{{ $bar['perolehan_barang'] }}</td>
                    <td>Rp {{ number_format($bar['harga_pembelian'], 2, ',', '.') }}</td>
                    <td>{{ $bar['tahun_pembelian'] }}</td>
                    <td>Rp {{ number_format($bar['nilai_ekonomis_barang'], 2, ',', '.') }}</td>
                    <td>{{ $bar['jumlah'] }}</td>
                    <td>{{ $bar['keterangan'] }}</td>
                    <td>{{ $bar->ruang->nama_ruang ?? 'N/A' }}</td>
                    <td>{{ $bar->kondisi->deskripsi_kondisi ?? 'N/A' }}</td>
                    <td>{{ $bar->kategori->nama_kategori ?? 'N/A' }}</td>
                    <td>{{ $bar['status_barang'] }}</td>
                    <td>
                        <div class="d-flex">
                            <button type="button" class="btn btn-primary btn-custom" data-toggle="modal"
                                data-target="#editModal{{ $bar['kode_barang'] }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <button type="button" class="btn btn-danger btn-custom" data-dismiss="modal">
                                <i class="fas fa-trash"></i>
                            </button>

                            <a href="/barang/{{ $bar['kode_barang'] }}" class="btn btn-info btn-custom">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>

                    </td>
                </tr>
                <div class="modal fade" id="editModal{{ $bar['kode_barang'] }}" tabindex="-1" role="dialog"
                    aria-labelledby="editModalLabel{{ $bar['kode_barang'] }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $bar['kode_barang'] }}">Edit Barang <b>{{ $bar['kode_barang'] }}</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Edit form -->
                                <form action="/barang/{{ $bar['kode_barang'] }}" method="POST">
                                    @csrf
                                    @method('PUT')
                             
                                    {{-- <div class="form-group">
                                        <label for="merek_barang">Merek</label>
                                        <input type="text" class="form-control" id="merek_barang" name="merek_barang"
                                            value="{{ $bar['merek_barang'] }}" required>
                                    </div> --}}
                                    {{-- <div class="form-group">
                                        <label for="harga_pembelian">Harga Beli</label>
                                        <input type="text" class="form-control" id="harga_pembelian"
                                            name="harga_pembelian" value="{{ $bar['harga_pembelian'] }}" required>
                                    </div> --}}
                                    {{-- <div class="form-group">
                                        <label for="tahun_pembelian">Tahun Beli</label>
                                        <input type="text" class="form-control" id="tahun_pembelian"
                                            name="tahun_pembelian" value="{{ $bar['tahun_pembelian'] }}" required>
                                    </div> --}}
                                    {{-- <div class="form-group">
                                        <label for="perolehan_barang">Perolehan</label>
                                        <select class="form-control" id="perolehan_barang" name="perolehan_barang">
                                            <option value="Hibah"
                                                {{ $bar['perolehan_barang'] == 'Hibah' ? 'selected' : '' }}>Hibah
                                            </option>
                                            <option value="Pembelian"
                                                {{ $bar['perolehan_barang'] == 'Pembelian' ? 'selected' : '' }}>
                                                Pembelian
                                            </option>
                                        </select>
                                    </div> --}}
                                    {{-- <div class="form-group">
                                        <label for="nilai_ekonomis_barang">Nilai Ekonomis</label>
                                        <input type="text" class="form-control" id="nilai_ekonomis_barang"
                                            name="nilai_ekonomis_barang" value="{{ $bar['nilai_ekonomis_barang'] }}"
                                            required>
                                    </div> --}}
                                    <div class="form-group">
                                        <label for="jumlah">Jumlah/Stok</label>
                                        <input type="text" class="form-control" id="jumlah" name="jumlah"
                                            value="{{ $bar['jumlah'] }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <input type="textbox" class="form-control" id="keterangan" name="keterangan"
                                            value="{{ $bar['keterangan'] }}" required>
                                    </div>
                                    {{-- <div class="form-group">
                                        <label for="ruang_id">Ruang</label>
                                        <select class="form-control" id="ruang_id" name="ruang_id">
                                            @foreach ($ruang as $rua)
                                                <option value="{{ $rua->id}}"
                                                    {{ $rua->id == old('ruang_id', $bar['ruang_id'] ?? '') ? 'selected' : '' }}>
                                                    {{ $rua->nama_ruang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="form-group">
                                        <label for="ruang_id">Ruang</label>
                                        <select class="form-control @error('ruang_id') is-invalid @enderror" name="ruang_id" id="ruang_id">
                                            <option value="">Pilih Ruang</option>
                                            @foreach ($ruang as $rua)
                                                <option value="{{ $rua->ruang_id }}"
                                                    {{ (old('ruang_id') == $rua->ruang_id  || (isset($bar) && $bar->ruang_id == $rua->ruang_id )) ? 'selected' : '' }}>
                                                    {{ $rua->nama_ruang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="kondisi_id">Kondisi</label>
                                        <select class="form-control @error('kondisi_id') is-invalid @enderror" name="kondisi_id" id="kondisi_id">
                                            <option value="">Pilih Kondisi</option>
                                            @foreach ($kondisi as $kon)
                                                <option value="{{ $kon->kondisi_id }}"
                                                    {{ (old('kondisi_id') == $kon->kondisi_id  || (isset($bar) && $bar->kondisi_id == $kon->kondisi_id )) ? 'selected' : '' }}>
                                                    {{ $kon->deskripsi_kondisi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="kategori_barang_id">Kategori</label>
                                        <select class="form-control @error('kategori_barang_id') is-invalid @enderror" name="kategori_barang_id" id="kategori_barang_id">
                                            <option value="">Pilih kategori</option>
                                            @foreach ($kategori as $kat)
                                                <option value="{{ $kat->kategori_barang_id }}"
                                                    {{ (old('kategori_barang_id') ==$kat->kategori_barang_id  || (isset($bar) && $bar->kategori_barang_id == $kat->kategori_barang_id )) ? 'selected' : '' }}>
                                                    {{ $kat->nama_kategori}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="status_barang">Status</label>
                                        <select class="form-control" id="status_barang" name="status_barang">
                                            <option value="Ada"
                                                {{ $bar['status_barang'] == 'Ada' ? 'selected' : '' }}>Ada
                                            </option>
                                            <option value="Dipinjam"
                                                {{ $bar['status_barang'] == 'Dipinjam' ? 'selected' : '' }}>Dipinjam
                                            </option>
                                            <option value="Diperbaiki"
                                                {{ $bar['status_barang'] == 'Diperbaiki' ? 'selected' : '' }}>Diperbaiki
                                            </option>
                                            <option value="Dihapus"
                                                {{ $bar['status_barang'] == 'Dihapus' ? 'selected' : '' }}>Dihapus
                                            </option>
                                            <option value="Dipakai"
                                                {{ $bar['status_barang'] == 'Dipakai' ? 'selected' : '' }}>Dipakai
                                            </option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $barang->links() }}
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

    @if (session('warning'))
        <div class="alert alert-warning border-left-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
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
