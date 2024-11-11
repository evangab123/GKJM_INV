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
                    {{-- Search Form --}}
                    <form action="{{ route('barang.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Cari ...') }}"
                            value="{{ request('search') }}" style="max-width: 200px;" oninput="this.form.submit()">
                        <a href="#" class="btn btn-info mx-2" data-toggle="modal" data-target="#modalFilter">
                            <i class="fa-solid fa-filter"></i> {{ __('Filter') }}
                        </a>
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary mr-2">
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
                                                    text-muted @endif
                                            ">
                                        @if ($bar['status_barang'] == 'Dihapus')
                                            <i class="fas fa-trash" aria-hidden="true"></i> {{ $bar['status_barang'] }}
                                        @elseif ($bar['status_barang'] == 'Ada')
                                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                                            {{ $bar['status_barang'] }}
                                        @elseif ($bar['status_barang'] == 'Dipinjam')
                                            <i class="fas fa-hand-paper" aria-hidden="true"></i>
                                            {{ $bar['status_barang'] }}
                                            @if ($bar['jumlah'] > 0 )
                                                <span class="text-warning">Sebagian</span>
                                            @endif
                                        @elseif ($bar['status_barang'] == 'Dipakai')
                                            <i class="fas fa-user" aria-hidden="true"></i> {{ $bar['status_barang'] }}
                                            @if ($bar['jumlah'] > 0 )
                                                <span class="text-warning">Sebagian</span>
                                            @endif
                                        @elseif ($bar['status_barang'] == 'Diperbaiki')
                                            <i class="fas fa-wrench" aria-hidden="true"></i> {{ $bar['status_barang'] }}
                                            @if ($bar['jumlah'] > 0)
                                                <span class="text-warning">Sebagian</span>
                                            @endif
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

    <!-- Modal Filter -->
    <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilterLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilterLabel">{{ __('Filter Barang') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('barang.index') }}" method="GET" class="form-inline">
                        <!-- Filter Kondisi -->
                        <div class="form-group mb-3">
                            <label for="kondisi">{{ __('Kondisi Barang') }}</label>
                            <select name="kondisi" class="form-control">
                                <option value="">{{ __('Filter Kondisi') }}</option>
                                @foreach ($kondisi as $kon)
                                    <option value="{{ $kon->deskripsi_kondisi }}"
                                        {{ request('kondisi') == $kon->deskripsi_kondisi ? 'selected' : '' }}>
                                        {{ $kon->deskripsi_kondisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Kategori -->
                        <div class="form-group mb-3">
                            <label for="kategori">{{ __('Kategori Barang') }}</label>
                            <select name="kategori" class="form-control">
                                <option value="">{{ __('Filter Kategori') }}</option>
                                @foreach ($kategori as $kon)
                                    <option value="{{ $kon->nama_kategori }}"
                                        {{ request('kategori') == $kon->nama_kategori ? 'selected' : '' }}>
                                        {{ $kon->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Harga -->
                        <div class="form-group mb-3">
                            <label for="harga">{{ __('Harga Barang') }}</label>
                            <div class="d-flex">
                                <input type="number" name="harga_min" class="form-control"
                                    value="{{ request('harga_min') }}" placeholder="{{ __('Harga Min') }}"
                                    min="0">
                                <span class="mx-2">{{ __('s/d') }}</span>
                                <input type="number" name="harga_max" class="form-control"
                                    value="{{ request('harga_max') }}" placeholder="{{ __('Harga Max') }}"
                                    min="0">
                            </div>
                        </div>


                        <!-- Filter Ruang -->
                        <div class="form-group mb-3">
                            <label for="ruang">{{ __('Ruang Barang') }}</label>
                            <select name="ruang" class="form-control">
                                <option value="">{{ __('Filter Ruang') }}</option>
                                @foreach ($ruangs as $kon)
                                    <option value="{{ $kon->nama_ruang }}"
                                        {{ request('ruang') == $kon->nama_ruang ? 'selected' : '' }}>
                                        {{ $kon->nama_ruang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Tahun Perolehan -->
                        <div class="form-group mb-3">
                            <label for="tahun_perolehan">{{ __('Tahun Perolehan') }}</label>
                            <div class="d-flex">
                                <input type="number" name="tahun_perolehan_start" class="form-control"
                                    value="{{ request('tahun_perolehan_start') }}" placeholder="{{ __('Tahun Mulai') }}"
                                    min="1900" max="{{ date('Y') }}">
                                <span class="mx-2">{{ __('s/d') }}</span>
                                <input type="number" name="tahun_perolehan_end" class="form-control"
                                    value="{{ request('tahun_perolehan_end') }}" placeholder="{{ __('Tahun Selesai') }}"
                                    min="1900" max="{{ date('Y') }}">
                            </div>
                        </div>


                        <!-- Filter Status -->
                        <div class="form-group mb-3">
                            <label for="status">{{ __('Status Barang') }}</label>
                            <select name="status" class="form-control">
                                <option value="">{{ __('Filter Status') }}</option>
                                <option value="Ada" {{ request('status') == 'Ada' ? 'selected' : '' }}>
                                    {{ __('Ada') }}
                                </option>
                                <option value="Diperbaiki" {{ request('status') == 'Diperbaiki' ? 'selected' : '' }}>
                                    {{ __('Diperbaiki') }}
                                </option>
                                <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>
                                    {{ __('Dipinjam') }}
                                </option>
                                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>
                                    {{ __('Ditolak') }}
                                </option>
                                <option value="Dihapus" {{ request('status') == 'Dihapus' ? 'selected' : '' }}>
                                    {{ __('Dihapus') }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="perolehan">{{ __('Perolehan Barang') }}</label>
                            <select name="perolehan" class="form-control">
                                <option value="">{{ __('Filter Perolehan') }}</option>
                                <option value="Pembelian" {{ request('perolehan') == 'Pembelian' ? 'selected' : '' }}>
                                    {{ __('Pembelian') }}
                                </option>
                                <option value="Pembuatan" {{ request('perolehan') == 'Pembuatan' ? 'selected' : '' }}>
                                    {{ __('Pembuatan') }}
                                </option>
                                <option value="Persembahan" {{ request('perolehan') == 'Persembahan' ? 'selected' : '' }}>
                                    {{ __('Persembahan') }}
                                </option>
                            </select>
                        </div>

                        <!-- Filter Jumlah -->
                        <div class="form-group mb-3">
                            <label for="jumlah">{{ __('Jumlah') }}</label>
                            <div class="d-flex">
                                <input type="number" name="jumlah_min" class="form-control"
                                    value="{{ request('jumlah_min') }}" placeholder="{{ __('Min Jumlah') }}">
                                <input type="number" name="jumlah_max" class="form-control ml-2"
                                    value="{{ request('jumlah_max') }}" placeholder="{{ __('Max Jumlah') }}">
                            </div>
                        </div>

                        <!-- Button Filter -->
                        <button type="submit" class="btn btn-primary">{{ __('Terapkan Filter') }}</button>
                    </form>
                </div>
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
    @if (session('error'))
        <div class="alert alert-danger border-left-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

@endpush
