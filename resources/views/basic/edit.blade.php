@extends('layouts.admin')

@section('main-content')
    {{-- <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $title ?? __('Blank Page') }}</h1> --}}

    <!-- Main Content goes here -->

    <div class="card">
        <div class="card-body">
            <form action="{{ route('basic.update',$user->pengguna_id)}}" method="post">
                @csrf
                @method('put')

                <div class="form-group">
                  <label for="nama_pengguna">Nama Lengkap</label>
                  <input type="text" class="form-control @error('nama_pengguna') is-invalid @enderror" name="nama_pengguna" id="nama_pengguna" placeholder="Nama Lengkap" autocomplete="off" value="{{ old('nama_pengguna') ?? $user->nama_pengguna}}">
                  @error('nama_pengguna')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                {{-- <div class="form-group">
                  <label for="last_name">Last Name</label>
                  <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="last_name" placeholder="Last name" autocomplete="off" value="{{ old('last_name') ?? $pengguna->last_name }}">
                  @error('last_name')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div> --}}

                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" autocomplete="off" value="{{ old('email') ?? $user->email }}">
                  @error('email')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password, Boleh Kosong..." autocomplete="off">
                  @error('password')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        name="password_confirmation" id="password_confirmation" placeholder="Confirm Password"
                        autocomplete="off">
                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select class="form-control @error('role_id') is-invalid @enderror" name="role_id" id="role_id">
                        <option value="">Pilih Role Pengguna</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->role_id }}"
                                {{ (old('role_id') == $role->role_id || (isset($user) && $user->role_id == $role->role_id)) ? 'selected' : '' }}>
                                {{ $role->nama_role }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('basic.index') }}" class="btn btn-default">Back to list</a>

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
