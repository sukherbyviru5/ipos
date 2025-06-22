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
        Schema::create('peminjaman_guru', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('grup');
            $table->string('nik_guru');
            $table->unsignedBigInteger('id_qr');
            $table->date('tanggal_pinjam')->nullable(false);
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status_peminjaman', ['dipinjam', 'dikembalikan', 'telat', 'bermasalah'])->nullable(false);
            $table->timestamps();

            $table->foreign('nik_guru')->references('nik')->on('guru')->onDelete('cascade');
            $table->foreign('id_qr')->references('id')->on('qr_buku')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjaman_guru');
    }
};