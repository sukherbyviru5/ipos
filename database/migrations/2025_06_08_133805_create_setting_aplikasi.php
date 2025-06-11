<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_aplikasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi');
            $table->string('nama_sub_instansi');
            $table->string('nama_madrasah');
            $table->string('alamat_madrasah');
            $table->string('logo');
            $table->string('nama_kepala_madrasah');
            $table->string('nip_kamad');
            $table->string('nama_kepala_perpustakaan');
            $table->string('nip_kepala_perpustakaan');
            $table->string('email_madrasah')->nullable();
            $table->string('no_telpon')->nullable();
            $table->text('embed_maps')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_aplikasi');
    }
};