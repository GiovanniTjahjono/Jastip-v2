<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukBulkBuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_bulk_buys', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama');
            $table->integer('jumlah_target');
            $table->integer('harga_jasa');
            $table->integer('harga_produk');
            $table->integer('berat');
            $table->string('keterangan')->nullable();
            $table->string('asal_pengiriman');
            $table->string('asal_negara');
            $table->integer('id_user');
            $table->integer('id_kategori');
            $table->date('batas_waktu');
            $table->enum('status_bulk', ['aktif', 'tidak aktif']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk_bulk_buys');
    }
}
