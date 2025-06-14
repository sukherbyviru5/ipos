<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buku_rusak_hilang', function (Blueprint $table) {
            $table->id();
            $table->string('nik_siswa')->nullable();
            $table->string('nik_guru')->nullable();
            $table->unsignedBigInteger('id_qr');
            $table->string('sanksi');
            $table->enum('status_buku', ['rusak', 'hilang'])->default('hilang');
            $table->enum('status_sanksi', ['selesai', 'belum_selesai'])->default('belum_selesai');
            $table->date('tanggal_laporan')->default(now());
            $table->foreign('id_qr')->references('id')->on('qr_buku')->onDelete('cascade');
            $table->foreign('nik_siswa')->references('nik')->on('siswa')->onDelete('cascade');
            $table->foreign('nik_guru')->references('nik')->on('guru')->onDelete('cascade');
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
        Schema::dropIfExists('buku_rusak_hilang');
    }
};
