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
                            value="{{ request('search') }}" style="max-width: 200px;" oninput="this.form.submit()">
                        <a href="#" class="btn btn-info mx-2" data-toggle="modal" data-target="#modalFilter">
                            <i class="fa-solid fa-filter"></i> {{ __('Filter') }}
                        </a>
                        <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary mr-2">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                        </a>

                    </form>
                    @if ($hasAccess['access'])
                        <form action="{{ route('pengadaan.export') }}" method="GET" id="exportForm">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="tanggal_pengajuan_start"
                                value="{{ request('tanggal_pengajuan_start') }}">
                            <input type="hidden" name="tanggal_pengajuan_end"
                                value="{{ request('tanggal_pengajuan_end') }}">

                            <input type="hidden" name="jumlah_min" value="{{ request('jumlah_min') }}">
                            <input type="hidden" name="jumlah_max" value="{{ request('jumlah_max') }}">

                            <input type="hidden" name="status" value="{{ request('status') }}">

                            <input type="hidden" name="kode_barang_true" value="{{ request('kode_barang_true') }}">
                            <input type="hidden" name="kode_barang_false" value="{{ request('kode_barang_false') }}">

                            <button type="button" class="btn btn-primary" onclick="confirmExport()">
                                <i class="fa-solid fa-file-excel"></i>
                            </button>
                        </form>
                    @endif
                </div>

                @if ($hasCreate['buat'])
                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modalPengadaan">
                        <i class="fa-solid fa-plus"></i> {{ __('Buat Pengadaan Barang!') }}
                    </a>
                @endif
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Merek Barang') }}</th>
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
                                    <td>{{ $item->merek_barang }}</td>
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
                                    <td>
                                        @if ($item->kode_barang)
                                            <a href="{{ route('barang.show', $item->kode_barang) }}">
                                                {{ $item->kode_barang }}
                                            </a>
                                        @else
                                            {{ __('Barang belum dibuat') }}
                                        @endif
                                    </td>

                                    <td>
                                        @if ($item->status_pengajuan == 'Diajukan')
                                            <span style="color: blue;">
                                                <i class="fas fa-paper-plane"></i> {{ __('Diajukan') }}
                                            </span>
                                        @elseif($item->status_pengajuan == 'Disetujui')
                                            <span style="color: green;">
                                                <i class="fas fa-check-circle"></i> {{ __('Disetujui') }}
                                            </span>
                                        @elseif($item->status_pengajuan == 'Ditolak')
                                            <span style="color: red;">
                                                <i class="fas fa-times-circle"></i> {{ __('Ditolak') }}
                                            </span>
                                        @endif
                                    </td>
                                    @if (
                                        $hasDelete['delete'] ||
                                            $hasEdit['edit'] ||
                                            auth()->user()->hasRole(['Super Admin', 'Majelis']))
                                        <td>
                                            <div class="d-flex flex-column" role="group" aria-label="Tombol Aksi"
                                                style="width: 100%; gap: 5px;">
                                                @if (auth()->user()->hasRole(['Super Admin', 'Majelis']))
                                                    @if (!($item->status_pengajuan == 'Disetujui'))
                                                        <!-- Tombol Setuju -->
                                                        <form
                                                            action="{{ route('pengadaan.approve', $item->pengadaan_id) }}"
                                                            method="POST" class="flex-fill" style="margin: 0;">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-success"
                                                                onclick="return confirm('{{ __('Apakah Anda yakin ingin menyetujui pengadaan ini?') }}')"
                                                                style="width: 100%; height: 40px;"
                                                                @if ($item->kode_barang !== null) disabled @endif>
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
                                                                style="width: 100%; height: 40px;"
                                                                @if ($item->kode_barang !== null) disabled @endif>
                                                                <i class="fas fa-times"></i> {{ __('Tolak') }}
                                                            </button>
                                                        </form>
                                                    @elseif ($item->status_pengajuan == 'Disetujui')
                                                        <!-- Tombol Buat Barang -->
                                                        <form
                                                            action="{{ route('pengadaan.buatbarang', $item->pengadaan_id) }}"
                                                            method="POST" class="flex-fill" style="margin: 0;">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-success"
                                                                style="width: 100%; height: 40px;"
                                                                @if ($item->kode_barang !== null || !auth()->user()->hasRole('Super Admin')) disabled @endif>
                                                                <i class="fas fa-plus"></i> {{ __('Barang') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                                @if ($hasEdit['edit'])
                                                    <!-- Tombol Edit -->
                                                    <button class="btn btn-primary flex-fill"
                                                        onclick="openEditModal({{ $item->pengadaan_id }}, '{{ $item->merek_barang }}', {{ $item->jumlah }}, '{{ $item->referensi }}', '{{ $item->keterangan }}')"
                                                        style="width: 100%; height: 40px;"
                                                        @if ($item->status_pengajuan == 'Disetujui') disabled @endif>
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
                                                            style="width: 100%; height: 40px;"
                                                            @if ($item->status_pengajuan == 'Disetujui') disabled @endif>
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
                            <label for="merek_barang">{{ __('Merek Barang') }} <span class="text-danger">*</span></label>
                            <input type="text" name="merek_barang" class="form-control" required
                                placeholder="Masukkan Merek/Nama Barang" id="merek_barang">
                        </div>
                        <div class="form-group">
                            <label for="jumlah">{{ __('Jumlah') }} <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" class="form-control" required min="1"
                                placeholder="Masukkan Jumlah Barang" id="jumlah">
                            <small class="form-text text-muted">
                                {{ __('Buat jumlah yang tepat agar dapat mudah di kerjakan oleh sistem, contoh: 1 gitar, 1 keyboard, 1 kotak (50pcs) gelas dan 1 komputer.') }}
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="referensi">{{ __('Referensi Barang') }} <span class="text-danger">*</span>
                            </label>
                            <input type="url" name="referensi" id="referensi" class="form-control" required
                                placeholder="Masukkan URL referensi">
                            <small class="form-text text-muted">
                                {{ __('Silakan masukkan link yang valid (misalnya, https://contoh.com)') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_pengajuan">{{ __('Tanggal Pengajuan') }} <span
                                    class="text-danger">*</span> </label>
                            <input type="date" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan"
                                required>
                            <small
                                class="form-text text-muted">{{ 'Jika anda memasukan pengadaan secara online tanpa ke Admin, masukan pada tanggal saat anda membuat pengadaan ' }}</small>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">{{ __('Keterangan') }} <span class="text-danger">*</span> </label>
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
                            <label for="edit_merek_barang">{{ __('Merek Barang') }}</label>
                            <input type="text" name="merek_barang" class="form-control" id="edit_merek_barang"
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

    <!-- Modal Filter -->
    <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilterLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilterLabel">{{ __('Filter Pengadaan') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pengadaan.index') }}" method="GET" class="form-inline" id="filterForm">
                        {{-- Tanggal Pengajuan Filter --}}
                        <div class="form-group mb-3">
                            <label for="tanggal_pengajuan_start">{{ __('Tanggal Pengajuan') }}</label>
                            <div class="d-flex">
                                <input type="date" name="tanggal_pengajuan_start" class="form-control"
                                    value="{{ request('tanggal_pengajuan_start') }}">
                                <span class="mx-2">{{ __('s/d') }}</span>
                                <input type="date" name="tanggal_pengajuan_end" class="form-control"
                                    value="{{ request('tanggal_pengajuan_end') }}">
                            </div>
                        </div>

                        {{-- Jumlah Filter --}}
                        <div class="form-group mb-3">
                            <label for="jumlah">{{ __('Jumlah ') }}</label>
                            <div class="d-flex">
                                <input type="number" name="jumlah_min" class="form-control"
                                    value="{{ request('jumlah_min') }}" placeholder="{{ __('Min Jumlah') }}">
                                <input type="number" name="jumlah_max" class="form-control ml-2"
                                    value="{{ request('jumlah_max') }}" placeholder="{{ __('Max Jumlah') }}">
                            </div>
                        </div>

                        {{-- Filter Status --}}
                        <div class="form-group mb-3">
                            <label for="status">{{ __('Status Pengajuan') }}</label>
                            <select name="status" class="form-control">
                                <option value="">{{ __('Filter Status') }}</option>
                                <option value="Diajukan" {{ request('status') == 'Diajukan' ? 'selected' : '' }}>
                                    {{ __('Diajukan') }}</option>
                                <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>
                                    {{ __('Disetujui') }}</option>
                                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>
                                    {{ __('Ditolak') }}</option>
                            </select>
                        </div>

                        {{-- Kode Barang Filter --}}
                        <div class="form-group mb-3">
                            <div class="d-flex">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="kode_barang_true"
                                        id="kode_barang_true" value="1"
                                        {{ request('kode_barang_true') == '1' ? 'checked' : '' }}>
                                    <small class="form-check-label"
                                        for="kode_barang_true">{{ __('Pengadaan Sudah dibuat') }}</small>
                                </div>
                                <div class="form-check ml-3">
                                    <input type="checkbox" class="form-check-input" name="kode_barang_false"
                                        id="kode_barang_false" value="1"
                                        {{ request('kode_barang_false') == '1' ? 'checked' : '' }}>
                                    <small class="form-check-label"
                                        for="kode_barang_false">{{ __('Pengadaan Belum dibuat') }}</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"
                        form="filterForm">{{ __('Terapkan Filter') }}</button>
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
        document.getElementById('edit_merek_barang').value = nama; // Isi merek_barang di form
        document.getElementById('edit_jumlah').value = jumlah; // Isi jumlah di form
        document.getElementById('edit_referensi').value = referensi; // Isi referensi di form
        document.getElementById('edit_keterangan').value = keterangan; // Isi keterangan di form

        // Menampilkan modal
        var modal = new bootstrap.Modal(document.getElementById('modalEditPengadaan'));
        modal.show();
    }
</script>

<script>
    function confirmExport() {
        var search = document.querySelector('input[name="search"]').value;
        var tanggalPengajuanStart = document.querySelector('input[name="tanggal_pengajuan_start"]').value;
        var tanggalPengajuanEnd = document.querySelector('input[name="tanggal_pengajuan_end"]').value;
        var jumlahMin = document.querySelector('input[name="jumlah_min"]').value;
        var jumlahMax = document.querySelector('input[name="jumlah_max"]').value;
        var status = document.querySelector('select[name="status"]').value;
        var kodeBarangTrue = document.querySelector('input[name="kode_barang_true"]').checked;
        var kodeBarangFalse = document.querySelector('input[name="kode_barang_false"]').checked;

        if (search || tanggalPengajuanStart || tanggalPengajuanEnd || jumlahMin || jumlahMax || status ||
            kodeBarangTrue || kodeBarangFalse) {
            var confirmation = confirm(
                "Apakah Anda yakin ingin mengekspor data? Data yang didownload adalah data hasil filter.");

            if (confirmation) {
                document.getElementById('exportForm').submit();
            }
        } else {
            document.getElementById('exportForm').submit();
        }
    }
</script>
