@extends('layouts.fullview')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Tambah Data Request</h3>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" action="/request">
                @csrf
                <div class="form-group row">
                    <label for="nama_req" class="col-sm-2 col-form-label">Nama Request</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('nama_req') is-invalid @enderror" id="nama_req" name="nama_req" value="{{old('nama_req')}}">
                        @error('nama_req')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jumlah_req" class="col-sm-2 col-form-label">Jumlah</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('jumlah_req') is-invalid @enderror" id="jumlah_req" name="jumlah_req" value="{{old('jumlah_req')}}">
                        @error('jumlah_req')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="alamat_req" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('alamat_req') is-invalid @enderror" id="alamat_req" name="alamat_req" value="{{Auth::user()->alamat}}">
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
                                if (Auth::user()->kota == $data['rajaongkir']['results'][$i]['city_name']) {
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
                        <select class="custom-select @error('status_req') is-invalid @enderror" id="status_req" name="status_req" value="{{old('status_req')}}">
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
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
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="gambar" class="col-sm-2 col-form-label">Pilih Banner</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control-file @error('gambar') is-invalid @enderror" id="gambar" name="gambar" value="{{old('gambar')}}">
                        @error('gambar')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <input type="text" hidden name="id_user" value="{{Auth::user()->id}}">
                    </div>
                </div>
                <div class="form-group row pull-right d-inline p-2">
                    <div class="col-sm-10">
                        <a href="/request" class="btn btn-primary">Kembali</a>
                    </div>
                </div>
                <div class="form-group row pull-right p-2">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-success" style="background-color: #65587f; border: hidden">Tambah Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop