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
            $table->string('batas_jumlah_buku_status')->default('non aktif');
            $table->string('batas_jumlah_buku')->nullable()->comment('Jumlah buku jika aktif, non aktif jika tidak terbatas');
            $table->string('lama_peminjaman_status')->default('non aktif');
            $table->string('lama_peminjaman')->nullable()->comment('Jumlah hari jika aktif, non aktif jika tidak terbatas');
            $table->string('lama_perpanjangan_status')->default('non aktif');
            $table->string('lama_perpanjangan')->nullable()->comment('Jumlah hari perpanjangan jika aktif, non aktif jika tidak terbatas');
            $table->string('batas_perpanjangan_status')->default('non aktif');
            $table->string('batas_perpanjangan')->nullable()->comment('Jumlah kali perpanjangan jika aktif, non aktif jika tidak terbatas');
            $table->string('denda_telat_status')->default('non aktif');
            $table->string('denda_telat')->nullable()->comment('Jumlah denda jika aktif, non aktif jika tidak ada denda');
            $table->string('perhitungan_denda')->default('non aktif')->comment('non aktif, per hari, atau per minggu');
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