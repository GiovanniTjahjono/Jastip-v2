<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanPreordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan_preorders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('kode_transaksi');
            $table->string('kurir');
            $table->string('service');
            $table->integer('ongkir');
            $table->integer('total_harga');
            $table->integer('kuantitas');
            $table->date('tanggal_penjualan');
            $table->date('tanggal_pengiriman')->nullable();
            $table->string('nomor_resi')->nullable();
            $table->enum('status_order', ['menunggu', 'dikirim', 'diterima', 'selesai']);
            $table->integer('rating');
            $table->string('review')->nullable();
            $table->integer('id_produk')->nullable();
            $table->integer('id_bulkbuy')->nullable();
            $table->integer('id_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan_preorders');
    }
}
