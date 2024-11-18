@extends('layouts.admin')

@section('title', __('Buat Hak/Permission | Inventaris GKJM'))

@section('main-content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('hak.store') }}" method="post">
                @csrf

                <!-- Select Tindakan -->
                <div class="form-group">
                    <label for="tindakan">{{ __('Tindakan') }} <span class="text-danger">*</span> </label>
                    <select name="tindakan" id="tindakan" class="form-control" required onchange="generateSlug()">
                        <option value="">{{ __('Pilih Tindakan...') }}</option>
                        <option value="buat">{{ __('Buat') }}</option>
                        <option value="hapus">{{ __('Hapus') }}</option>
                        <option value="perbarui">{{ __('Perbarui') }}</option>
                        <option value="lihat">{{ __('Lihat') }}</option>
                        <option value="semua">{{ __('Semua') }}</option>
                    </select>
                </div>

                <!-- Select Entitas -->
                <div class="form-group">
                    <label for="entitas">{{ __('Entitas') }} <span class="text-danger">*</span> </label>
                    <select name="entitas" id="entitas" class="form-control" required onchange="generateSlug()">
                        <option value="">{{ __('Pilih Entitas...') }}</option>
                        <option value="barang">{{ __('Barang') }}</option>
                        <option value="pengadaan">{{ __('Pengadaan') }}</option>
                        <option value="penghapusan">{{ __('Penghapusan') }}</option>
                        <option value="pemakai">{{ __('Pemakai') }}</option>
                        <option value="peminjam">{{ __('Peminjam') }}</option>
                        <option value="semua">{{ __('Semua') }}</option>
                    </select>
                </div>

                <!-- Select Ruangan -->
                <div class="form-group">
                    <label for="ruangan">{{ __('Ruang') }} <span class="text-danger">*</span> </label>
                    <select name="ruangan" id="ruangan" class="form-control" required onchange="generateSlug()">
                        <option disabled="true">{{ __('Pilih Ruang...') }}</option>
                        <option value="semua" style="color:rgb(214, 22, 22);">{{ __('Semua Ruangan') }}</option>
                        @foreach ($ruangs as $ruangan)
                            <option value="{{ $ruangan->nama_ruang }}">{{ $ruangan->nama_ruang }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- <!-- Input Slug Hak -->
                <div class="form-group">
                    <label for="nama_hak_slug">{{ __('Slug Hak') }}</label>
                    <input type="text" class="form-control @error('nama_hak_slug') is-invalid @enderror"
                        name="nama_hak_slug" id="nama_hak_slug" placeholder="slug-hak..." autocomplete="off" readonly
                        required>
                    @error('nama_hak_slug')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> --}}
                <input type="hidden" class="form-control @error('nama_hak_slug') is-invalid @enderror" name="nama_hak_slug"
                    id="nama_hak_slug" placeholder="slug-hak..." autocomplete="off" readonly required>

                <div class="form-group">
                    <label for="deskripsi_hak">{{ __('Deskripsi Hak Akses') }}</label>
                    <p id="deskripsi_hak" class="form-control-static"></p>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                <a href="{{ route('hak.index') }}" class="btn btn-default">{{ __('Kembali ke list') }}</a>
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

        // Menghasilkan deskripsi hak akses berdasarkan slug
        const deskripsi = formatHakAkses(finalSlug);
        document.querySelector('#deskripsi_hak').innerText = deskripsi;
    }

    function formatHakAkses(slug) {
        const hakList = slug.split('-');
        const deskripsiMap = {
            'lihat': 'Melihat',
            'perbarui': 'Memperbarui',
            'buat': 'Membuat',
            'hapus': 'Menghapus',
            'peminjam': 'Peminjaman',
            'pengadaan': 'Pengadaan',
            'barang': 'Barang',
            'penghapusan': 'Penghapusan',
            'pemakai': 'Pemakaian',
            'semua': 'Semua',
        };

        let hakFormatted = [];
        let index = 0;

        hakList.forEach((item) => {
            if (item === 'semua' && index === 0) {
                hakFormatted.push('Melihat, Membuat, Memperbarui, Menghapus');
            } else if (item === 'semua' && index === 1) {
                hakFormatted.push('Pengadaan, Peminjaman, Barang, Penghapusan, dan Pemakaian');
            } else if (item === 'semua' && index === 2) {
                hakFormatted.push('Semua Ruangan');
            } else {
                hakFormatted.push(deskripsiMap[item] || item.charAt(0).toUpperCase() + item.slice(1));
            }
            index++;
        });

        if (hakFormatted.length > 1) {
            const lastElement = hakFormatted.pop();
            return hakFormatted.join(', ') + ' di ' + lastElement;
        } else {
            return hakFormatted.join(', ');
        }
    }
</script>
