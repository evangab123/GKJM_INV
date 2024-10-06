@extends('layouts.admin')
@section('title', 'Buat Role | Inventaris GKJM')

@section('main-content')
    {{-- <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ $title ?? __('Blank Page') }}</h1> --}}

    <!-- Main Content goes here -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('pengguna.store') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="nama_role">Nama Role</label>
                    <input type="text" class="form-control @error('nama_role') is-invalid @enderror" name="nama_role"
                        id="nama_role" placeholder="Nama Role..." autocomplete="off" value="{{ old('nama_role') }}" onchange="generateSlug()">
                    @error('nama_role')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_role_slug">Nama Role Slug</label>
                    <input type="text" class="form-control @error('nama_role_slug') is-invalid @enderror"
                        name="nama_role_slug" id="nama_role_slug" placeholder="nama-role-slug..." autocomplete="off"
                        value="{{ old('nama_role_slug') }}" readonly>
                    @error('nama_role_slug')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="permissions">Hak</label>
                    <input type="text" class="form-control @error('permissions') is-invalid @enderror" name="permissions"
                        id="permissions" data-role="tagsinput" placeholder="Hak gunakan Koma (,) untuk banyak isian!"
                        autocomplete="off" value="{{ old('permissions') }}">
                    @error('permissions')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('role.index') }}" class="btn btn-default">Kembali ke list</a>

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
    $(document).ready(function() {
        $('#permissions').tagsinput({
            delimiter: [','], // Allow commas as delimiters
            maxTags: 10 // Optional: set a maximum number of tags
        });
    });
</script>

<style>
    /* Change the background and text color of the tags input */
    .bootstrap-tagsinput {
        background-color: #f8f9fa; /* Light grey background */
        border: 1px solid #ced4da; /* Border color */
        border-radius: .25rem; /* Border radius */
        width: 100%
    }

    /* Change the color of the tags */
    .bootstrap-tagsinput .tag {
        background-color: #8894a1; /* Change tag color */
        color: white; /* Change tag text color */
        border-radius: 4px; /* Tag border radius */
        padding: 5px 10px; /* Padding for tags */
        margin-right: 5px; /* Space between tags */
        line-height: 1px;
    }

    /* Change the input field color when focused */
    #permissions:focus {
        border-color: #47494a; /* Change border color on focus */
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Focus shadow */
    }
</style>

<script>
    function generateSlug() {
        var namaRole = document.getElementById('nama_role').value;
        var slug = namaRole.toLowerCase()
                            .replace(/[^\w\s]/g, '') // Menghapus karakter non-alphanumeric
                            .replace(/\s+/g, '-') // Mengganti spasi dengan tanda hubung
                            .trim(); // Menghilangkan spasi di awal/akhir
        document.getElementById('nama_role_slug').value = slug;
    }
</script>
