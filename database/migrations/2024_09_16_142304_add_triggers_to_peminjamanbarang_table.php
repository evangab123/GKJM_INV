<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTriggersToPeminjamanbarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Trigger untuk mengubah status barang menjadi 'Dipinjam' saat peminjaman terjadi
        DB::unprepared('
            CREATE TRIGGER after_peminjaman_barang_insert
            AFTER INSERT ON PeminjamanBarang
            FOR EACH ROW
            BEGIN
                IF NEW.status_pengajuan = "Dipinjam" THEN
                    UPDATE Barang
                    SET status_barang = "Dipinjam"
                    WHERE kode_barang = NEW.kode_barang;
                END IF;
            END
        ');

        // Trigger untuk mengubah status barang menjadi 'Ada' saat barang dikembalikan
        DB::unprepared('
            CREATE TRIGGER after_peminjaman_barang_update
            AFTER UPDATE ON PeminjamanBarang
            FOR EACH ROW
            BEGIN
                IF NEW.status_pengajuan = "Dikembalikan" THEN
                    UPDATE Barang
                    SET status_barang = "Ada"
                    WHERE kode_barang = NEW.kode_barang;

                    UPDATE PeminjamanBarang
                    SET tanggal_pengembalian = CURDATE()
                    WHERE peminjaman_id = NEW.peminjaman_id;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Menghapus trigger ketika migration dibatalkan
        DB::unprepared('DROP TRIGGER IF EXISTS after_peminjaman_barang_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_peminjaman_barang_update');
    }
}
