<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama_req');
            $table->integer('id_kategori');
            $table->integer('jumlah_req');
            $table->string('alamat_req');
            $table->string('kota_req');
            $table->enum('status_req', ['aktif', 'tidak aktif']);
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('requests');
    }
}
