<?php

namespace App\Exports;

use App\Models\Pengadaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class PengadaanExport implements FromCollection, WithHeadings
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
                'Merek Barang' => $pengadaan->barang->merek_barang ?? 'N/A',
                'Referensi' => $pengadaan->referensi ?? 'N/A',
                'Pengaju' => $pengadaan->pengguna->nama_pengguna ?? 'N/A',

                'Tanggal Pengajuan' => Carbon::parse($pengadaan->tanggal_pengajuan)->locale('id')->isoFormat('D MMMM YYYY') ?? 'N/A',

                'Kode Barang' => $pengadaan->kode_barang ?? 'Barang Belum Dibuat',
                'Status' => $pengadaan->status_pengajuan ?? 'N/A',

                'Dibuat' => $pengadaan->created_at ? Carbon::parse($pengadaan->created_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') : 'N/A',
                'Diperbarui' => $pengadaan->updated_at ? Carbon::parse($pengadaan->updated_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') : 'N/A',
            ];
        });
    }
    public function headings(): array
    {
        return [
            'ID',
            'Merek Barang',
            'Referensi',
            'Pengaju',
            'Tanggal Pengajuan',
            'Kode Barang',
            'Status',
            'Dibuat',
            'Diperbarui',
        ];
    }
}
