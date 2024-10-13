@extends('layouts.admin')
@section('title', 'Edit Role dan Permissions | Inventaris GKJM')

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
                    <label for="nama_role">Nama Role</label>
                    <input type="text" class="form-control @error('nama_role') is-invalid @enderror" name="nama_role"
                        id="nama_role" value="{{ old('nama_role', $role->name) }}" placeholder="Nama Role..."
                        autocomplete="off">
                    @error('nama_role')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit button untuk simpan perubahan -->
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>

            <!-- Daftar Permissions -->
            <div id="permissions-list" class="mt-4 md-4">
                <h5>Daftar Hak Akses yang dimiliki Role:</h5>

                @foreach ($role->permissions as $permission)
                    <form action="{{ route('roles.permissions.destroy', [$role->id, $permission->id]) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Apakah kamu yakin ingin menghapus permission ini dari role?')">
                            {{ $permission->name }} <i class="fas fa-times"></i>
                        </button>
                    </form>
                @endforeach

                <h6 class="mt-4 md-4">Petunjuk: Klik Role yang mau dihapus...</h6>
            </div>

            <!-- Form untuk menambah Permission -->
            <form action="{{ route('role.givepermissions', $role->id) }}" method="POST" class="mt-4">
                @csrf

                <!-- Select Permissions -->
                <div class="form-group">
                    <label for="permissions">Tambah Permission</label>
                    <select name="permissions" id="permissions" class="form-control">
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->name }}">
                                {{ $permission->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit button untuk tambah permission -->
                <button type="submit" class="btn btn-primary">Tambah Hak Akses</button>
            </form>
        </div>

        <!-- Tombol kembali -->
        <a href="{{ route('role.index') }}" class="btn btn-default">Kembali ke list</a>
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
