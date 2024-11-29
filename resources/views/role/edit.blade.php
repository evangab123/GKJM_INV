@extends('layouts.admin')
@section('title', __('Edit Role dan Hak | Inventaris GKJM'))
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
            'semua' => 'Semua'
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
    <!-- Main Content -->
    <div class="card">
        <div class="card-body">
            <!-- Form untuk update Role -->
            <form action="{{ route('role.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Role -->
                <div class="form-group">
                    <label for="nama_role">{{ __('Nama Role') }}</label>
                    <input type="text" class="form-control @error('nama_role') is-invalid @enderror" name="nama_role"
                        id="nama_role" value="{{ old('nama_role', $role->name) }}" placeholder="{{ __('Nama Role...') }}"
                        autocomplete="off">
                    @error('nama_role')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit button untuk simpan perubahan -->
                <button type="submit" class="btn btn-primary">{{ __('Simpan Perubahan') }}</button>
            </form>

            <!-- Daftar Hak -->
            <div id="permissions-list" class="mt-4 md-4">
                <h5>{{ __('Daftar Hak Akses yang dimiliki Role:') }}</h5>

                @foreach ($role->permissions as $permission)
                    <form action="{{ route('roles.permissions.destroy', [$role->id, $permission->id]) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('{{ __('Apakah kamu yakin ingin menghapus permission ini dari role?') }}')">
                            {{ formatHakAkses($permission->name) }} <i class="fas fa-times"></i>
                        </button>
                    </form>
                @endforeach

                <h6 class="mt-4 md-4">{{ __('Petunjuk: Klik Role yang mau dihapus...') }}</h6>
            </div>

            <!-- Form untuk menambah Permission -->
            <form action="{{ route('role.givepermissions', $role->id) }}" method="POST" class="mt-4">
                @csrf

                <!-- Select Permissions -->
                <div class="form-group">
                    <label for="permissions">{{ __('Tambah Hak') }}</label>
                    <select name="permissions" id="permissions" class="form-control">
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->name }}">
                                {{ formatHakAkses($permission->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit button untuk tambah permission -->
                <button type="submit" class="btn btn-primary">{{ __('Beri Izin Hak Akses') }}</button>
            </form>
            <div class="mt-2">
                <a href="{{ route('hak.create') }}" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> {{ __('Buat Hak!') }}
                </a>
                <small>{{ __('Jika hak belum ada klik tombol di bawah untuk membuat hak baru') }}</small>
            </div>
        </div>

        <!-- Tombol kembali -->
        <a href="{{ route('role.index') }}" class="btn btn-default">{{ __('Kembali ke list') }}</a>
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
