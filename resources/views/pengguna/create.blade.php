@extends('layouts.admin')
@section('title', 'Buat Pengguna | Inventaris GKJM')

@section('main-content')

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pengguna.store') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="nama_pengguna">Nama Lengkap</label>
                    <input type="text" class="form-control @error('nama_pengguna') is-invalid @enderror"
                        name="nama_pengguna" id="nama_pengguna" placeholder="Nama Lengkap..." autocomplete="off"
                        value="{{ old('nama_pengguna') }}">
                    @error('nama_pengguna')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" class="form-control @error('jabatan') is-invalid @enderror" name="jabatan"
                        id="jabatan" placeholder="Jabatan..." autocomplete="off" value="{{ old('jabatan') }}">
                    @error('jabatan')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        id="email" placeholder="Email..." autocomplete="off" value="{{ old('email') }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                        id="password" placeholder="Password" autocomplete="off">
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Password Konfirmasi</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        name="password_confirmation" id="password_confirmation" placeholder="Password Konfirmasi"
                        autocomplete="off">
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select class="form-control @error('role_id') is-invalid @enderror" name="role_id" id="role_id"
                        onchange="haklist()">
                        <option value="">Pilih Role Pengguna</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
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
                    <h5>Permissions</h5>
                    <div id="permissions-list"></div>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('pengguna.index') }}" class="btn btn-default">Kembali ke list</a>

            </form>
        </div>
    </div>

    <script>
            document.addEventListener('DOMContentLoaded', function() {
        haklist(); // Call the function to populate permissions on page load
    });
        function haklist() {
            const roleId = document.getElementById('role_id').value;
            const permissionsContainer = document.getElementById('permissions-container');

            // Clear previous content
            permissionsContainer.innerHTML = '';

            if (roleId) {
                permissionsContainer.style.display = 'block';
                fetch(`/roles/${roleId}/permissions`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.permissions.length > 0) {
                            // Create a heading for the permissions list
                            const heading = document.createElement('h6');
                            heading.classList.add('font-weight-bold', 'mb-3');
                            heading.textContent = 'Daftar Hak Akses untuk Role Ini:';
                            permissionsContainer.appendChild(heading);

                            // Create a list for permissions
                            const listGroup = document.createElement('div');
                            listGroup.classList.add('list-group');

                            // Iterate over the permissions and create checkboxes
                            data.permissions.forEach(permission => {
                                const checkboxContainer = document.createElement('div');
                                checkboxContainer.classList.add('form-check', 'mb-2'); // Add margin-bottom here

                                checkboxContainer.innerHTML = `
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="permissions[]"
                                   value="${permission.name}"
                                   id="permission-${permission.id}">
                            <label class="form-check-label" for="permission-${permission.id}">
                                ${permission.name}
                            </label>
                        `;

                                listGroup.appendChild(checkboxContainer);
                            });

                            permissionsContainer.appendChild(listGroup);
                        } else {
                            // Display a message if no permissions are found
                            const noPermissionsMessage = document.createElement('p');
                            noPermissionsMessage.classList.add('text-muted');
                            noPermissionsMessage.textContent = 'Tidak ada hak akses untuk role ini.';
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
                // Optionally clear the permissions list if no role is selected
                const noRoleMessage = document.createElement('p');
                noRoleMessage.classList.add('text-muted');
                noRoleMessage.textContent = 'Silakan pilih role untuk melihat hak akses.';
                permissionsContainer.appendChild(noRoleMessage);
            }
        }
    </script>



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

@endsection
