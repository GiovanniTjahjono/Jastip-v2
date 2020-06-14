@extends('layouts.produkView')
@section('content')
<div class="container mt-4 shadow-lg p-3 mb-5 bg-white rounded border-0">
    <div class="card border-0">
        <div class="card-header bg-white">
            <h3>Ubah Data Request</h3>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" action="/req/{{$req->id}}">
                @method('PATCH')
                @csrf
                <div class="form-group row">
                    <label for="nama_req" class="col-sm-2 col-form-label">Nama Request</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('nama_req') is-invalid @enderror" id="nama_req" name="nama_req" value="{{$req->nama_req}}">
                        @error('nama_req')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="id_kategori" class="col-sm-2 col-form-label">Kategori Produk</label>
                    <div class="col-sm-10">
                        <select class="custom-select @error('id_kategori') is-invalid @enderror" id="id_kategori" name="id_kategori" value="{{old('jenis_produk')}}">
                            @foreach($kategoris as $data)
                            @if($data->id === $req->id_kategori)
                            <option selected value="{{$data->id}}">{{$data->nama_kategori}}</option>
                            @else
                            <option value="{{$data->id}}">{{$data->nama_kategori}}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('id_kategori')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jumlah_req" class="col-sm-2 col-form-label">Jumlah</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('jumlah_req') is-invalid @enderror" id="jumlah_req" name="jumlah_req" value="{{$req->jumlah_req}}">
                        @error('jumlah_req')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="alamat_req" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('alamat_req') is-invalid @enderror" id="alamat_req" name="alamat_req" value="{{$req->alamat_req}}">
                        @error('alamat_req')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kota_req" class="col-sm-2 col-form-label">Kota</label>
                    <div class="col-sm-10">
                        <select class="custom-select @error('kota_req') is-invalid @enderror" id="kota_req" name="kota_req">
                            <?PHP
                            $data = json_decode($response, true);
                            for ($i = 0; $i < count($data['rajaongkir']['results']); $i++) {
                                echo "<option value='" . $data['rajaongkir']['results'][$i]['city_id'] . "'> " . $data['rajaongkir']['results'][$i]['city_name'] . "</option>";
                                if ($req->kota_req == $data['rajaongkir']['results'][$i]['city_name']) {
                                    echo "<option selected value='" . $data['rajaongkir']['results'][$i]['city_name'] . "'> " . $data['rajaongkir']['results'][$i]['city_name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="status_req" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <select class="custom-select @error('status_req') is-invalid @enderror" id="status_req" name="status_req" value="{{$req->status_req}}">
                            @if($req->status_req==="aktif")
                            <option selected value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
                            @else
                            <option value="aktif">Aktif</option>
                            <option selected value="tidak aktif">Tidak Aktif</option>
                            @endif
                        </select>
                        @error('status_req')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                    <div class="col-sm-10">
                        <div class="form-group">
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"> <?= $req->keterangan; ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-12">
                    <a href="/edit-gambar-request/{{$req->id}}" class="btn btn-success float-right">Edit Gambar</a>
                    </div>
                </div>
                <div class="form-group row">
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Gambar</th>
                            </tr>
                        </thead>
                        <tbody >
                            @foreach($gambars as $key => $data)
                            <tr>
                                <th scope="row">{{$key + 1}}</th>
                                <td><img class="rounded img-thumbnail" style="max-width: 150px;"
                                        src="{{asset('request_images/' . $data->url)}}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10">
                        <input type="text" hidden name="id_user" value="{{Auth::user()->id}}">
                    </div>
                </div>
                <div class="form-group row pull-right d-inline p-2">
                    <div class="col-sm-10">
                        <a href="/req" class="btn btn-primary">Kembali</a>
                    </div>
                </div>
                <div class="form-group row pull-right p-2">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-success" style="background-color: #65587f; border: hidden">Ubah Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop