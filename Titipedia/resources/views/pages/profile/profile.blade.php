@extends('layouts.fullview')
@section('content')
<div class="container mt-5">
    <div class="card  shadow-lg p-3 mb-5 bg-white rounded border-0">
        <div class="card-header bg-white">
            <h3>Profile</h3>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <div class="mt-2"></div>
                    @csrf
                    <div class="card border-0">
                        <div class="col-3">
                            <div class="mt-5">
                                <img src="{{ asset('photo_profile/'.Auth::user()->foto)}}" class="rounded float-left" width="200" height="200" alt="Responsive image">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card mt-3 border-0">
                        <div class="card-body">                              
                            <h3 class="card-title">{{Auth::user()->name}}</h3>
                            <p class="card-subtitle mb-2 text-muted">{{Auth::user()->username}}</p>
                            <p class="card-text"><small class="text-muted">Email: </small>{{Auth::user()->email}}</p> 
                            <p class="card-text"><small class="text-muted">Jenis Kelamin: </small>{{Auth::user()->jenis_kelamin}}</p>
                            <p class="card-text"><small class="text-muted">Tanggal Lahir: </small>{{Auth::user()->tanggal_lahir}}</p>
                            <p class="card-text"><small class="text-muted">Tempat Lahir: </small>{{Auth::user()->tempat_lahir}}</p>
                            <p class="card-text"><small class="text-muted">Alamat: </small>{{Auth::user()->alamat}}</p>
                            <p class="card-text"><small class="text-muted">Kota: </small>{{Auth::user()->kota}}</p>
                            <p class="card-text"><small class="text-muted">No Hp: </small>{{Auth::user()->no_hp}}</p>
                            <p class="card-text"><small class="text-muted">Saldo: </small>Rp. {{number_format(Auth::user()->saldo)}}</p>
                        </div>
                    </div>
                    <div class="form-group row pull-right d-inline p-2">
                        <div class="col-sm-10">
                            <a href="/home" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>
                    <div class="form-group row pull-right d-inline p-2">
                        <div class="col-sm-10">
                            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#editProfile">Edit Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editProfile" tabindex="-1" role="dialog" aria-labelledby="editProfile" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/edit-profile">
                        @csrf
                        <div class="form-group">
                            <label for="tarik">Name</label>
                        <input type="text" name="nama" class="form-control  @error('name') is-invalid @enderror" id="nama" value="{{Auth::user()->name}}">
                        </div>
                        <div class="form-group">
                            <label for="tarik">Jenis Kelamin</label>
                        <input type="text" name="jenis_kelamin" class="form-control  @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" value="{{Auth::user()->jenis_kelamin}}">
                        </div>
                        <div class="form-group">
                            <label for="tarik">Tempat Lahir</label>
                            <select class="custom-select @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir">
                                <?PHP
                                $data = json_decode($response, true);
                                for ($i = 0; $i < count($data['rajaongkir']['results']); $i++) {
                                    if (Auth::user()->tempat_lahir === $data['rajaongkir']['results'][$i]['city_name']) {
                                        echo "<option selected value='" . $data['rajaongkir']['results'][$i]['city_name'] . "'> " . $data['rajaongkir']['results'][$i]['city_name'] . "</option>";
                                    } else {
                                        echo "<option value='" . $data['rajaongkir']['results'][$i]['city_name'] . "'> " . $data['rajaongkir']['results'][$i]['city_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tarik">Tanggal Lahir</label>
                        <input type="text" name="tanggal_lahir" class="form-control  @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" value="{{Auth::user()->tanggal_lahir}}">
                        </div>
                        <div class="form-group">
                            <label for="tarik">Alamat</label>
                        <input type="text" name="alamat" class="form-control  @error('alamat') is-invalid @enderror" id="alamat" value="{{Auth::user()->alamat}}">
                        </div>
                        <div class="form-group">
                            <label for="tarik">Kota</label>
                            <select class="custom-select @error('kota') is-invalid @enderror" id="kota" name="kota">
                                <?PHP
                                $data = json_decode($response, true);
                                for ($i = 0; $i < count($data['rajaongkir']['results']); $i++) {
                                    if (Auth::user()->kota === $data['rajaongkir']['results'][$i]['city_name']) {
                                        echo "<option selected value='" . $data['rajaongkir']['results'][$i]['city_name'] . "'> " . $data['rajaongkir']['results'][$i]['city_name'] . "</option>";
                                    } else {
                                        echo "<option value='" . $data['rajaongkir']['results'][$i]['city_name'] . "'> " . $data['rajaongkir']['results'][$i]['city_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                       
                        </div>
                        <div class="form-group">
                            <label for="tarik">Nomer Handphone</label>
                        <input type="text" name="no_hp" class="form-control  @error('no_hp') is-invalid @enderror" id="no_hp" value="{{Auth::user()->no_hp}}">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>


@stop