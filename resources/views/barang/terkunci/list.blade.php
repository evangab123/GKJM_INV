@extends('layouts.admin')
@section('title', __('Daftar Barang Terkunci | Inventaris GKJM'))

@section('main-content')

    <div class="container-fluid">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header pt-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Daftar Barang Terkunci') }}</h6>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">
                    <i class="fa-solid fa-plus"></i> {{ __('Tambah Barang Terkunci!') }}
                </button>
            </div>

            <div class="card-body">
                <!-- Modal -->
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
                                    <div class="form-group">
                                        <label for="kode_barang">{{ __('Kode Barang') }}</label>
                                        <select class="form-control" id="kode_barang" name="kode_barang" required>
                                            <option value="" disabled selected>{{ __('Pilih Barang...') }}</option>
                                            @foreach ($barangs as $barang)
                                                @if (!in_array($barang->kode_barang, $kodeBarangTerkunci))
                                                    <option value="{{ $barang->kode_barang }}">
                                                        {{ $barang->kode_barang . ' || ' . $barang->merek_barang }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
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

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Kode Barang') }}</th>
                                <th scope="col">{{ __('Alasan Terkunci') }}</th>
                                <th scope="col">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangTerkunci as $item)
                                <tr>
                                    <td scope="row">
                                        {{ ($barangTerkunci->currentPage() - 1) * $barangTerkunci->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $item->kode_barang ?? '-' }}</td>
                                    <td>{{ $item->alasan_terkunci ?? '-' }}</td>
                                    <td style="width: 200px;">
                                        <form action="{{ route('terkunci.destroy', $item->kode_barang) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('{{ __('Apakah Anda yakin ingin menghapus barang terkunci ini?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i> {{ __(' Hapus!') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{ $barangTerkunci->links() }}
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
