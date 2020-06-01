<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = 'requests';
    protected $fillable = ['nama_req', 'jumlah_req', 'alamat_req', 'kota_req', 'status_req', 'keterangan', 'gambar', 'id_user'];

}
