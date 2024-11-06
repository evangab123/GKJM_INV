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
                {{-- Search Form --}}
                <form action="{{ route('terkunci.index') }}" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('Cari ...') }}"
                        value="{{ request('search') }}" style="max-width: 200px;">
                    <button type="submit" class="btn btn-primary ml-2">{{ __('Cari') }}</button>
                    <a href="{{ route('terkunci.index') }}" class="btn btn-secondary ml-2">
                        <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                    </a>
                </form>
            </div>

            <div class="card-body">
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
                                    <td>
                                        <a href="{{ route('barang.show', $item->kode_barang) }}">
                                            {{ $item->kode_barang ?? '-' }}
                                        </a>
                                    </td>

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


        <!-- Pagination and Info -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="show-info">
                {{ __('Melihat') }} {{ $barangTerkunci->firstItem() }} {{ __('hingga') }} {{ $barangTerkunci->lastItem() }}
                {{ __('dari total') }} {{ $barangTerkunci->total() }} {{ __('Barang') }}
            </div>
            <div class="pagination">
                {{ $barangTerkunci->links() }}
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
