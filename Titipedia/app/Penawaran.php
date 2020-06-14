<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    protected $fillable = ['id_request', 'id_penawar', 'harga_jasa_penawaran', 'harga_produk_penawaran', 'alamat_penawaran', 'kota_penawaran', 'status'];
}
