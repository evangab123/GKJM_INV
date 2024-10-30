@extends('layouts.admin')

@section('title', __('Daftar Barang | Inventaris GKJM'))

@section('main-content')
    @php
        use App\Helpers\PermissionHelper;
        $hasCreate = PermissionHelper::AnyCanCreateBarang();
        $hasEdit = PermissionHelper::AnyCanEditBarang();
        $hasAccess = PermissionHelper::AnyHasAccesstoBarang();
        $hasDelete = PermissionHelper::AnyCanDeleteBarang();
    @endphp
    <!-- Main Content goes here -->
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    {{-- search --}}
                    <form action="{{ route('barang.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Cari Barang...') }}"
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary ml-2">{{ __('Cari') }}</button>
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary ml-2">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                        </a>
                    </form>
                </div>

                <!-- Add New Item Button di kanan -->
                @if ($hasCreate['buat'])
                    <a href="{{ route('barang.create') }}" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i> {{ __('Tambah Barang!') }}
                    </a>
                @endif
            </div>


            <div class="card-body">
                <div class="table-responsive">
                    <!-- Table -->
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Kode') }}</th>
                                <th scope="col">{{ __('Merek') }}</th>
                                <th scope="col">{{ __('Ruang') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                @if ($hasAccess['access'] || $hasDelete['delete'])
                                    <th scope="col">{{ __('Aksi') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $bar)
                                <tr>
                                    <td scope="row">
                                        {{ ($barang->currentPage() - 1) * $barang->perPage() + $loop->iteration }}</td>
                                    <td>{{ $bar['kode_barang'] }}</td>
                                    <td>{{ $bar['merek_barang'] }}</td>
                                    <td>{{ $bar->ruang->nama_ruang ?? __('N/A') }}</td>
                                    <td
                                        class="
                                            @if ($bar['status_barang'] == 'Dihapus') text-danger
                                            @elseif ($bar['status_barang'] == 'Ada')
                                                text-success
                                            @elseif ($bar['status_barang'] == 'Dipinjam')
                                                text-warning
                                            @elseif ($bar['status_barang'] == 'Dipakai')
                                                text-info
                                            @elseif ($bar['status_barang'] == 'Diperbaiki')
                                                text-primary
                                            @else
                                                text-muted @endif">
                                        @if ($bar['status_barang'] == 'Dihapus')
                                            <i class="fas fa-trash" aria-hidden="true"></i> {{ $bar['status_barang'] }}
                                        @elseif ($bar['status_barang'] == 'Ada')
                                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                                            {{ $bar['status_barang'] }}
                                        @elseif ($bar['status_barang'] == 'Dipinjam')
                                            <i class="fas fa-user" aria-hidden="true"></i> {{ $bar['status_barang'] }}
                                        @elseif ($bar['status_barang'] == 'Dipakai')
                                            <i class="fas fa-cog" aria-hidden="true"></i> {{ $bar['status_barang'] }}
                                        @elseif ($bar['status_barang'] == 'Diperbaiki')
                                            <i class="fas fa-wrench" aria-hidden="true"></i> {{ $bar['status_barang'] }}
                                        @endif
                                    </td>
                                    @if ($hasAccess['access'] || $hasDelete['delete'])
                                        <td style="width: 200px;">
                                            <div class="d-flex">
                                                <!-- Detail Button -->
                                                @if ($hasAccess['access'])
                                                    <a href="{{ route('barang.show', $bar['kode_barang']) }}"
                                                        class="btn btn-info">
                                                        <i class="fas fa-info-circle"></i> {{ __('Detil') }}
                                                    </a>
                                                @endif
                                                <!-- Tombol Hapus -->
                                                @if ($hasDelete['delete'])
                                                    @if ($bar->status_barang === 'Ada')
                                                        <form action="{{ route('barang.destroy', $bar->kode_barang) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger ml-2"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                                                <i class="fas fa-trash"></i> {{ __('Hapus!') }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button type="button" class="btn btn-danger ml-2" disabled>
                                                            <i class="fas fa-trash"></i> {{ __('Hapus!') }}
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    @endif
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
                {{ __('Melihat') }} {{ $barang->firstItem() }} {{ __('hingga') }} {{ $barang->lastItem() }}
                {{ __('dari total') }} {{ $barang->total() }} {{ __('Barang') }}
            </div>
            <div class="pagination">
                {{ $barang->links() }}
            </div>
        </div>
        <!-- End of Main Content -->
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
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
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
