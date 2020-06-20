@extends('layouts.produkView')
@section('content')
<div class="container mt-4 shadow-lg p-3 mb-5 bg-white rounded border-0">
    <div class="card border-0">
        <div class="card-header bg-white">
            <h3>Data Produk</h3>
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
                    <a href="produk/create" class="btn btn-success" style="background-color: #65587f; border: hidden">Tambah Data</a>
                </div>
            </div>
            <div class="mt-3">
                <table id="table_product" class="table table-responsive table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Batas Produk</th>
                            <th>Harga Jasa</th>
                            <th>Harga Produk</th>
                            <th>Berat</th>
                            <th>Estimasi Pengiriman</th>
                            <th>Asal Negara</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produks as $data)
                        @if($data->status_produk === 'tidak aktif')
                        <tr class="bg-warning">
                        @else
                        <tr>
                        @endif
                            <td>{{$loop->iteration}}</td>
                            <td>{{$data->nama}}</td>
                            <td>{{$data->stok}}</td>
                            <td>Rp. {{number_format($data->harga_jasa)}}</td>
                            <td>Rp. {{number_format($data->harga_produk)}}</td>
                            <td>{{$data->berat}} kg</td>
                            <td>{{date_format(date_create($data->estimasi_pengiriman),"d-F-Y")}}</td>
                            <td>{{$data->asal_negara}}</td>
                            <td>{{$data->status_produk}}</td>
                            <td>
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