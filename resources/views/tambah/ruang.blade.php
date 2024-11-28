@extends('layouts.admin')
@section('title', 'Daftar Ruangan | Inventaris GKJM')

@section('main-content')
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
                    <form action="{{ route('ruang.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Cari ...') }}"
                            value="{{ request('search') }}" style="max-width: 200px;" oninput="this.form.submit()">
                        <a href="{{ route('ruang.index') }}" class="btn btn-secondary ml-2">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                        </a>
                    </form>
                </div>
                <button class="btn btn-success" data-toggle="modal" data-target="#createModal">
                    <i class="fa-solid fa-plus"></i> {{ __('Tambah Ruangan!') }}
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Nama Ruangan') }}</th>
                                <th scope="col">{{ __('Jumlah Barang') }}</th>
                                <th scope="col">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $ruangan)
                                <tr>
                                    <td scope="row">
                                        {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $ruangan->nama_ruang }}</td>
                                    <td>{{ $ruangan->barang_count }}</td>
                                    <td style="width:150px">
                                        <div class="d-flex">
                                            {{-- Edit Button --}}
                                            <button class="btn btn-primary mr-2" data-toggle="modal"
                                                data-target="#editModal{{ $ruangan->ruang_id }}">
                                                <i class="fas fa-edit"></i> {{ __('Edit') }}
                                            </button>

                                            {{-- Delete Form --}}
                                            <form action="{{ route('ruang.destroy', $ruangan->ruang_id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus ruangan ini? Data akan dihapus jika tidak direferensikan oleh barang lain.')">
                                                    <i class="fas fa-trash"></i> {{ __('Hapus') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editModal{{ $ruangan->ruang_id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">{{ __('Edit Ruangan') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('ruang.update', $ruangan->ruang_id) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="nama_ruang">{{ __('Nama Ruangan') }}</label>
                                                        <input type="text" class="form-control" id="nama_ruang"
                                                            name="nama_ruang" value="{{ $ruangan->nama_ruang }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">{{ __('Batal') }}</button>
                                                    <button type="submit"
                                                        class="btn btn-primary">{{ __('Simpan') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Pagination and Info -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="show-info">
                {{ __('Menampilkan') }} {{ $data->firstItem() }} {{ __('hingga') }} {{ $data->lastItem() }}
                {{ __('dari total') }} {{ $data->total() }} {{ __('ruangan') }}
            </div>
            <div class="pagination">
                {{ $data->links() }}
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">{{ __('Tambah Ruangan') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('ruang.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_ruang">{{ __('Nama Ruangan') }}</label>
                            <input type="text" class="form-control" id="nama_ruang" name="nama_ruang" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Batal') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Simpan') }}</button>
                    </div>
                </form>
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
