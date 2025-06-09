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
        Schema::create('setting_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('batas_jumlah_buku');
            $table->string('lama_peminjaman');
            $table->string('lama_perpanjangan');
            $table->string('batas_perpanjangan');
            $table->string('denda_telat');
            $table->string('perhitungan_denda')->comment('hitungan hari');
            $table->text('syarat_peminjaman');
            $table->text('syarat_perpanjangan');
            $table->text('syarat_pengembalian');
            $table->text('sanksi_kerusakan');
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
        Schema::dropIfExists('setting_peminjaman');
    }
};