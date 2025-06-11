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
        Schema::create('profil_perpustakaan', function (Blueprint $table) {
            $table->id();
            $table->longText('sejarah')->nullable();
            $table->string('struktur_organisasi')->nullable();
            $table->longText('visi_misi')->nullable();
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
        Schema::dropIfExists('profil_perpustakaan');
    }
};
