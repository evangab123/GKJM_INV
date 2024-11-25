<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class BarangExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    /**
     * Constructor to pass data.
     *
     * @param \Illuminate\Support\Collection $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->data->map(function ($barang) {
            return [
                'Kode Barang' => $barang->kode_barang ?? 'N/A',
                'Merek' => $barang->merek_barang ?? 'N/A',
                'Perolehan' => $barang->perolehan_barang ?? 'N/A',
                'Harga' => $barang->harga_pembelian ?? 'N/A',
                'Tahun' => $barang->tahun_pembelian ?? 'N/A',
                'Nilai Ekonomis' => $barang->nilai_ekonomis_barang,
                'Jumlah/Stok' => $barang->jumlah ?? 'N/A',
                'Jumlah/Stok dipinjam' => $barang->peminjaman->where('status_peminjaman', 'Dipinjam')->sum('jumlah') ?? 'N/A',
                'Jumlah/Stok dipakai' => $barang->pemakaian->where('status_pemakaian', 'Dipakai')->sum('jumlah') ?? 'N/A',
                'Keterangan' => $barang->keterangan ?? 'N/A',
                'Ruang' => $barang->ruang->nama_ruang ?? 'N/A',
                'Kondisi' => $barang->kondisi->deskripsi_kondisi ?? 'N/A',
                'Kategori' => $barang->kategori->nama_kategori ?? 'N/A',
                'Status' => $barang->status_barang ?? 'N/A',
                'Dibuat' => $barang->created_at ? Carbon::parse($barang->created_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') : 'N/A',
                'Diperbarui' => $barang->updated_at ? Carbon::parse($barang->updated_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') : 'N/A',
                'Detil Keterangan' => $barang->detilketerangan && $barang->detilketerangan->isNotEmpty()
                ? $barang->detilketerangan->map(function ($detil) {
                    return 'ID: ' . $detil->keterangan_id .
                           ', Tanggal: ' . Carbon::parse($detil->tanggal)->locale('id')->isoFormat('D MMMM YYYY') .
                           ', Keterangan: ' . $detil->keterangan .
                           ', Dibuat: ' . Carbon::parse($detil->created_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') .
                           ', Diperbarui: ' . Carbon::parse($detil->updated_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm');
                })->implode('; ')
                : 'N/A',


            ];
        });
    }

    /**
     * Return headings for the Excel export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Kode Barang',
            'Merek',
            'Perolehan',
            'Harga',
            'Tahun',
            'Nilai Ekonomis',
            'Jumlah/Stok',
            'Jumlah/Stok Dipinjam',
            'Jumlah/Stok dipakai',
            'Keterangan',
            'Ruang',
            'Kondisi',
            'Kategori',
            'Status',
            'Dibuat',
            'Diperbarui',
            'Detil Keterangan',
        ];
    }
    public function columnFormats(): array
    {
        return [
            'D' => '"Rp " #,##0',
            'F' => '"Rp " #,##0',
        ];
    }
}
