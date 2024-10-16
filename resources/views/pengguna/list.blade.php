@extends('layouts.admin')
@section('title', __('Daftar Pengguna | Inventaris GKJM'))

@section('main-content')

    <div class="container-fluid">
        <a href="{{ route('pengguna.create') }}" class="btn btn-primary mb-3">{{ __('Buat Pengguna!') }}</a>

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Daftar Pengguna') }}</h6>
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
                                    <td scope="row">{{ $loop->iteration }}</td>
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
                                            <a href="{{ route('pengguna.edit', $user->pengguna_id) }}" title="{{ __('Edit') }}"
                                                class="btn btn-warning mr-2">
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

        {{ $pengguna->links() }}
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
