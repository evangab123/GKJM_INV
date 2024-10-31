@extends('layouts.admin')
@section('title', __('Daftar Pengguna | Inventaris GKJM'))

@section('main-content')

    <div class="container-fluid">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="card shadow">
            <div class="card-header pt-3 d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    {{-- Search Form --}}
                    <form action="{{ route('pengguna.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Cari ...') }}"
                            value="{{ request('search') }}" style="max-width: 200px;">
                        <select name="permission" class="form-control ml-2">
                            <option value="">{{ __('Filter Hak') }}</option>
                            @foreach ($permissions as $perm)
                                <option value="{{ $perm->name }}"
                                    {{ request('permission') == $perm->name ? 'selected' : '' }}>
                                    {{ $perm->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="roles" class="form-control ml-2">
                            <option value="">{{ __('Filter Roles') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ request('roles') == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary ml-2">{{ __('Cari') }}</button>
                        <a href="{{ route('pengguna.index') }}" class="btn btn-secondary ml-2">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                        </a>
                    </form>
                </div>
                <a href="{{ route('pengguna.create') }}" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> {{ __('Buat Pengguna!') }}</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Nama') }}</th>
                                <th scope="col">{{ __('Jabatan') }}</th>
                                <th scope="col">{{ __('Email') }}</th>
                                <th scope="col">{{ __('Role Pengguna') }}</th>
                                <th scope="col">{{ __('Hak yang dimiliki') }}</th>
                                <th scope="col">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengguna as $user)
                                <tr>
                                    <td scope="row">
                                        {{ ($pengguna->currentPage() - 1) * $pengguna->perPage() + $loop->iteration }}</td>
                                    <td>{{ $user->nama_pengguna }}</td>
                                    <td>{{ $user->jabatan }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->getRoleNames()->first() }}</td>
                                    <td>
                                        @if ($user->permissions->count())
                                            <ul>
                                                @foreach ($user->permissions as $permission)
                                                    <li>{{ $permission->name }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">{{ __('Tidak ada hak') }}</span>
                                        @endif
                                    </td>
                                    <td style="width:200px">
                                        <div class="d-flex">
                                            <a href="{{ route('pengguna.edit', $user->pengguna_id) }}"
                                                title="{{ __('Edit') }}" class="btn btn-warning mr-2">
                                                <i class="fa-solid fa-pen-to-square"></i> {{ __('Edit!') }}
                                            </a>
                                            <form action="{{ route('pengguna.destroy', $user->pengguna_id) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger" title="{{ __('Hapus') }}"
                                                    onclick="return confirm('{{ __('Are you sure to delete this?') }}')">
                                                    <i class="fas fa-trash"></i>{{ __('Hapus!') }}
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
                {{ __('Melihat') }} {{ $pengguna->firstItem() }} {{ __('hingga') }} {{ $pengguna->lastItem() }}
                {{ __('dari total') }} {{ $pengguna->total() }} {{ __('Pengguna') }}
            </div>
            <div class="pagination">
                {{ $pengguna->links() }}
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
