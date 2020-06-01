<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gambar extends Model
{
    protected $fillable = ['url', 'id_produk', 'id_bulkbuy', 'id_request'];
}
