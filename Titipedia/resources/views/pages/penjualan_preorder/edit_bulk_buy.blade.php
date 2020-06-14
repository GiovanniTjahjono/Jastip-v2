@extends('layouts.produkView')
@section('content')
<div class="container mt-4 shadow-lg p-3 mb-5 bg-white rounded border-0">
    <div class="card border-0">
        <div class="card-header bg-white">
            <h3>Pengiriman</h3>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" action="/terjual/{{$penjualanPreorder->id}}">
                @method('PATCH')
                @csrf
                <div class="form-group row">
                    <label for="kode_transaksi" class="col-sm-2 col-form-label">Nomer Transaksi</label>
                    <div class="col-sm-10">
                        <input type="text" disabled class="form-control @error('nomer_resi') is-invalid @enderror" id="kode_transaksi" name="kode_transaksi" value="{{$penjualanPreorder->kode_transaksi}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nomer_resi" class="col-sm-2 col-form-label">Masukan Resi Pengiriman</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('nomer_resi') is-invalid @enderror" id="nomer_resi" name="nomer_resi">
                        @error('nomer_resi')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row pull-right p-2">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-success" style="background-color: #65587f; border: hidden">Update Data Pengiriman</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@stop