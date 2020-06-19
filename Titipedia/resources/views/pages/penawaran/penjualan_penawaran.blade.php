@extends('layouts.produkview')
@section('content')
<div class="container mt-5">
    <div class="card  shadow-lg p-3 mb-5 bg-white rounded border-0">
        <div class="card-header bg-white">
            <h3>Daftar Pre-Order</h3>
        </div>
        <div class="card-body">
            @if (session('status') === "Data Berhasil Ditambahkan!")
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @elseif (session('status')==="Data Berhasil Diubah!")
            <div class="alert alert-primary">
                {{ session('status') }}
            </div>
            @elseif (session('status')==="Data Berhasil Dibatalkan!")
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
            @endif
            <div class="mt-3">
                <table id="table_order" class="table  table-responsive table-hover">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Nama Produk</th>
                            <th>Jumlah Beli</th>
                            <th>Alamat</th>
                            <th>Kota</th>
                            <th>Kurir</th>
                            <th>Servis</th>
                            <th>Nama Pembeli</th>
                            <th>No. Hp</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan_request as $data)
                        <tr>
                            <td>{{$data->kode_transaksi}}</td>
                            <td>{{$data->nama_req}}</td>
                            <td>{{$data->jumlah_req}}</td>
                            <td>{{$data->alamat_req}}</td>
                            <td>{{$data->kota_req}}</td>
                            <td>{{$data->kurir}}</td>
                            <td>{{$data->service}}</td>
                            <td>{{$data->name}}</td>
                            <td>{{$data->no_hp}}</td>
                            <td>{{number_format($data->total_harga)}}</td>
                            <td>{{$data->status_penjualan_req}}</td>
                            <td>
                                @if($data->status_penjualan_req === 'dikirim')
                                <a href="/terjual/{{$data->id}}" class="badge badge-warning">Ganti Resi</a>
                                @endif

                                @if($data->status_penjualan_req ==='menunggu dikirim')
                                <a href="/terjual/{{$data->id}}" class="badge badge-success">Kirim Barang</a>
                                <form action="/terjual/{{$data->id}}" method="post">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="badge badge-danger border-0">Cancel Order</button>
                                </form>
                                @elseif($data->status_penjualan_req ==='diterima')
                                <label>Selesai</label>
                                @elseif($data->status_penjualan_req ==='dibatalkan')
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