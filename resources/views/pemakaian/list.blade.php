@extends('layouts.admin')
@section('title', __('Daftar Pemakaian | Inventaris GKJM'))

@section('main-content')
    @php
        use App\Helpers\PermissionHelper;
        $hasCreate = PermissionHelper::AnyCanCreatePemakaian();
        $hasEdit = PermissionHelper::AnyCanEditPemakaian();
        $hasAccess = PermissionHelper::AnyCanAccessPemakaian();
        $hasDelete = PermissionHelper::AnyCanDeletePemakaian();
    @endphp
    <!-- Modal Pemakaian -->
    <div class="modal fade" id="modalPemakaian" tabindex="-1" role="dialog" aria-labelledby="modalPemakaianLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPemakaianLabel">{{ __('Tambah Pemakaian Barang') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pemakaian.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kode_barang">
                                {{ __('Kode Barang') }}
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="kode_barang" name="kode_barang" required>
                                <option value="">{{ __('Pilih Kode Barang') }}</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->kode_barang }}" data-jumlah="{{ $item->jumlah }}">
                                        {{ $item->kode_barang }} - {{ $item->merek_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_mulai">{{ __('Tanggal Mulai') }} <span class="text-danger">*</span> </label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                        </div>

                        {{-- <div class="form-group">
                            <label for="tanggal_selesai">{{ __('Tanggal Selesai') }} <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                        </div> --}}
                        <div class="form-group">
                            <label for="jumlah">
                                {{ __('Jumlah/Stok') }}
                                <span class="text-danger">*</span>
                            </label>
                            <small id="stok-info" class="text-muted"></small>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="0">
                        </div>

                        <div class="form-group">
                            <label for="keterangan">{{ __('Keterangan') }}</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Tutup') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('Simpan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Pemakaian -->

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
                    <form action="{{ route('pemakaian.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control mr-2 ml-2"
                            placeholder="{{ __('Cari ...') }}" value="{{ request('search') }}" style="max-width: 200px;"
                            oninput="this.form.submit()">
                        <!-- Filter Tanggal Mulai -->
                        <label for="tanggal_mulai">{{ __('Tanggal Mulai:') }}</label>
                        <input type="date" name="tanggal_mulai" class="form-control mr-2 ml-2"
                            value="{{ request('tanggal_mulai') }}" placeholder="{{ __('Tanggal Mulai') }}"
                            style="max-width: 150px;" onchange="this.form.submit()">
                        <!-- Filter Tanggal Selesai -->
                        <label for="tanggal_selesai">{{ __('Tanggal Selesai:') }}</label>
                        <input type="date" name="tanggal_selesai" class="form-control mr-2 ml-2"
                            value="{{ request('tanggal_selesai') }}" placeholder="{{ __('Tanggal Selesai') }}"
                            style="max-width: 150px;" onchange="this.form.submit()">

                        <!-- Refresh-->
                        <a href="{{ route('pemakaian.index') }}" class="btn btn-secondary ml-2 mr-2">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('Refresh') }}
                        </a>
                    </form>
                </div>
                @if ($hasCreate['create'])
                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modalPemakaian">
                        <i class="fa-solid fa-plus"></i> {{ __('Buat pemakaian Barang!') }}
                    </a>
                @endif
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Kode Barang') }}</th>
                                <th scope="col">{{ __('Merek Barang') }}</th>
                                <th scope="col">{{ __('Jumlah') }}</th>
                                <th scope="col">{{ __('Pengguna Akun') }}</th>
                                <th scope="col">{{ __('Tanggal Mulai') }}</th>
                                <th scope="col">{{ __('Tanggal Selesai') }}</th>
                                <th scope="col">{{ __('Keterangan') }}</th>
                                <th scope="col">{{ __('Status Pemakaian') }}</th>
                                <th scope="col">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td scope="row">
                                        {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        <a href="{{ route('barang.show', $item->kode_barang) }}">
                                            {{ $item->kode_barang ?? '-' }}
                                        </a>
                                    </td>
                                    <td>{{ $item->barang->merek_barang ?? 'Tidak tersedia' }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ $item->pengguna->nama_pengguna ?? 'Tidak tersedia' }}</td>
                                    <td>{{ $item->tanggal_mulai }}</td>
                                    <td>{{ $item->tanggal_selesai ?? 'Belum dikembalikan/Masih dipinjam' }}</td>
                                    <td>{{ $item->keterangan ?? 'Tidak ada Keterangan' }}</td>
                                    <td
                                        class="
                                    @if ($item->status_pemakaian == 'Dipakai') text-warning
                                    @elseif ($item->status_pemakaian == 'Dikembalikan') text-success
                                    @else text-muted @endif">
                                        @if ($item->status_pemakaian == 'Dipakai')
                                            <i class="fas fa-hand-paper" style="color: #f39c12;" title="Dipakai"></i>
                                            {{ __('Dipakai') }}
                                        @elseif ($item->status_pemakaian == 'Dikembalikan')
                                            <i class="fas fa-undo" style="color: #28a745;" title="Dikembalikan"></i>
                                            {{ __('Dikembalikan') }}
                                        @else
                                            <i class="fas fa-question-circle" style="color: #6c757d;"
                                                title="Status Tidak Diketahui"></i>
                                            {{ __('Status Tidak Diketahui') }}
                                        @endif
                                    </td>
                                    @if (auth()->user()->hasRole(['Super Admin']) || $hasDelete['delete'])
                                        <td style="width:120px">
                                            <div class="d-flex">
                                                @if (auth()->user()->hasRole(['Super Admin']))
                                                    <form action="{{ route('pemakaian.kembalikan', $item->riwayat_id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary"
                                                            onclick="return confirm('{{ __('Apakah Anda yakin pengguna telah kembalikan pemakaian ini?') }}')"
                                                            @if ($item->status_pemakaian != 'Dipakai') disabled @endif>
                                                            <i class="fas fa-undo"></i> {{ __('Selesai') }}
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($hasDelete['delete'])
                                                    <form action="{{ route('pemakaian.destroy', $item->riwayat_id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('{{ __('Apakah anda yakin ini hapus/batalkan pemakaian ini?') }}')"
                                                            @if ($item->status_pemakaian != 'Dipakai') disabled @endif>
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
                {{ __('Melihat') }} {{ $data->firstItem() }} {{ __('hingga') }} {{ $data->lastItem() }}
                {{ __('dari total') }} {{ $data->total() }} {{ __('Pemakaian Barang') }}
            </div>
            <div class="pagination">
                {{ $data->links() }}
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
    document.addEventListener('DOMContentLoaded', function() {
        const kodeBarangSelect = document.getElementById('kode_barang');
        const jumlahInput = document.getElementById('jumlah');
        const stokInfo = document.getElementById('stok-info');

        // Fungsi untuk update jumlah yang tersedia
        kodeBarangSelect.addEventListener('change', function() {
            const selectedOption = kodeBarangSelect.options[kodeBarangSelect.selectedIndex];
            const availableStock = selectedOption.getAttribute('data-jumlah');

            // Update informasi stok yang tersedia
            stokInfo.textContent = `Stok tersedia: ${availableStock}`;

            jumlahInput.setAttribute('max', availableStock);
            jumlahInput.setAttribute('min', 0);

            jumlahInput.value = 0;
        });

        // Trigger change event on load to set the initial stock
        kodeBarangSelect.dispatchEvent(new Event('change'));
    });
</script>
