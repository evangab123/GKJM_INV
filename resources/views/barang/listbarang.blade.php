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
    <div class="d-flex justify-content-between mb-3">
        <!-- Search Form -->
        <form class="d-none d-sm-inline-block form-inline" method="GET" action="{{ route('barang.index') }}">
            <div class="input-group">
                <input type="text" class="form-control bg-light border-1 small" placeholder="{{ __('Cari Barang...') }}"
                    aria-label="search" aria-describedby="basic-addon2" name="search" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                    </a>
                </div>
            </div>
        </form>

        <!-- Add New Item Button -->
        @if($hasCreate['buat'])
            <div>
                <a href="{{ route('barang.create') }}" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> {{ __('Tambah Barang!') }}
                </a>
            </div>
        @endif

    </div>

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
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td>{{ $bar['kode_barang'] }}</td>
                    <td>{{ $bar['merek_barang'] }}</td>
                    <td>{{ $bar->ruang->nama_ruang ?? __('N/A') }}</td>
                    <td>{{ $bar['status_barang'] }}</td>
                    @if ($hasAccess['access'] || $hasDelete['delete'])
                    <td style="width: 200px;">
                        <div class="d-flex">
                            <!-- Detail Button -->
                            @if ($hasAccess['access'])
                                <a href="{{ route('barang.show', $bar['kode_barang']) }}" class="btn btn-info">
                                    <i class="fas fa-info-circle"></i> {{ __('Detil') }}
                                </a>
                            @endif
                            <!-- Delete Button -->
                            @if ($hasDelete['delete'])
                                <form action="{{ route('barang.destroy', $bar['kode_barang']) }}" method="POST"
                                    class="ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('{{ __('Are you sure you want to delete this item?') }}')">
                                        <i class="fas fa-trash"></i> {{ __('Hapus!') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination and Info -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="show-info">
            {{ __('Melihat') }} {{ $barang->firstItem() }} {{ __('hingga') }} {{ $barang->lastItem() }} {{ __('dari total') }} {{ $barang->total() }} {{ __('Barang') }}
        </div>
        <div class="pagination">
            {{ $barang->links() }}
        </div>
    </div>

    <!-- End of Main Content -->
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
