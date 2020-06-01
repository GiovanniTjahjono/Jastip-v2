<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MutasiSaldo extends Model
{
    protected $fillable = ['nama_bank', 'saldo_masuk', 'saldo_keluar', 'keterangan', 'tanggal', 'user_id'];
}
