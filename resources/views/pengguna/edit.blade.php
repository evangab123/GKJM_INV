@extends('layouts.admin')
@section('title', 'Edit Pengguna | Inventaris GKJM')

@section('main-content')

<div class="card">
    <div class="card-body">
        <form action="{{ route('pengguna.update', $user->pengguna_id) }}" method="post">
            @csrf
            @method('put')
            <input id='pengguna_id' type="hidden" name="pengguna_id" value="{{ $user->pengguna_id }}">

            <div class="form-group">
                <label for="nama_pengguna">{{ __('Nama Lengkap') }}</label>
                <input type="text" class="form-control @error('nama_pengguna') is-invalid @enderror" name="nama_pengguna" id="nama_pengguna" placeholder="Nama Lengkap" autocomplete="off" value="{{ old('nama_pengguna') ?? $user->nama_pengguna }}">
                @error('nama_pengguna')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="jabatan">{{ __('Jabatan') }}</label>
                <input type="text" class="form-control @error('jabatan') is-invalid @enderror" name="jabatan" id="jabatan" placeholder="Jabatan" autocomplete="off" value="{{ old('jabatan') ?? $user->jabatan }}">
                @error('jabatan')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="username">{{ __('Username') }}</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" placeholder="Username..." autocomplete="off" value="{{ old('username')?? $user->username }}">
                @error('username')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" autocomplete="off" value="{{ old('email') ?? $user->email }}">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password, Boleh Kosong..." autocomplete="off">
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('Password Konfirmasi') }}</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" autocomplete="off">
                @error('password_confirmation')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="role_id">{{ __('Role') }}</label>
                <select class="form-control @error('role_id') is-invalid @enderror" name="role_id" id="role_id" onchange="haklist()">
                    <option value="" disabled>{{ __('Pilih Role Pengguna') }}</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id || (isset($user) && $user->hasRole($role->name)) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Permissions List -->
            <div id="permissions-container" style="display: none;">
                <h5>{{ __('Permissions') }}</h5>
                <div id="permissions-list"></div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('pengguna.index') }}" class="btn btn-default">{{ __('kembali') }}</a>

        </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        haklist();
    });

    function formatHakAkses(hak) {
        const hakList = hak.split('-');
        const deskripsiMap = {
            'lihat': 'Melihat',
            'perbarui': 'Memperbarui',
            'buat': 'Membuat',
            'hapus': 'Menghapus',
            'peminjam': 'Peminjaman',
            'pengadaan': 'Pengadaan',
            'r.': 'Ruangan',
            'semua': 'Semua'
        };

        let hakFormatted = [];
        hakList.forEach((item, index) => {
            if (item === "semua" && index === 0) {
                hakFormatted.push("Melihat, Membuat, Memperbarui, Menghapus");
            } else if (item === "semua" && index === 1) {
                hakFormatted.push("Pengadaan, Peminjaman, Barang, Penghapusan, dan Pemakaian");
            } else if (item === "semua" && index === 2) {
                hakFormatted.push("Semua Ruangan");
            } else {
                hakFormatted.push(deskripsiMap[item] || item.charAt(0).toUpperCase() + item.slice(1));
            }
        });

        return hakFormatted.join(', ');
    }

    function haklist() {
        const roleId = document.getElementById('role_id').value;
        const penggunaid = document.getElementById('pengguna_id').value;
        const permissionsContainer = document.getElementById('permissions-container');

        permissionsContainer.innerHTML = '';

        if (penggunaid || roleId) {
            permissionsContainer.style.display = 'block';
            const url = `{{ route('pengguna.permissions.edit', ['pengguna' => '__pengguna_id__']) }}`
                .replace('__pengguna_id__', penggunaid) + `?roleId=${roleId}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.permissions.length > 0) {
                        const heading = document.createElement('h6');
                        heading.classList.add('font-weight-bold', 'mb-3');
                        heading.textContent = 'Daftar Hak Akses untuk Pengguna Ini:';
                        permissionsContainer.appendChild(heading);

                        const listGroup = document.createElement('div');
                        listGroup.classList.add('list-group');

                        const userPermissions = data.userPermissions.map(permission => permission.name);

                        data.permissions.forEach(permission => {
                            const checkboxContainer = document.createElement('div');
                            checkboxContainer.classList.add('form-check', 'mb-2');

                            const isChecked = userPermissions.includes(permission.name) ? 'checked' : '';

                            checkboxContainer.innerHTML = `
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="permissions[]"
                                   value="${permission.name}"
                                   id="permission-${permission.id}"
                                   ${isChecked}>
                            <label class="form-check-label" for="permission-${permission.id}">
                                ${formatHakAkses(permission.name)}
                            </label>
                        `;

                            listGroup.appendChild(checkboxContainer);
                        });

                        permissionsContainer.appendChild(listGroup);
                    } else {
                        const noPermissionsMessage = document.createElement('p');
                        noPermissionsMessage.classList.add('text-muted');
                        noPermissionsMessage.textContent = 'Tidak ada hak akses untuk pengguna atau role ini.';
                        permissionsContainer.appendChild(noPermissionsMessage);
                    }
                })
                .catch(error => {
                    console.error('Error fetching permissions:', error);
                    const errorMessage = document.createElement('p');
                    errorMessage.classList.add('text-danger');
                    errorMessage.textContent = 'Terjadi kesalahan saat mengambil hak akses.';
                    permissionsContainer.appendChild(errorMessage);
                });
        } else {
            // Optionally clear the permissions list if no user or role is selected
            const noUserMessage = document.createElement('p');
            noUserMessage.classList.add('text-muted');
            noUserMessage.textContent = 'Silakan pilih pengguna dan role untuk melihat hak akses.';
            permissionsContainer.appendChild(noUserMessage);
        }
    }
</script>
