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
        // Schema::create('RolePengguna', function (Blueprint $table) {
        //     $table->id('role_id');
        //     // $table->enum('nama_role', ['SuperAdmin','Majelis','AdminRuang','Pengguna']);
        //     $table->string('nama_role');
        //     $table->string('slug');
        //     $table->timestamps();
        // });

        Schema::create('Pengguna', function (Blueprint $table) {
            $table->id('pengguna_id');
            $table->string('nama_pengguna');
            $table->string('jabatan', 100)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // $table->unsignedBigInteger('role_id');
            // $table->foreign('role_id')->references('role_id')->on('RolePengguna')
            //     ->onUpdate('cascade');
        });


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('RolePengguna');
        Schema::dropIfExists('Pengguna');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
