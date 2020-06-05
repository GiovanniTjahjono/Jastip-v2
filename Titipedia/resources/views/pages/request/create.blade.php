@extends('layouts.produkView')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Tambah Data Request</h3>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" action="/req">
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
                    <label for="id_kategori" class="col-sm-2 col-form-label">Kategori Produk</label>
                    <div class="col-sm-10">
                        <select class="custom-select @error('id_kategori') is-invalid @enderror" id="id_kategori" name="id_kategori" value="{{old('jenis_produk')}}">
                            @foreach($kategoris as $key => $data)
                            <option value="{{$data->id}}">{{$data->nama_kategori}}</option>
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

                <label for="gambar" class="col-sm-2 col-form-label">Pilih Foto </label>
                <div class="input-group control-group increment">
                    <input type="file" name="gambar[]" class="form-control" id="gambar">
                    <div class="input-group-btn">
                        <button id="btnAdd" class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>Tambah Foto</button>
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
                @error('gambar')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
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
                        <button type="submit" class="btn btn-success" style="background-color: #65587f; border: hidden">Tambah Data</button>
                    </div>
                </div>
            </form>
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#btnAdd").click(function() {
                        var html = $(".clone").html();
                        $(".increment").after(html);
                    });
                    $("body").on("click", "#btnRemove", function() {
                        $(this).parents(".control-group").remove();
                    });
                });
            </script>
        </div>
    </div>
</div>
@stop