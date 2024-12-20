@extends('layouts.admin')
@section('title', __('Buat Role | Inventaris GKJM'))

@section('main-content')
    <!-- Main Content goes here -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('role.store') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="nama_role">{{ __('Nama Role') }} <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control @error('nama_role') is-invalid @enderror" name="nama_role"
                        id="nama_role" placeholder="{{ __('Nama Role...') }}" autocomplete="off"
                        value="{{ old('nama_role') }}" onchange="generateSlug()">
                    @error('nama_role')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                <a href="{{ route('role.index') }}" class="btn btn-default">{{ __('Kembali ke list') }}</a>

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
