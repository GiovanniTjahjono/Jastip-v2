@extends('layouts.produkView')
@section('content')
<div class="container mt-4 shadow-lg p-3 mb-5 bg-white rounded border-0">
    <div class="card border-0">
        <div class="card-header bg-white">
            <h3>Ubah Gambar</h3>
        </div>
        <div class="card-body">
            @if (session('status') === "Berhasil Dihapus!")
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @elseif (session('status')==="Gagal dihapus, produk setidaknya harus memiliki 1 gambar!")
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
            @endif
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody >
                        @foreach($gambars as $key => $data)
                        <tr>
                            <th scope="row">{{$key + 1}}</th>
                            <td><img class="rounded img-thumbnail" style="max-width: 150px;"
                                    src="{{asset('produk_images/' . $data->url)}}"></td>
                            <td>
                            <form action="/gambar/{{$data->id}}/{{$data->id_produk}}" method="post">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="badge badge-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <form method="post" enctype="multipart/form-data" action="/tambah_gambar">
                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <input type="text" hidden name="id_produk" value="{{$gambars[0]->id_produk}}">
                        </div>
                    </div>
                    <label for="gambar" class="col-sm-2 col-form-label">Pilih Foto </label>
                    <div class="input-group control-group increment">
                        <input type="file" name="gambar[]" class="form-control" id="gambar">
                        <div class="input-group-btn">
                            <button id="btnAdd" class="btn btn-success" type="button"><i
                                    class="glyphicon glyphicon-plus"></i>Tambah Foto</button>
                        </div>
                    </div>
                    <div class="clone hide">
                        <div class="control-group input-group" style="margin-top:10px">
                            <input type="file" name="gambar[]" class="form-control" id="gambar">
                            <div class="input-group-btn">
                                <button id="btnRemove" class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>
                                    Hapus</button>
                            </div>
                        </div>
                    </div>
                <div class="form-group row pull-right d-inline p-2">
                    <div class="col-sm-10">
                        <a href="/produk/{{$gambars[0]->id_produk}}/edit" class="btn btn-primary">Kembali</a>
                    </div>
                </div>
                <div class="form-group row pull-right p-2">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-success"
                            style="background-color: #65587f; border: hidden">Ubah Data</button>
                    </div>
                </div>
                </form>
        </div>
    </div>
</div>
@stop