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
        Schema::create('denda', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjaman_siswa_id');
            $table->string('jumlah_denda')->default(0);
            $table->enum('status_denda', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->date('tanggal_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('peminjaman_siswa_id')->references('id')->on('peminjaman_siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('denda');
    }
};
