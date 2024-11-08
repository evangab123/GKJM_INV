@extends('layouts.admin')
@section('title', 'Daftar Roles | Inventaris GKJM')
@php
    function formatHakAkses($hak)
    {
        $hakList = explode('-', $hak);
        $hakFormatted = [];
        $index = 0;

        $deskripsiMap = [
            'lihat' => 'Melihat',
            'perbarui' => 'Memperbarui',
            'buat' => 'Membuat',
            'hapus' => 'Menghapus',
            'peminjam' => 'Peminjaman',
            'pengadaan' => 'Pengadaan',
            'r.' => 'Ruangan',
            'semua' => 'Semua',
        ];

        foreach ($hakList as $item) {
            if ($item === 'semua' && $index === 0) {
                $hakFormatted[] = 'Melihat, membuat, memperbarui, menghapus';
            } elseif ($item === 'semua' && $index === 1) {
                $hakFormatted[] = 'Pengadaan, Peminjaman, Barang, Penghapusan, dan Pemakaian';
            } elseif ($item === 'semua' && $index === 2) {
                $hakFormatted[] = 'Semua Ruangan';
            } else {
                $hakFormatted[] = $deskripsiMap[$item] ?? ucfirst($item);
            }
            $index += 1;
        }

        if (count($hakFormatted) > 1) {
            $lastElement = array_pop($hakFormatted);
            return implode(', ', $hakFormatted) . ' di ' . $lastElement;
        }
    }
@endphp


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
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Cari Role ...') }}"
                            value="{{ request('search') }}" style="max-width: 200px;" oninput="this.form.submit()">

                        <div class="form-group">
                            <select name="permission" class="form-control ml-2" style="max-width: 200px;" onchange="this.form.submit()">
                                <option value="">{{ __('Filter Hak') }}</option>
                                @foreach ($permission as $perm)
                                    <option value="{{ $perm->name }}"
                                        {{ request('permission') == $perm->name ? 'selected' : '' }}>
                                        {{ formatHakAkses($perm->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Nama') }}</th>
                                <th scope="col">{{ __('Hak') }}</th>
                                <th scope="col">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Roles as $Role)
                                <tr>
                                    <td scope="row">
                                        {{ ($Roles->currentPage() - 1) * $Roles->perPage() + $loop->iteration }}</td>
                                    <td>{{ $Role->name }}</td>
                                    <td>
                                        @if ($Role->permissions->count())
                                            <ul>
                                                @foreach ($Role->permissions as $permission)
                                                    <li>{{ formatHakAkses($permission->name) }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">{{ __('Tidak ada hak') }}</span>
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
