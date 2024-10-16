@extends('layouts.admin')

@section('title', 'Detail Keterangan Barang | Inventaris GKJM')

@section('main-content')
    @php
        use App\Helpers\PermissionHelper;
        $hasCreate = PermissionHelper::AnyCanCreateBarang();
        $hasEdit = PermissionHelper::AnyCanEditBarang();
        $hasDelete = PermissionHelper::AnyCanDeleteBarang();
    @endphp
    <div class="row mb-3">
        <div class="d-flex">
            <a href="{{ route('barang.show', $barang->kode_barang) }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <!-- Tombol Add Keterangan -->
            @if ($hasCreate['buat'])
                <button type="button" class="btn btn-success ml-2" data-toggle="modal" data-target="#addKeteranganModal">
                    <i class="fa-solid fa-plus"></i> Tambah Keterangan Baru
                </button>
            @endif

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addKeteranganModal" tabindex="-1" role="dialog" aria-labelledby="addKeteranganModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKeteranganModalLabel">Tambah Keterangan Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('keterangan.store', $barang->kode_barang) }}" method="POST">
                        @csrf
                        <input type="hidden" name="kode_barang" value="{{ $barang->kode_barang }}">
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Keterangan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detail Keterangan untuk Barang: {{ $barang->nama_barang }}</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                @if ($hasEdit['edit'] && $hasDelete['delete'])
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($keteranganList as $keterangan)
                                <tr>
                                    <td>{{ $keterangan->tanggal }}</td>
                                    <td>{{ $keterangan->keterangan }}</td>
                                    <td>
                                        @if ($hasEdit['edit'])
                                            <a href="{{ route('keterangan.edit', $keterangan->keterangan_id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                        @endif
                                        @if ($hasDelete['delete'])
                                            <form action="{{ route('keterangan.destroy', $keterangan->keterangan_id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
