@extends('layouts.admin')
@section('title', __('Daftar Peminjaman | Inventaris GKJM'))

@section('main-content')
    <!-- Modal Peminjaman -->
    <div class="modal fade" id="modalPeminjaman" tabindex="-1" role="dialog" aria-labelledby="modalPeminjamanLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPeminjamanLabel">{{ __('Tambah Peminjaman Barang') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('peminjaman.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kode_barang">{{ __('Kode Barang') }}</label>
                            <select class="form-control" id="kode_barang" name="kode_barang" required>
                                <option value="">{{ __('Pilih Kode Barang') }}</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->kode_barang }}">{{ $item->kode_barang }} -
                                        {{ $item->merek_barang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_peminjaman">{{ __('Tanggal Peminjaman') }}</label>
                            <input type="date" class="form-control" id="tanggal_peminjaman" name="tanggal_peminjaman"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_pengembalian">{{ __('Tanggal Pengembalian') }}</label>
                            <input type="date" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">{{ __('Keterangan') }}</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        </div>

                        {{-- <div class="form-group">
                            <label for="status_peminjaman">{{ __('Status Peminjaman') }}</label>
                            <select class="form-control" id="status_peminjaman" name="status_peminjaman" required>
                                <option value="">{{ __('Pilih Status') }}</option>
                                <option value="Dipinjam">{{ __('Dipinjam') }}</option>
                                <option value="Dikembalikan">{{ __('Dikembalikan') }}</option>
                            </select>
                        </div> --}}

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Tutup') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Simpan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Peminjaman -->

    <div class="container-fluid">

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header pt-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    {{-- Search Form --}}
                    <form action="{{ route('peminjaman.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control mr-2 ml-2"
                            placeholder="{{ __('Cari ...') }}" value="{{ request('search') }}" style="max-width: 200px;"
                            oninput="this.form.submit()">
                        <!-- Filter Tanggal Peminjaman -->
                        <label for="tanggal_peminjaman">{{ __('Tanggal Peminjaman:') }}</label>
                        <input type="date" name="tanggal_peminjaman" class="form-control mr-2 ml-2"
                            value="{{ request('tanggal_peminjaman') }}" placeholder="{{ __('Tanggal Peminjaman') }}"
                            style="max-width: 150px;" onchange="this.form.submit()">
                        <!-- Filter Tanggal Selesai -->
                        <label for="tanggal_pengembalian">{{ __('Tanggal Pengembalian:') }}</label>
                        <input type="date" name="tanggal_pengembalian" class="form-control mr-2 ml-2"
                            value="{{ request('tanggal_pengembalian') }}" placeholder="{{ __('Tanggal Pengembalian') }}"
                            style="max-width: 150px;" onchange="this.form.submit()">
                        <!-- Filter Status -->
                        <label for="status">{{ __('Status Barang') }}</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">{{ __('Filter Status') }}</option>
                            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>
                                {{ __('Dipinjam') }}
                            </option>
                            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>
                                {{ __('Dikembalikan') }}
                            </option>
                        </select>
                        <!-- Refresh-->
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary ml-2 mr-2">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                        </a>
                    </form>
                </div>
                <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modalPeminjaman">
                    <i class="fa-solid fa-plus"></i> {{ __('Buat Peminjaman Barang!') }}
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Kode Barang') }}</th>
                                <th scope="col">{{ __('Merek Barang') }}</th>
                                <th scope="col">{{ __('Pengguna Akun') }}</th>
                                <th scope="col">{{ __('Tanggal Mulai') }}</th>
                                <th scope="col">{{ __('Tanggal Selesai') }}</th>
                                <th scope="col">{{ __('Keterangan') }}</th>
                                <th scope="col">{{ __('Status Peminjaman') }}</th>
                                <th scope="col">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td scope="row">
                                        {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        <a href="{{ route('barang.show', $item->kode_barang) }}">
                                            {{ $item->kode_barang ?? '-' }}
                                        </a>
                                    </td>
                                    <td>{{ $item->barang->merek_barang ?? 'Tidak tersedia' }}</td>
                                    <td>{{ $item->pengguna->nama_pengguna ?? 'Tidak tersedia' }}</td>
                                    <td>{{ $item->tanggal_peminjaman }}</td>
                                    <td>{{ $item->tanggal_pengembalian }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td
                                        class="
                                    @if ($item->barang['status_barang'] == 'Dipinjam') text-warning
                                    @elseif ($item->barang['status_barang'] == 'Dikembalikan')
                                        text-success
                                    @else
                                        text-muted @endif">
                                        @if ($item->status_peminjaman == 'Dipinjam')
                                            <i class="fas fa-hand-paper" style="color: #f39c12;" title="Dipinjam"></i>
                                            {{ __('Dipinjam') }}
                                        @elseif ($item->status_peminjaman == 'Dikembalikan')
                                            <i class="fas fa-undo" style="color: #28a745;" title="Dikembalikan"></i>
                                            {{ __('Dikembalikan') }}
                                        @else
                                            <i class="fas fa-question-circle" style="color: #6c757d;"
                                                title="Status Tidak Diketahui"></i> {{ __('Status Tidak Diketahui') }}
                                        @endif
                                    </td>

                                    <td style="width:120px">
                                        <div class="d-flex">
                                            <form action="{{ route('peminjaman.destroy', $item->peminjaman_id) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('{{ __('Are you sure to delete this record?') }}')">
                                                    <i class="fas fa-trash"></i> {{ __('Hapus') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Pagination and Info -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="show-info">
                {{ __('Melihat') }} {{ $data->firstItem() }} {{ __('hingga') }} {{ $data->lastItem() }}
                {{ __('dari total') }} {{ $data->total() }} {{ __('Peminjamanan Barang') }}
            </div>
            <div class="pagination">
                {{ $data->links() }}
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
