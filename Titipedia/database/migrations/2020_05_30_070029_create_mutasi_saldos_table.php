<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutasiSaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_saldos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama_bank');
            $table->integer('saldo_masuk')->nullable();
            $table->integer('saldo_keluar')->nullable();
            $table->string('keterangan')->nullable();
            $table->dateTime('tanggal');
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutasi_saldos');
    }
}
