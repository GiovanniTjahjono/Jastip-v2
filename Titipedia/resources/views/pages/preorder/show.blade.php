@extends('layouts.pembelianview')
@section('content')
<div class="container mt-5">
    <div class="card  shadow-lg p-3 mb-5 bg-white rounded border-0">
        <div class="card-header bg-white">
            <h3>Daftar Pre-Order</h3>
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
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Batas Pengiriman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $data)
                        <tr>
                            <td>{{$data->kode_transaksi}}</td>
                            <td>{{$data->nama}}</td>
                            <td>{{$data->nama_kategori}}</td>
                            <td>{{$data->kuantitas}}</td>
                            <td>{{$data->kurir}}</td>
                            <td>{{$data->service}}</td>
                            <td>{{$data->total_harga}}</td>
                            <td>{{$data->status_order}}</td>
                            <td>
                                @if($data->status_order === 'menunggu')
                                {{$sisa_waktu}} Hari
                                @elseif($data->status_order === 'dikirim')
                                Sedang dikirim
                                @else
                                @endif
                            </td>
                            <td>
                                <!-- <a href="" class="badge badge-danger">delete</a> -->
                                @if($data->status_order === 'dikirim')
                                <a href="/terjual-konfirmasi/{{$data->id}}" class="badge badge-success">Konfirmasi</a>
                                @elseif($data->status_order === 'menunggu')
                                <label>Menunggu Pengiriman</label>
                                @elseif($data->status_order === 'diterima' && $data->review === NULL)
                                <a href="/terjual-rating/{{$data->id}}" class="badge badge-primary">Beri Rating</a>
                                @elseif($data->status_order === 'diterima')
                                <label>Sukses</label>
                                @elseif($data->status_order === 'dibatalkan')
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