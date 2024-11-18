@extends('layouts.admin')
@section('title', __('Daftar Pemakaian | Inventaris GKJM'))

@section('main-content')
    <!-- Modal Pemakaian -->
    <div class="modal fade" id="modalPemakaian" tabindex="-1" role="dialog" aria-labelledby="modalPemakaianLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPemakaianLabel">{{ __('Tambah Pemakaian Barang') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pemakaian.store') }}" method="POST">
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
                            <label for="tanggal_mulai">{{ __('Tanggal Mulai') }}</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_selesai">{{ __('Tanggal Selesai') }}</label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">{{ __('Keterangan') }}</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Tutup') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Simpan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Pemakaian -->

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
                    <form action="{{ route('pemakaian.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control mr-2 ml-2"
                            placeholder="{{ __('Cari ...') }}" value="{{ request('search') }}" style="max-width: 200px;"
                            oninput="this.form.submit()">
                        <!-- Filter Tanggal Mulai -->
                        <label for="tanggal_mulai">{{ __('Tanggal Mulai:') }}</label>
                        <input type="date" name="tanggal_mulai" class="form-control mr-2 ml-2"
                            value="{{ request('tanggal_mulai') }}" placeholder="{{ __('Tanggal Mulai') }}"
                            style="max-width: 150px;" onchange="this.form.submit()">
                        <!-- Filter Tanggal Selesai -->
                        <label for="tanggal_selesai">{{ __('Tanggal Selesai:') }}</label>
                        <input type="date" name="tanggal_selesai" class="form-control mr-2 ml-2"
                            value="{{ request('tanggal_selesai') }}" placeholder="{{ __('Tanggal Selesai') }}"
                            style="max-width: 150px;" onchange="this.form.submit()">

                        <!-- Refresh-->
                        <a href="{{ route('pemakaian.index') }}" class="btn btn-secondary ml-2 mr-2">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                        </a>
                    </form>
                </div>
                <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modalPemakaian">
                    <i class="fa-solid fa-plus"></i> {{ __('Buat pemakaian Barang!') }}
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
                                <th scope="col">{{ __('Jumlah') }}</th>
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
                                    <td>{{ $item->tanggal_mulai }}</td>
                                    <td>{{ $item->tanggal_selesai }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td style="width:120px">
                                        <div class="d-flex">
                                            {{-- <a href="{{ route('pemakaian.edit', $item->riwayat_id) }}"
                                                class="btn btn-primary mr-2">
                                                <i class="fas fa-edit"></i> {{ __('Edit') }}
                                            </a> --}}
                                            <form action="{{ route('pemakaian.destroy', $item->riwayat_id) }}"
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
                {{ __('dari total') }} {{ $data->total() }} {{ __('Pemakaian Barang') }}
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
