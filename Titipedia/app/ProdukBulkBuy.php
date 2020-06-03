<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdukBulkBuy extends Model
{
    //
    protected $fillable = ['nama', 'jumlah_target', 'harga_jasa', 'harga_produk', 'berat', 'asal_pengiriman', 'batas_waktu', 'status_bulk', 'keterangan', 'id_user', 'id_kategori'];
}
