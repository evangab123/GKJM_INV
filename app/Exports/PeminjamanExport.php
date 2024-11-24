<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;


class PeminjamanExport implements FromCollection, WithHeadings
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($peminjaman) {
            return [
                'ID' => $peminjaman->peminjaman_id,

                'Kode Barang' => $peminjaman->barang->kode_barang ?? 'N/A',
                'Merek Barang' => $peminjaman->barang->merek_barang ?? 'N/A',

                'Jumlah' => $peminjaman->jumlah ?? 0,
                // 'Pengguna Akun' => $peminjaman->pengguna->nama_pengguna ?? 'N/A',

                'Tanggal Peminjaman' => Carbon::parse($peminjaman->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') ?? 'N/A',
                'Tanggal Kembali' => Carbon::parse($peminjaman->tanggal_selesai)->locale('id')->isoFormat('D MMMM YYYY') ?? 'N/A',
                'Tanggal Pengembalian' => Carbon::parse($peminjaman->tanggal_selesai)->locale('id')->isoFormat('D MMMM YYYY') ?? 'N/A',

                'Keterangan' => $peminjaman->keterangan ?? 'N/A',
                'Status peminjaman' => $peminjaman->status_peminjaman ?? 'N/A',

                'Dibuat' => $peminjaman->created_at ? Carbon::parse($peminjaman->created_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') : 'N/A',
                'Diperbarui' => $peminjaman->updated_at ? Carbon::parse($peminjaman->updated_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') : 'N/A',
            ];
        });
    }
    public function headings(): array
    {
        return [
            'ID',
            'Kode Barang',
            'Merek Barang',
            'Jumlah',
            // 'Pengguna Akun',
            'Tanggal Peminjaman',
            'Tanggal Kembali',
            'Tanggal Pengembalian',
            'Keterangan',
            'Status Peminjaman',
            'Dibuat',
            'Diperbarui'
        ];
    }
}
