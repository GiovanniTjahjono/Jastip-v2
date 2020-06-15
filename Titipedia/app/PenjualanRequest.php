<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenjualanRequest extends Model
{
    protected $fillable = ['kode_transaksi', 'total_harga', 'kurir', 'service', 'ongkir', 'tanggal_penjualan', 'tanggal_pengiriman', 'nomer_resi', 'status_penjualan_req', 'id_user', 'id_penawaran', 'rating', 'review'];
}
