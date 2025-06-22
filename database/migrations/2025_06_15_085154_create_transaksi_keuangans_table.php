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
        Schema::create('transaksi_keuangans', function (Blueprint $table) {
            $table->id();
            $table->text('uraian');
            $table->string('nominal')->nullable()->default(0);
            $table->enum('type', ['kredit', 'debit']);
            $table->enum('sumber', ['kas', 'denda']);
            $table->date('tanggal');
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
        Schema::dropIfExists('transaksi_keuangans');
    }
};
