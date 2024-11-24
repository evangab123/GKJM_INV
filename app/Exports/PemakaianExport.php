<?php

namespace App\Exports;

use App\Models\Pemakaian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class PemakaianExport implements FromCollection, WithHeadings
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($pemakaian) {
            return [
                'No' => $pemakaian->riwayat_id,

                'Kode Barang' => $pemakaian->barang->kode_barang ?? 'N/A',
                'Merek Barang' => $pemakaian->barang->merek_barang ?? 'N/A',

                'Jumlah' => $pemakaian->jumlah ?? 0,
                'Pengguna Akun' => $pemakaian->pengguna->nama_pengguna ?? 'N/A',

                'Tanggal Mulai' => Carbon::parse($pemakaian->tanggal_mulai)->format('Y-m-d') ?? 'N/A',
                'Tanggal Selesai' => Carbon::parse($pemakaian->tanggal_selesai)->format('Y-m-d') ?? 'N/A',

                'Keterangan' => $pemakaian->keterangan ?? 'N/A',
                'Status Pemakaian' => $pemakaian->status_pemakaian ?? 'N/A',

                'Dibuat' => $pemakaian->status_pemakaian ?? 'N/A',
                'Diperbarui' => $pemakaian->status_pemakaian ?? 'N/A',
            ];
        });
    }
    public function headings(): array
    {
        return [
            'No',
            'Kode Barang',
            'Merek Barang',
            'Jumlah',
            'Pengguna Akun',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Keterangan',
            'Status Pemakaian',
            'Dibuat',
            'Diperbarui'
        ];
    }
}

