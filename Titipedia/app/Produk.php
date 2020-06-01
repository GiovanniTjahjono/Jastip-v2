<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = ['nama', 'jenis_produk', 'stok', 'harga_jasa', 'harga_produk', 'berat', 'keterangan', 'gambar', 'asal_pengiriman','id_user', 'id_kategori'];
}
