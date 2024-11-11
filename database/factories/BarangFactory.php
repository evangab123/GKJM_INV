<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\KondisiBarang;
use App\Models\Ruang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition()
    {
        // Ambil kategori barang yang ada untuk mengisi kategori_barang_id
        $kategoriBarang = KategoriBarang::inRandomOrder()->first();
        $kondisiBarang = KondisiBarang::inRandomOrder()->first();
        $ruang = Ruang::inRandomOrder()->first();

        // Membuat kode barang otomatis berdasarkan logika di store
        $tahunBeli = $this->faker->year;
        $kategoriNama = $kategoriBarang->nama_kategori;

        $kataArray = explode(' ', $kategoriNama);

        $singkatanKategori = '';
        foreach ($kataArray as $kata) {
            $singkatanKategori .= strtoupper(substr($kata, 0, 1));
        }

        if (count($kataArray) == 1) {
            $singkatanKategori = strtoupper(substr($kategoriNama, 0, 3));
        }

        $lastBarang = Barang::where('kategori_barang_id', $kategoriBarang->kategori_barang_id)
            ->orderBy('id', 'desc')
            ->first();

        $nomorUrut = $lastBarang ? intval(substr($lastBarang->kode_barang, -4)) + 1 : 1;
        $nomorUrutFormatted = str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

        $kodeBarang = "GKJM" . '-' . $tahunBeli . '-' . $singkatanKategori . '-' . $nomorUrutFormatted;

        while (Barang::where('kode_barang', $kodeBarang)->exists()) {
            $nomorUrut++;
            $nomorUrutFormatted = str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
            $kodeBarang = "GKJM" . '-' . $tahunBeli . '-' . $singkatanKategori . '-' . $nomorUrutFormatted;
        }

        $hargaPembelian = $this->faker->randomNumber(5);

        $umurEkonomis = 10;
        $nilaiSisa = 100;

        $totalDepreciation = ($hargaPembelian - $nilaiSisa) / $umurEkonomis;

        $currentYear = date('Y');
        $yearsUsed = $currentYear - $tahunBeli;
        $nilaiEkonomis = $hargaPembelian - ($totalDepreciation * $yearsUsed);

        $nilaiEkonomis = $nilaiEkonomis >= 0 ? $nilaiEkonomis : 0;

        $perolehanOptions = ['Persembahan', 'Pembelian', 'Pembuatan'];

        $perolehanBarang = $perolehanOptions[array_rand($perolehanOptions)];
        return [
            'kode_barang' => $kodeBarang,
            'merek_barang' => $this->faker->word,
            'perolehan_barang' => $perolehanBarang,
            'harga_pembelian' => $hargaPembelian,
            'tahun_pembelian' => $tahunBeli,
            'nilai_ekonomis_barang' => $nilaiEkonomis, // Nilai ekonomis dihitung
            'jumlah' => $this->faker->randomDigitNotNull,
            'keterangan' => $this->faker->sentence,
            'ruang_id' => $ruang->ruang_id,
            'kondisi_id' => $kondisiBarang->kondisi_id,
            'kategori_barang_id' => $kategoriBarang->kategori_barang_id,
            'status_barang' => 'Ada',
            'path_gambar' => null,
        ];
    }
}
