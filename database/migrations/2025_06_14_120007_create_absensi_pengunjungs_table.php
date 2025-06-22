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
        Schema::create('absensi_pengunjungs', function (Blueprint $table) {
            $table->id();
            $table->string('nik_siswa')->nullable();
            $table->string('nik_guru')->nullable();
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->unsignedBigInteger('guru_id')->nullable();
            $table->string('materi')->nullable();
            $table->boolean('is_kunjungan_kelas')->default(false);
            $table->foreign('nik_siswa')->references('nik')->on('siswa')->onDelete('cascade');
            $table->foreign('nik_guru')->references('nik')->on('guru')->onDelete('cascade');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('set null');
            $table->foreign('guru_id')->references('id')->on('guru')->onDelete('set null');
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
        Schema::dropIfExists('absensi_pengunjungs');
    }
};
