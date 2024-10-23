<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuanpengadaan', function (Blueprint $table) {
            $table->id('pengadaan_id');
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->date('tanggal_pengajuan');
            $table->enum('status_pengajuan', ['Diajukan', 'Disetujui', 'Ditolak']);
            $table->unsignedBigInteger('pengaju_id');
            $table->string('referensi')->nullable();
            $table->string('keterangan');
            $table->foreign('pengaju_id')->references('pengguna_id')->on('pengguna')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuanpengadaan');
    }
};
