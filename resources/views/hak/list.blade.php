@extends('layouts.admin')
@section('title', 'Daftar Hak | Inventaris GKJM')

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
                    <form action="{{ route('hak.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Cari ...') }}"
                            value="{{ request('search') }}" style="max-width: 200px;">
                        <button type="submit" class="btn btn-primary ml-2">{{ __('Cari') }}</button>
                        <a href="{{ route('hak.index') }}" class="btn btn-secondary ml-2">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                        </a>
                    </form>
                </div>
                <a href="{{ route('hak.create') }}" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> {{ __('Buat Hak!') }}
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permisi)
                                <tr>
                                    <td scope="row">{{ ($permissions->currentPage() - 1) * $permissions->perPage() + $loop->iteration }}</td>
                                    <td>{{ $permisi->name }}</td>
                                    <td style="width:110px">
                                        <div class="d-flex">
                                            <form action="{{ route('hak.destroy', $permisi->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Are you sure to delete this?')">
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
                {{ __('Melihat') }} {{ $permissions->firstItem() }} {{ __('hingga') }} {{ $permissions->lastItem() }}
                {{ __('dari total') }} {{ $permissions->total() }} {{ __('Hak') }}
            </div>
            <div class="pagination">
                {{ $permissions->links() }}
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
