@extends('layouts.admin')
@section('title', 'Daftar Pengadaan Barang | Inventaris GKJM')

@section('main-content')
    @php
        use App\Helpers\PermissionHelper;
        $hasCreate = PermissionHelper::AnyCanCreatePengadaan();
        $hasEdit = PermissionHelper::AnyCanEditPengadaan();
        $hasAccess = PermissionHelper::AnyCanAccessPengadaan();
        $hasDelete = PermissionHelper::AnyCanDeletePengadaan();
    @endphp
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
                    <form action="{{ route('pengadaan.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Cari ...') }}"
                            value="{{ request('search') }}" style="max-width: 200px;">
                        <button type="submit" class="btn btn-primary ml-2">{{ __('Cari') }}</button>
                        <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary ml-2">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                        </a>
                    </form>
                </div>
                <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modalPengadaan">
                    <i class="fa-solid fa-plus"></i> {{ __('Buat Pengadaan Barang!') }}
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Nama Barang') }}</th>
                                <th scope="col">{{ __('Jumlah') }}</th>
                                <th scope="col">{{ __('Referensi') }}</th>
                                <th scope="col">{{ __('Keterangan') }}</th>
                                <th scope="col">{{ __('Pengaju') }}</th>
                                <th scope="col">{{ __('Tanggal Pengajuan') }}</th>
                                <th scope="col">{{ __('Kode Barang') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                @if (
                                    $hasDelete['delete'] ||
                                        $hasEdit['edit'] ||
                                        auth()->user()->hasRole(['Super Admin', 'Majelis']))
                                    <th scope="col">{{ __('Aksi') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengadaan as $item)
                                <tr>
                                    <td scope="row">
                                        {{ ($pengadaan->currentPage() - 1) * $pengadaan->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td style="width:120px">
                                        <div class="d-flex">
                                            <a href="{{ $item->referensi }}" target="_blank" class="btn btn-link">
                                                {{ __('Lihat Referensi') }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td>{{ $item->pengguna->nama_pengguna }}</td>
                                    <td>{{ $item->tanggal_pengajuan }}</td>
                                    <td>{{ $item->kode_barang ?? 'Belum dibuat kode barang' }}</td>
                                    <td>
                                        @if ($item->status_pengajuan == 'Diajukan')
                                            <span style="color: blue;">
                                                <i class="fas fa-paper-plane"></i> Diajukan
                                            </span>
                                        @elseif($item->status_pengajuan == 'Disetujui')
                                            <span style="color: green;">
                                                <i class="fas fa-check-circle"></i> Disetujui
                                            </span>
                                        @elseif($item->status_pengajuan == 'Ditolak')
                                            <span style="color: red;">
                                                <i class="fas fa-times-circle"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    @if (
                                        $hasDelete['delete'] ||
                                            $hasEdit['edit'] ||
                                            auth()->user()->hasRole(['Super Admin', 'Majelis']))
                                        <td>
                                            <div class="btn-group d-flex" role="group" aria-label="Tombol Aksi"
                                                style="width: 100%;">
                                                @if (auth()->user()->hasRole(['Super Admin', 'Majelis']))
                                                    @if (!($item->status_pengajuan == 'Ditolak' || $item->status_pengajuan == 'Disetujui'))
                                                        <!-- Tombol Setuju -->
                                                        <form
                                                            action="{{ route('pengadaan.approve', $item->pengadaan_id) }}"
                                                            method="POST" class="flex-fill" style="margin: 0;">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-success"
                                                                onclick="return confirm('{{ __('Apakah Anda yakin ingin menyetujui pengadaan ini?') }}')"
                                                                style="width: 100%;">
                                                                <i class="fas fa-check"></i> {{ __('Setuju') }}
                                                            </button>
                                                        </form>
                                                        <!-- Tombol Tolak -->
                                                        <form action="{{ route('pengadaan.reject', $item->pengadaan_id) }}"
                                                            method="POST" class="flex-fill" style="margin: 0;">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-warning"
                                                                onclick="return confirm('{{ __('Apakah Anda yakin ingin menolak pengadaan ini?') }}')"
                                                                style="width: 100%;">
                                                                <i class="fas fa-times"></i> {{ __('Tolak') }}
                                                            </button>
                                                        </form>
                                                    @elseif ($item->status_pengajuan == 'Disetujui')
                                                        <form
                                                            action="{{ route('pengadaan.buatbarang', $item->pengadaan_id) }}"
                                                            method="POST" class="flex-fill" style="margin: 0;">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-success"
                                                                style="width: 100%;">
                                                                <i class="fas fa-plus"></i>{{ __('Barang') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                                @if ($hasEdit['edit'])
                                                    <!-- Tombol Edit -->
                                                    <button class="btn btn-primary flex-fill"
                                                        onclick="openEditModal({{ $item->pengadaan_id }}, '{{ $item->nama_barang }}', {{ $item->jumlah }}, '{{ $item->referensi }}', '{{ $item->keterangan }}')"
                                                        style="width: 100%;">
                                                        <i class="fas fa-edit"></i> {{ __('Edit') }}
                                                    </button>
                                                @endif
                                                @if ($hasDelete['delete'])
                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('pengadaan.destroy', $item->pengadaan_id) }}"
                                                        method="post" class="flex-fill" style="margin: 0;">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('{{ __('Apakah Anda yakin ingin menghapus data ini?') }}')"
                                                            style="width: 100%;">
                                                            <i class="fas fa-trash"></i> {{ __('Hapus') }}
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
                </div>
            </div>
        </div>
        <!-- Pagination and Info -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="show-info">
                {{ __('Melihat') }} {{ $pengadaan->firstItem() }} {{ __('hingga') }} {{ $pengadaan->lastItem() }}
                {{ __('dari total') }} {{ $pengadaan->total() }} {{ __('Pengadaan') }}
            </div>
            <div class="pagination">
                {{ $pengadaan->links() }}
            </div>
        </div>
    </div>
    <!-- Modal Pengadaan Barang -->
    <div class="modal fade" id="modalPengadaan" tabindex="-1" role="dialog" aria-labelledby="modalPengadaanLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPengadaanLabel">{{ __('Buat Pengadaan Barang') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form Pengadaan Barang -->
                    <form action="{{ route('pengadaan.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nama_barang">{{ __('Nama Barang') }}</label>
                            <input type="text" name="nama_barang" class="form-control" required
                                placeholder="Masukkan Nama Barang" id="nama_barang">
                        </div>
                        <div class="form-group">
                            <label for="jumlah">{{ __('Jumlah') }}</label>
                            <input type="number" name="jumlah" class="form-control" required min="1"
                                placeholder="Masukkan Jumlah Barang" id="jumlah">
                        </div>
                        <div class="form-group">
                            <label for="referensi">{{ __('Referensi Barang') }}</label>
                            <input type="url" name="referensi" id="referensi" class="form-control" required
                                placeholder="Masukkan URL referensi">
                            <small class="form-text text-muted">
                                {{ __('Silakan masukkan link yang valid (misalnya, https://contoh.com)') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">{{ __('Keterangan') }}</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" required
                                placeholder="Masukkan Keteragan barang" id="keterangan">
                            <small class="form-text text-muted">
                                {{ __(' Keterangan dari Barang (misalnya, Laptop Ram 12 GB, Windows 11)') }}
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Simpan Pengadaan') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit Pengadaan Barang -->
    <div class="modal fade" id="modalEditPengadaan" tabindex="-1" role="dialog"
        aria-labelledby="modalEditPengadaanLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditPengadaanLabel">{{ __('Edit Pengadaan Barang') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form Edit Pengadaan Barang -->
                    <form action="" method="POST" id="formEditPengadaan">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="edit_nama_barang">{{ __('Nama Barang') }}</label>
                            <input type="text" name="nama_barang" class="form-control" id="edit_nama_barang"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="edit_jumlah">{{ __('Jumlah') }}</label>
                            <input type="number" name="jumlah" class="form-control" id="edit_jumlah" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_referensi">{{ __('Referensi Barang') }}</label>
                            <input type="url" name="referensi" class="form-control" id="edit_referensi" required>
                            <small class="form-text text-muted">
                                {{ __('Silakan masukkan link yang valid (misalnya, https://contoh.com)') }}
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="edit_keterangan">{{ __('Keterangan') }}</label>
                            <input type="text" name="keterangan" class="form-control" id="edit_keterangan" required>
                            <small class="form-text text-muted">
                                {{ __(' Keterangan dari Barang (misalnya, Laptop Ram 12 GB, Windows 11)') }}
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Simpan Pengadaan') }}</button>
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

<script>
    function openEditModal(id, nama, jumlah, referensi, keterangan) {
        // Mengisi data ke dalam modal
        document.getElementById('modalEditPengadaanLabel').textContent = 'Edit Pengadaan Barang ID: ' + id;
        document.getElementById('formEditPengadaan').action = '/pengadaan/' + id; // Update action form
        document.getElementById('edit_nama_barang').value = nama; // Isi nama_barang di form
        document.getElementById('edit_jumlah').value = jumlah; // Isi jumlah di form
        document.getElementById('edit_referensi').value = referensi; // Isi referensi di form
        document.getElementById('edit_keterangan').value = keterangan; // Isi keterangan di form

        // Menampilkan modal
        var modal = new bootstrap.Modal(document.getElementById('modalEditPengadaan'));
        modal.show();
    }
</script>
