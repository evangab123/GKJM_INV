@extends('layouts.admin')
@section('title', 'Edit Hak/Permission | Inventaris GKJM')

@section('main-content')
    <!-- Main Content -->
    <div class="card">
        <div class="card-body">
            <h2 class="text-info mb-3">Petunjuk: Nama yang disimpan adalah slug</h2>
            <p class="text-muted mb-4">Silakan masukkan nama hak/permission. Slug akan otomatis dihasilkan berdasarkan nama yang Anda masukkan.</p>
            <form action="{{ route('hak.update', $permission->id) }}" method="post">
                @csrf
                <!-- Input Nama Hak -->
                <div class="form-group">
                    <label for="nama_hak">Nama Hak/Permission</label>
                    <input type="text" class="form-control @error('nama_hak_slug') is-invalid @enderror" name="nama_hak_slug"
                        id="nama_hak_slug" placeholder="Nama Hak..." autocomplete="off" value="{{ old('nama_hak_slug', $permission->name) }}"
                        onchange="generateSlug()">
                    @error('nama_hak_slug')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Slug Hak -->
                <div class="form-group">
                    <label for="slug">Slug Hak</label>
                    <input type="text" class="form-control @error('nama_hak_slug') is-invalid @enderror"
                        name="nama_hak_slug" id="nama_hak_slug" placeholder="slug-hak..." autocomplete="off"
                        value="{{ old('nama_hak_slug', $permission->name) }}" readonly>
                    @error('nama_hak_slug')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('hak.index') }}" class="btn btn-default">Kembali ke list</a>
            </form>
        </div>
    </div>
    <!-- End of Main Content -->
@endsection

<script>
    function generateSlug() {
        const namahak = document.querySelector('#nama_hak').value; // Menggunakan querySelector
        const slug = namahak.toLowerCase()
            .replace(/[^\w ]+/g, '') // Menghapus karakter spesial
            .replace(/ +/g, '-'); // Mengganti spasi dengan tanda hubung
        document.querySelector('#nama_hak_slug').value = slug; // Menggunakan querySelector
    }
</script>
