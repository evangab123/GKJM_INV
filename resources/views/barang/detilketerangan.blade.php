@extends('layouts.admin')

@section('title', 'Detail Keterangan Barang | Inventaris GKJM')

@section('main-content')
    @php
        use App\Helpers\PermissionHelper;
        $hasCreate = PermissionHelper::AnyCanCreateBarang();
        $hasEdit = PermissionHelper::AnyCanEditBarang();
        $hasAccess = PermissionHelper::AnyHasAccesstoBarang();
        $hasDelete = PermissionHelper::AnyCanDeleteBarang();
    @endphp
    <div class="row mb-3">
        <div class="d-flex">
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> {{ __('Kembali') }}
            </a>
            <!-- Tombol Add Keterangan -->
            @if ($hasCreate['buat'])
                <button type="button" class="btn btn-success ml-2" data-toggle="modal" data-target="#addKeteranganModal">
                    <i class="fa-solid fa-plus"></i> {{ __('Tambah Keterangan Baru') }}
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
                    <h5 class="modal-title" id="addKeteranganModalLabel">{{ __('Tambah Keterangan Baru') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('keterangan.store', $barang->kode_barang) }}" method="POST">
                        @csrf
                        <input type="hidden" name="kode_barang" value="{{ $barang->kode_barang }}">
                        <div class="form-group">
                            <label for="keterangan">{{ __('Keterangan') }}</label>
                            <input id = 'keterangan' type="text" class="form-control" name="keterangan" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">{{ __('Tanggal') }}</label>
                            <input id = 'tanggal' type="date" class="form-control" name="tanggal" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Tambah Keterangan') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ __('Detail Keterangan untuk Barang ') . $barang->merek_barang }}: </h5>

                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Tanggal') }}</th>
                                <th scope="col">{{ __('Keterangan') }}</th>
                                @if ($hasDelete['delete'])
                                    <th scope="col">{{ __('Aksi') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($keteranganList as $keterangan)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>{{ $keterangan->tanggal }}</td>
                                    <td>{{ $keterangan->keterangan }}</td>
                                    @if ($hasDelete['delete'])
                                        <td style="width: 200px;">
                                            <form action="{{ route('keterangan.destroy', $keterangan->keterangan_id) }}"
                                                method="POST" style="display:inline;"
                                                onsubmit="return confirm('{{ __('Apakah Anda yakin ingin menghapus?') }}');">
                                                @csrf
                                                @method('DELETE')

                                                @php
                                                    $dateDiff = \Carbon\Carbon::parse($keterangan->created_at)->diffInDays(
                                                        now(),
                                                    );
                                                @endphp

                                                <button type="submit" class="btn btn-danger"
                                                    {{ $dateDiff > (int) env('DELETE_PERIOD_DAYS', 7) ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i> {{ __(' Hapus Keterangan!') }}
                                                </button>

                                            </form>
                                        </td>
                                    @endif
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
