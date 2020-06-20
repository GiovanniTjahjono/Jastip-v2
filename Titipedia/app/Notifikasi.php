<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = ['isi_notifikasi', 'waktu_kirim', 'jenis', 'link', 'dibaca', 'id_penerima', 'id_trigger'];
}
