<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('activity');
            $table->string('entitas');
            $table->string('id_objek');
            $table->text('changess')->nullable(); // Store changes in a single field
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->foreign('username')->references('username')->on('Pengguna')
                ->onUpdate('cascade');
        });
    }


    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
