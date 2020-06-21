@extends('layouts.pembelianview')
@section('content')
<div class="container mt-5">
    <div class="card  shadow-lg p-3 mb-5 bg-white rounded border-0">
        <div class="card-header bg-white">
            <h3>Daftar Pembelian Request</h3>
        </div>
        <div class="card-body">
            @if (session('status') === "Pemberian Rating dan Review Berhasil!")
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif
            <div class="mt-3">
                <table id="table_order" class="table  table-responsive table-hover">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Jumlah Beli</th>
                            <th>Kurir</th>
                            <th>Servis</th>
                            <th>Nomer Resi</th>
                            <th>Total Harga</th>
                            <th>Status Pengiriman</th>
                            <th>Batas Pengiriman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan_requests as $data)
                        <tr>
                            <td>{{$data->kode_transaksi}}</td>
                            <td>{{$data->nama_req}}</td>
                            <td>{{$data->nama_kategori}}</td>
                            <td>{{$data->jumlah_req}}</td>
                            <td>{{$data->kurir}}</td>
                            <td>{{$data->service}}</td>
                            <td>{{$data->nomor_resi}}</td>
                            <td>Rp. {{number_format($data->total_harga)}}</td>
                            <td>{{$data->status_penjualan_req}}</td>
                            <td>
                                @if($data->status_penjualan_req === 'menunggu dikirim')
                                {{$batas_waktu[$loop->iteration - 1]}} Hari
                                @elseif($data->status_penjualan_req === 'dikirim')
                                Sedang dikirim
                                @else
                                @endif
                            </td>
                            <td>
                                @if($data->status_penjualan_req === 'dikirim')
                                <a href="/penjualan-penawaran-konfirmasi/{{$data->id}}" class="badge badge-success">Konfirmasi</a>
                                @elseif($data->status_penjualan_req === 'menunggu dikirim')
                                <label>Menunggu Pengiriman</label>
                                @elseif($data->status_penjualan_req === 'diterima' && $data->review === NULL)
                                <a href="/pembelian-penawaran-rating/{{$data->id}}" class="badge badge-primary">Beri Rating</a>
                                @elseif($data->status_penjualan_req === 'diterima')
                                <label>Sukses</label>
                                @elseif($data->status_penjualan_req === 'dibatalkan')
                                <label>Dibatalkan</label>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop