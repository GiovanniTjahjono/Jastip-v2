@extends('layouts.produkView')
@section('content')
<div class="container mt-4 shadow-lg p-3 mb-5 bg-white rounded border-0">
    <div class="card border-0">
        <div class="card-header bg-white">
            <h3>Data Request</h3>
        </div>
        <div class="card-body">
            @if (session('status')==="Data Berhasil Ditambahkan!")
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @elseif (session('status')==="Data Request Order Berhasil Diubah!")
            <div class="alert alert-primary">
                {{ session('status') }}
            </div>
            @elseif (session('status')==="Data Request Order Berhasil Dihapus!")
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
            @endif
            <div class="row">
                <div class="col-md-4">
                    <a href="req/create" class="btn btn-success" style="background-color: #65587f; border: hidden">Tambah Data Request</a>
                </div>
            </div>
            <div class="mt-3">
                <table id="table_product" class="table table-responsive w-100 table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Alamat</th>
                            <th>Kota</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($request as $data)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$data->nama_req}}</td>
                            <td>{{$data->jumlah_req}}</td>
                            <td>{{$data->alamat_req}}</td>
                            <td>{{$data->kota_req}}</td>
                            <td>{{$data->status_req}}</td>
                            <td>{{$data->keterangan}}</td>
                            @if($data->status_req === "aktif")
                            <td><a href="/cek-penawaran/{{$data->id}}" class="badge badge-primary">cek penawaran</a>
                                <a href="/req/{{$data->id}}/edit" class="badge badge-success">edit</a>
                                <!-- <a href="" class="badge badge-danger">delete</a> -->
                                <form action="/req/{{$data->id}}" method="post">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="badge badge-danger">Delete</button>
                                </form>
                            </td>
                            @else
                            <td><a href="pembelian-request/daftar_pembelian_request/{{Auth::user()->id}}" class="badge badge-primary">cek pembelian</a>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop