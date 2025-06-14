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
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku')->unique()->nullable(false)->comment('DDC.NoUrut.Penerbit.Singkatan.NoStok');
            $table->foreignId('id_ddc')->constrained('ddc_buku')->onDelete('cascade');
            $table->foreignId('id_kategori')->constrained('kategori_buku')->onDelete('cascade');
            $table->string('singkatan_buku')->nullable();
            $table->string('isbn')->nullable();
            $table->string('judul_buku')->nullable(false);
            $table->string('penulis_buku')->nullable();
            $table->string('penerbit_buku')->nullable();
            $table->string('tempat_terbit')->nullable();
            $table->string('tahun_terbit')->nullable();
            $table->string('asal_buku')->nullable();
            $table->text('sinopsis')->nullable();
            $table->foreignId('id_kondisi')->constrained('kondisi_buku')->onDelete('cascade');
            $table->foreignId('id_jenis')->constrained('jenis_buku')->onDelete('cascade');
            $table->string('harga_buku')->nullable();
            $table->string('stok_buku')->nullable(false);
            $table->string('lokasi_lemari')->nullable();
            $table->string('lokasi_rak')->nullable();
            $table->string('cover_buku')->nullable();
            $table->boolean('ebook_tersedia')->default(false);
            $table->string('ebook_file')->nullable();
            $table->string('view_count')->default(0);
            $table->string('created_by');
            $table->timestamps();
        });

        Schema::create('qr_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_buku')->constrained('buku')->onDelete('cascade');
            $table->string('no_urut');
            $table->string('kode');
            $table->string('path_qr')->nullable();
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
        Schema::dropIfExists('qr_buku');
        Schema::dropIfExists('buku');
    }
};
