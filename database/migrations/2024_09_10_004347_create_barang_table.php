<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Ruang', function (Blueprint $table) {
            $table->id('ruang_id');
            $table->string("nama_ruang",100);
            $table->timestamps();
        });
        Schema::create('KategoriBarang', function (Blueprint $table) {
            $table->id('kategori_barang_id');
            $table->string("nama_kategori",100);
            $table->timestamps();
        });
        Schema::create('KondisiBarang', function (Blueprint $table) {
            $table->id('kondisi_id');
            $table->string("deskripsi_kondisi",100);
            $table->timestamps();
        });
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang',50)->unique();
            $table->string('merek_barang', 100);
            $table->decimal('harga_pembelian', 15, 2)->nullable();
            $table->year('tahun_pembelian');
            $table->enum('perolehan_barang', ['Hibah', 'Pembelian']);
            $table->decimal('nilai_ekonomis_barang', 15, 2);
            $table->integer('jumlah');
            $table->enum('status_barang', ['Dipinjam', 'Diperbaiki', 'Dihapus','Ada','Dipakai']);
            $table->string('keterangan', 255)->nullable();
            $table->string('path_gambar', 255)->nullable();

            $table->unsignedBigInteger('ruang_id');
            $table->unsignedBigInteger('kondisi_id');
            $table->unsignedBigInteger('kategori_barang_id');
            // Foreign key constraints
            $table->foreign('ruang_id')->references('ruang_id')->on('Ruang')
            ->onUpdate('cascade');
            $table->foreign('kondisi_id')->references('kondisi_id')->on('KondisiBarang')
            ->onUpdate('cascade');
            $table->foreign('kategori_barang_id')->references('kategori_barang_id')->on('KategoriBarang')
            ->onUpdate('cascade');
            $table->timestamps();
        });
        Schema::create('DetilKeteranganBarang', function (Blueprint $table) {
            $table->id('keterangan_id');
            $table->string('kode_barang',50);
            $table->date("tanggal");
            $table->string("keterangan");
            $table->foreign('kode_barang')->references('kode_barang')->on('Barang')
            ->onUpdate('cascade');
            $table->timestamps();
        });

        //need working on pengajuanpengadaanm apakah perlu trigger
        Schema::create('PengajuanPengadaan', function (Blueprint $table) {
            $table->id('pengajuan_id');
            // $table->string('kode_barang',50);
            $table->integer("jumlah");
            $table->date("tanggal_pengajuan");
            $table->enum('status_pengajuan', ['Diajukan','Disetujui','Ditolak']);
            $table->unsignedBigInteger('pengaju_id');
            $table->string("keterangan");
            $table->foreign('pengaju_id')->references('pengguna_id')->on('Pengguna')
            ->onUpdate('cascade');
            $table->timestamps();
        });


        Schema::create('PeminjamanBarang', function (Blueprint $table) {
            $table->id('peminjaman_id');
            $table->string('kode_barang',50);
            $table->date("tanggal_peminjaman");
            $table->date("tanggal_pengembalian")->nullable();
            $table->unsignedBigInteger('peminjam_id');
            $table->enum('status_pengajuan', ['Dipinjam','Dikembalikan']);
            $table->foreign('kode_barang')->references('kode_barang')->on('Barang')
            ->onUpdate('cascade');
            $table->foreign('peminjam_id')->references('pengguna_id')->on('Pengguna')
            ->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('RiwayatPemakaiBarang', function (Blueprint $table) {
            $table->id('riwayat_id');
            $table->string('kode_barang',50);
            $table->unsignedBigInteger('pengguna_id');
            $table->date("tanggal_mulai");
            $table->date("tanggal_selesai");
            $table->string("keterangan");
            $table->foreign('kode_barang')->references('kode_barang')->on('Barang')
            ->onUpdate('cascade');
            $table->foreign('pengguna_id')->references('pengguna_id')->on('Pengguna')
            ->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('PenghapusanBarang', function (Blueprint $table) {
            $table->id('penghapusan_id');
            $table->string('kode_barang',50);
            $table->date("tanggal_penghapusan");
            $table->string("alasan_penghapusan");
            $table->decimal('nilai_sisa', 15, 2);
            $table->foreign('kode_barang')->references('kode_barang')->on('Barang')
            ->onUpdate('cascade');
            $table->timestamps();
        });
        Schema::create('LogBarang', function (Blueprint $table) {
            $table->id('log_id');
            $table->string('kode_barang',50);
            $table->date("tanggal");
            $table->enum('aksi', ['Pengadaan', 'Peminjaman', 'Pengembalian', 'Penghapusan', 'Perubahan Pemakai']);
            $table->unsignedBigInteger('pengguna_id');
            $table->string("keterangan");
            $table->foreign('kode_barang')->references('kode_barang')->on('Barang')
            ->onUpdate('cascade');
            $table->foreign('pengguna_id')->references('pengguna_id')->on('Pengguna')
            ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Ruang');
        Schema::dropIfExists('KategoriBarang');
        Schema::dropIfExists('KondisiBarang');
        Schema::dropIfExists('Barang');
        Schema::dropIfExists('DetilKeteranganBarang');
        Schema::dropIfExists('PengajuanPengadaan');
        Schema::dropIfExists('PeminjamanBarang');
        Schema::dropIfExists('RiwayatPemakaiBarang');
        Schema::dropIfExists('PenghapusanBarang');
        Schema::dropIfExists('LogBarang');

    }
};
