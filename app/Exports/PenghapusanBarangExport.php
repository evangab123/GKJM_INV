<?php

namespace App\Exports;

use App\Models\PenghapusanBarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class PenghapusanBarangExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($pengadaan) {
            return [
                'ID' => $pengadaan->pengadaan_id,
                'Kode Barang' => $pengadaan->kode_barang ?? 'N/A',
                'Merek Barang' => $pengadaan->barang->merek_barang ?? 'N/A',
                'Tanggal Penghapusan' => Carbon::parse($pengadaan->tanggal_penghapusan)->locale('id')->isoFormat('D MMMM YYYY') ?? 'N/A',
                'Alasan Penghapusan' => $pengadaan->alasan_penghapusan ?? 'N/A',
                'Nilai Sisa' => $pengadaan->nilai_sisa ? $pengadaan->nilai_sisa : 'N/A',
                'Dibuat' => $pengadaan->created_at ? Carbon::parse($pengadaan->created_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') : 'N/A',
                'Diperbarui' => $pengadaan->updated_at ? Carbon::parse($pengadaan->updated_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') : 'N/A',
            ];
        });
    }
    public function headings(): array
    {
        return [
            'ID',
            'Kode Barang',
            'Merek Barang',
            'Tanggal Penghapusan',
            'Alasan Penghapusan',
            'Nilai Sisa',
            'Dibuat',
            'Diperbarui',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => '"Rp " #,##0',
        ];
    }
}
