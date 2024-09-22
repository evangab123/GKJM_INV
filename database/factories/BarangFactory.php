<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition()
    {
        $perolehanOptions = ['Hibah', 'Pembelian'];
        $statusoption = ['Dipinjam', 'Diperbaiki', 'Dihapus','Ada','Dipakai'];
        return [
            'kode_barang' => $this->faker->unique()->word,
            'merek_barang' => $this->faker->company,
            'harga_pembelian' => $this->faker->randomFloat(2, 1000, 10000),
            'tahun_pembelian' => $this->faker->year,
            'perolehan_barang' => $this->faker->randomElement($perolehanOptions),
            'nilai_ekonomis_barang' => $this->faker->randomFloat(2, 1000, 10000),
            'jumlah' => $this->faker->numberBetween(1, 100),
            'status_barang'  => $this->faker->randomElement($statusoption),
            'keterangan' => $this->faker->sentence,
            'ruang_id' => $this->faker->numberBetween(1, 14),
            'kondisi_id' => $this->faker->numberBetween(1, 5),
            'kategori_barang_id' => $this->faker->numberBetween(1, 8),
        ];
    }
}
