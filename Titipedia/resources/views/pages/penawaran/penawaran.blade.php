@extends('layouts.fullview')
@section('content')
<div class="container mt-4 shadow-lg p-3 mb-5 bg-white rounded border-0">
    <div class="card border-0">
        <div class="row card-header bg-white">
            <div class="col-6">
                <h3>Data Request</h3>
            </div>
            @if($request->id_user !== Auth::user()->id)
            <div class="col-6">
                <a href="/pesan/{{$request->id_user}}" class="btn float-right btn-success border-0"
                    style="background-color: #65587f;">Chat Peminta</a>
            </div>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div id="carouselExampleControls" class="carousel slide mt-4" data-ride="carousel">
                        <div class="carousel-inner">
                            @if(count($gambars) > 0)
                            @for($i = 0;$i < count($gambars); $i++) @if($i===0) <div class="carousel-item active">
                                <img src="{{asset('request_images/'.$gambars[$i]->url)}}" class="d-block w-100"
                                    alt="...">
                        </div>
                    </div>
                    @else
                    <div class="carousel-item">
                        <img src="{{asset('request_images/'.$gambars[$i]->url)}}" class="d-block w-100" alt="...">
                    </div>
                    @endif

                    @endfor
                    @endif
                </div>

                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>


            </div>
            <div class="col">
                <div class="card-body">
                    <h3 class="card-title">{{$request->nama_req}}</h3>
                    <p class="card-subtitle mb-2 text-muted">{{$kategori}}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" id="keterangan">
                        <small class="text-muted">
                            Nama:
                        </small>{{$user}}
                    </li>

                    <li class="list-group-item" id="keterangan">
                        <small class="text-muted">
                            Keterangan:
                        </small>{{$request->keterangan}}
                    </li>

                    <li class="list-group-item">
                        <small class="text-muted">
                            Jumlah Request:
                        </small>{{$request->jumlah_req}}
                    </li>

                    <li class="list-group-item">
                        <small class="text-muted">
                            Status:
                        </small>{{$request->status_req}}
                    </li>

                    <li class="list-group-item" id="asal">
                        <small class="text-muted">
                            Alamat:
                        </small>{{$request->alamat_req}}</li>
                    <li class="list-group-item" id="asal">
                        <small class="text-muted">
                            Tujuan Pengiriman:
                        </small>{{$request->kota_req}}</li>
                </ul>

            </div>
        </div>
    </div>
    <div class="card-body">
        @if (session('status')==="Data Berhasil Ditambahkan!")
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @elseif (session('status')==="Data Penawaran Berhasil Dihapus!")
        <div class="alert alert-danger">
            {{ session('status') }}
        </div>
        @endif
        <div class="row">
            @if(!$isAjukanPenawaran)
            <div class="col-md-4">
                <a href="/penawaran/{{$request->id}}/create" class="btn btn-success" style="background-color: #65587f; border: hidden">Tambah Penawaran</a>
            </div>
            @endif
        </div>
        <div class="mt-3">
            <table id="table_product" class="table table-responsive table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Harga Produk</th>
                        <th>Harga Jasa</th>
                        <th>Alamat</th>
                        <th>Kota</th>
                        <th>Status</th>
                        <th>Penawar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penawarans as $data)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>Rp. {{number_format($data->harga_produk_penawaran)}}</td>
                        <td>Rp. {{number_format($data->harga_jasa_penawaran)}}</td>
                        <td>{{$data->alamat_penawaran}}</td>
                        <td>{{$data->kota_penawaran}}</td>
                        <td>{{$data->status}}</td>
                        <td>{{$data->name}}</td>
                        <td>
                            @if($data->id_penawar === Auth::user()->id)
                            <form action="/penawaran/{{$data->id}}" method="post">
                                @method('delete')
                                @csrf
                                <button type="submit" class="badge badge-danger">DELETE</button>
                            </form>
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