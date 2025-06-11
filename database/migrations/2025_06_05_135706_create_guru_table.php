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
       Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique()->notNullable();
            $table->string('nip')->unique()->notNullable();
            $table->string('password')->nullable();
            $table->string('nama_guru')->notNullable();
            $table->string('nama_mata_pelajaran')->nullable();
            $table->string('qr_code')->nullable();
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
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
        Schema::dropIfExists('guru');
    }
};
