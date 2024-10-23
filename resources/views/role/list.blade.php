@extends('layouts.admin')
@section('title', 'Daftar Roles | Inventaris GKJM')

@section('main-content')

    <div class="container-fluid">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header pt-3 d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    {{-- Search Form --}}
                    <form action="{{ route('role.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Cari ...') }}"
                            value="{{ request('search') }}" style="max-width: 200px;">
                        <button type="submit" class="btn btn-primary ml-2">{{ __('Cari') }}</button>
                        <a href="{{ route('role.index') }}" class="btn btn-secondary ml-2">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                        </a>
                    </form>
                </div>
                <a href="{{ route('role.create') }}" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> {{ __('Buat Role!') }}
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Hak</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Roles as $Role)
                                <tr>
                                    <td scope="row">{{ ($Roles->currentPage() - 1) * $Roles->perPage() + $loop->iteration }}</td>
                                    <td>{{ $Role->name }}</td>
                                    <td>
                                        @if ($Role->permissions->count())
                                            <ul>
                                                @foreach ($Role->permissions as $permission)
                                                    <li>{{ $permission->name }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">Tidak ada hak</span>
                                        @endif
                                    </td>
                                    <td style="width: 200px">
                                        <div class="d-flex">
                                            <a href="{{ route('role.edit', $Role->id) }}" title="Edit"
                                                class="btn  btn-warning mr-2">
                                                <i class="fa-solid fa-pen-to-square"></i> {{ __(' Edit') }}
                                            </a>
                                            <form action="{{ route('role.destroy', $Role->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn  btn-danger" title="Hapus"
                                                    onclick="return confirm('Anda yakin ingin menghapus hak ini dari role tersebut?')">
                                                    <i class="fas fa-trash"></i> {{ __(' Hapus!') }}
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
                {{ __('Melihat') }} {{ $Roles->firstItem() }} {{ __('hingga') }} {{ $Roles->lastItem() }}
                {{ __('dari total') }} {{ $Roles->total() }} {{ __('Roles') }}
            </div>
            <div class="pagination">
                {{ $Roles->links() }}
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
