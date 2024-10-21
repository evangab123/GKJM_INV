@extends('layouts.admin')
@section('title', __('Daftar Pengadaan | Inventaris GKJM'))

@section('main-content')

    <div class="container-fluid">

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Daftar Pemakaian Barang') }}</h6>
                <form action="{{ route('pemakaian.index') }}" method="GET" class="form-inline mt-3">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('Cari...') }}" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary ml-2">{{ __('Cari') }}</button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Kode Barang') }}</th>
                                <th scope="col">{{ __('Nama Barang') }}</th>
                                <th scope="col">{{ __('Pengguna') }}</th>
                                <th scope="col">{{ __('Tanggal Pengajuan') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengadaan as $item)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_barang }}</td>
                                    <td>{{ $item->barang->nama_barang ?? __('Tidak tersedia') }}</td>
                                    <td>{{ $item->pengguna->nama ?? __('Tidak tersedia') }}</td>
                                    <td>{{ $item->tanggal_pengajuan }}</td>
                                    <td>{{ $item->status_pengajuan }}</td>
                                    <td style="width:120px">
                                        <div class="d-flex">
                                            <a href="{{ route('pengadaan.edit', $item->id) }}" class="btn btn-primary mr-2">
                                                <i class="fas fa-edit"></i> {{ __('Edit') }}
                                            </a>
                                            <form action="{{ route('pengadaan.destroy', $item->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('{{ __('Apakah Anda yakin ingin menghapus data ini?') }}')">
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

        {{ $pengadaan->links() }}
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
