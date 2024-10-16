@extends('layouts.admin')

@section('title', 'Daftar Barang | Inventaris GKJM')

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
                <input type="text" class="form-control bg-light border-1 small" placeholder="Cari Barang..."
                    aria-label="search" aria-describedby="basic-addon2" name="search" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrows-rotate"></i> Refresh
                    </a>
                </div>
            </div>
        </form>

        <!-- Add New Item Button -->
        @if ($hasCreate['buat'])
            <div>
                <a href="{{ route('barang.create') }}" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> Tambah Barang!
                </a>
            </div>
        @endif

    </div>

    <!-- Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Merek</th>
                <th>Ruang</th>
                <th>Status</th>
                @if ($hasAccess['access'] && $hasDelete['delete'])
                    <th>Aksi</th>
                @endif

            </tr>
        </thead>
        <tbody>
            @foreach ($barang as $bar)
                <tr>
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td>{{ $bar['kode_barang'] }}</td>
                    <td>{{ $bar['merek_barang'] }}</td>
                    <td>{{ $bar->ruang->nama_ruang ?? 'N/A' }}</td>
                    <td>{{ $bar['status_barang'] }}</td>
                    <td>
                        <div class="d-flex">
                            {{-- <!-- Edit Button -->
                            <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="modal"
                                data-target="#editModal{{ $bar['kode_barang'] }}">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button> --}}

                            <!-- Delete Button -->

                            @if ($hasDelete['delete'])
                                <form action="{{ route('barang.destroy', $bar['kode_barang']) }}" method="POST"
                                    class="mr-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            @endif

                            <!-- Detail Button -->
                            @if ($hasAccess['access'])
                                <a href="{{ route('barang.show', $bar['kode_barang']) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-info-circle"></i> _{{ 'Detail' }}
                                </a>
                            @endif

                        </div>
                    </td>
                </tr>

                {{-- <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $bar['kode_barang'] }}" tabindex="-1" role="dialog"
                    aria-labelledby="editModalLabel{{ $bar['kode_barang'] }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $bar['kode_barang'] }}">Edit Barang - {{ $bar['kode_barang'] }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('barang.update', $bar['kode_barang']) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <!-- Form fields -->
                                    <div class="form-group">
                                        <label for="jumlah">Jumlah/Stok</label>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah"
                                            value="{{ $bar['jumlah'] }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ $bar['keterangan'] }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="ruang_id">Ruang</label>
                                        <select class="form-control" name="ruang_id" id="ruang_id">
                                            <option value="">Pilih Ruang</option>
                                            @foreach ($ruang as $rua)
                                                <option value="{{ $rua->ruang_id }}" {{ $bar->ruang_id == $rua->ruang_id ? 'selected' : '' }}>
                                                    {{ $rua->nama_ruang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div> --}}
            @endforeach
        </tbody>
    </table>

    <!-- Pagination and Info -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="show-info">
            Melihat {{ $barang->firstItem() }} hingga {{ $barang->lastItem() }} dari total {{ $barang->total() }} Barang
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
