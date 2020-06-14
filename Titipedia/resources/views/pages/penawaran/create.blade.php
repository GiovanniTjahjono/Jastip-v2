@extends('layouts.fullview')
@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Tambah Penawaran</h3>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" action="/penawaran">
                @csrf
                <div class="form-group row">
                    <label for="harga_produk" class="col-sm-2 col-form-label">Harga Produk</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control @error('harga_produk') is-invalid @enderror" id="harga_produk" name="harga_produk" value="{{old('harga_produk')}}">
                        @error('harga_produk')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="harga_jasa" class="col-sm-2 col-form-label">Harga Jasa</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control @error('harga_jasa') is-invalid @enderror" id="harga_jasa" name="harga_jasa" value="{{old('harga_jasa')}}">
                        @error('harga_jasa')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="alamat_penawar" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('alamat_penawar') is-invalid @enderror" id="alamat_penawar" name="alamat_penawar" value="{{Auth::user()->alamat}}">
                        @error('alamat_penawar')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kota_penawar" class="col-sm-2 col-form-label">Kota</label>
                    <div class="col-sm-10">
                        <select class="custom-select @error('kota_penawar') is-invalid @enderror" id="kota_penawar" name="kota_penawar">
                            <?PHP
                            $data = json_decode($response, true);
                            for ($i = 0; $i < count($data['rajaongkir']['results']); $i++) {
                                echo "<option value='" . $data['rajaongkir']['results'][$i]['city_name'] . "'> " . $data['rajaongkir']['results'][$i]['city_name'] . "</option>";
                                if (Auth::user()->kota == $data['rajaongkir']['results'][$i]['city_name']) {
                                    echo "<option selected value='" . $data['rajaongkir']['results'][$i]['city_name'] . "'> " . $data['rajaongkir']['results'][$i]['city_name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <input type="text" hidden name="id_user" value="{{Auth::user()->id}}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <input type="text" hidden name="id_request" value="{{$request->id}}">
                    </div>
                </div>
                <div class="form-group row pull-right d-inline p-2">
                    <div class="col-sm-10">
                    <a href="/penawaran/{{$request->id}}" class="btn btn-primary">Kembali</a>
                    </div>
                </div>
                <div class="form-group row pull-right p-2">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-success" style="background-color: #65587f; border: hidden">Tambah Penawaran</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop