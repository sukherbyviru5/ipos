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
       Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique()->notNullable();
            $table->string('nisn')->unique()->notNullable();
            $table->string('password')->nullable();
            $table->string('nama_siswa')->notNullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->notNullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->foreignId('id_kelas')->constrained('kelas')->onDelete('cascade')->nullable();
            $table->string('foto')->nullable();
            $table->string('qr_code')->nullable();
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->boolean('is_alumni')->default(false);
            $table->date('tanggal_kelulusan')->nullable();
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
        Schema::dropIfExists('siswa');
    }
};
