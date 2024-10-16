<?php

namespace App\Console\Commands;

use App\Models\Barang;
use Illuminate\Console\Command;

class UpdateNilaiEkonomis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-nilai-ekonomis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $barangs = Barang::all(); 
        foreach ($barangs as $barang) {
            $barang->nilai_ekonomis_barang = $this->hitungNilaiBaru($barang);
            $barang->save();
        }

        $this->info('Nilai ekonomis barang telah diperbarui.');
    }

    function calculateNilaiEkonomis($hargaPembelian, $tahunPembelian)
    {
        $umurEkonomis = 10;
        $nilaiSisa = 100;

        $hargaPembelian = floatval($hargaPembelian) ?: 0;
        $tahunPembelian = intval($tahunPembelian) ?: date('Y');

        $totalDepreciation = ($hargaPembelian - $nilaiSisa) / $umurEkonomis;

        $currentYear = date('Y');
        $yearsUsed = $currentYear - $tahunPembelian;

        $nilaiEkonomis = $hargaPembelian - ($totalDepreciation * $yearsUsed);
        $nilaiEkonomis = $nilaiEkonomis >= 0 ? $nilaiEkonomis : 0;

        return number_format($nilaiEkonomis, 2);
    }
}
