<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangTerkunciTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_terkunci', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->text('alasan_terkunci')->nullable();
            $table->timestamps();
            $table->foreign('kode_barang')->references('kode_barang')->on('barang')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang_terkunci');
    }
}
