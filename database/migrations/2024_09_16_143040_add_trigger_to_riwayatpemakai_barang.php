<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTriggerToRiwayatpemakaiBarang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_riwayat_pemakai_barang_insert');

        DB::unprepared('
            CREATE TRIGGER after_riwayat_pemakai_barang_insert
            AFTER INSERT ON RiwayatPemakaiBarang
            FOR EACH ROW
            BEGIN
                -- Mengubah status barang menjadi "Dipakai" saat ada insert baru
                UPDATE Barang
                SET status_barang = "Dipakai"
                WHERE kode_barang = NEW.kode_barang;
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
        // Menghapus trigger jika migration dibatalkan
        DB::unprepared('DROP TRIGGER IF EXISTS after_riwayat_pemakai_barang_insert');
    }
}
