@extends('layouts.produkview')
@section('content')
<div class="container mt-4 shadow-lg p-3 mb-5 bg-white rounded border-0">
    <div class="card border-0">
        <div class="card-header bg-white">
            <h3>Data Produk Bulk Buy</h3>
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
            @elseif (session('status')==="Data Produk Berhasil Dihapus!")
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
            @endif
            <div class="row">
                <div class="col-md-4">
                    <a href="produk-bulk-buy/create" class="btn btn-success" style="background-color: #65587f; border: hidden">Tambah Data</a>
                </div>
            </div>
            <div class="mt-3">
                <table id="table_product" class="table table-responsive table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Jumlah Target</th>
                            <th>Harga Jasa</th>
                            <th>Harga Produk</th>
                            <th>Berat</th>
                            <th>Asal Pengiriman</th>
                            <th>Batas Waktu</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produkBulkBuys as $data)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$data->nama}}</td>
                            <td>{{$data->jumlah_target}}</td>
                            <td>{{$data->harga_jasa}}</td>
                            <td>{{$data->harga_produk}}</td>
                            <td>{{$data->berat}}</td>
                            <td>{{$data->asal_pengiriman}}</td>
                            <td>{{$data->batas_waktu}}</td>
                            <td>{{$data->status_bulk}}</td>
                            <td>{{$data->keterangan}}</td>
                            <td><a href="/produk/{{$data->id}}" class="badge badge-primary">detail</a>
                                <a href="/produk/{{$data->id}}/edit" class="badge badge-success">edit</a>
                                <!-- <a href="" class="badge badge-danger">delete</a> -->
                                <form action="/produk/{{$data->id}}" method="post">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="badge badge-danger">Delete</button>
                                </form>
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