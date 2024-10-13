@extends('layouts.admin')
@section('title', 'Buat Hak/Permission | Inventaris GKJM')

@section('main-content')
    <div class="card">
        <div class="card-body">
            <h2 class="text-info mb-3">Petunjuk: Nama yang disimpan adalah slug</h2>
            <p class="text-muted mb-4">Silakan masukkan sesuai form. Slug akan otomatis dihasilkan berdasarkan pilihan Anda.
            </p>

            <form action="{{ route('hak.store') }}" method="post">
                @csrf

                <!-- Select Tindakan -->
                <div class="form-group">
                    <label for="tindakan">Tindakan</label>
                    <select name="tindakan" id="tindakan" class="form-control" required onchange="generateSlug()">
                        <option value="">Pilih Tindakan...</option>
                        <option value="buat">Buat</option>
                        <option value="hapus">Hapus</option>
                        <option value="perbarui">Perbarui</option>
                        <option value="lihat">Lihat</option>
                        <option value="semua">Semua</option>
                    </select>
                </div>

                <!-- Select Entitas -->
                <div class="form-group">
                    <label for="entitas">Entitas</label>
                    <select name="entitas" id="entitas" class="form-control" required onchange="generateSlug()">
                        <option value="">Pilih Entitas...</option>
                        <option value="barang">Barang</option>
                        <option value="pengadaan">Pengadaan</option>
                        <option value="pemakai">Pemakai</option>
                        <option value="peminjam">Peminjam</option>
                        <option value="semua">Semua</option>
                    </select>
                </div>

                <!-- Select Ruangan -->
                <div class="form-group">
                    <label for="ruangan">Ruang</label>
                    <select name="ruangan" id="ruangan" class="form-control" required onchange="generateSlug()">
                        <option disabled="true">Pilih Ruang...</option>
                        <option value="semua" style="color:rgb(214, 22, 22);">Semua Ruangan</option>
                        @foreach ($ruangs as $ruangan)
                            <option value="{{ $ruangan->nama_ruang }}">{{ $ruangan->nama_ruang }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Slug Hak -->
                <div class="form-group">
                    <label for="nama_hak_slug">Slug Hak</label>
                    <input type="text" class="form-control @error('nama_hak_slug') is-invalid @enderror"
                        name="nama_hak_slug" id="nama_hak_slug" placeholder="slug-hak..." autocomplete="off" readonly required>
                    @error('nama_hak_slug')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('hak.index') }}" class="btn btn-default">Kembali ke list</a>
            </form>
        </div>
    </div>
@endsection

<script>
    function generateSlug() {
        const tindakan = document.querySelector('#tindakan').value;
        const entitas = document.querySelector('#entitas').value;
        const ruangan = document.querySelector('#ruangan').value;

        // Membuat slug dari pilihan
        const slugTindakan = tindakan ? tindakan.toLowerCase() : '';
        const slugEntitas = entitas ? entitas.toLowerCase() : '';
        const slugRuang = ruangan ? ruangan.toLowerCase() : '';

        // Menggabungkan semua slug
        const finalSlug = [slugTindakan, slugEntitas, slugRuang].filter(Boolean).join('-');

        // Memperbarui nilai input slug
        document.querySelector('#nama_hak_slug').value = finalSlug;
    }
</script>
