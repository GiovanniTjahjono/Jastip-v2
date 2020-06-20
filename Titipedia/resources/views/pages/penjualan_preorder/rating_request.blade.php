@extends('layouts.produkView')
@section('content')
<div class="container mt-4 shadow-lg p-3 mb-5 bg-white rounded border-0">
    <div class="card border-0">
        <div class="card-header bg-white">
            <h3>Rating dan Review</h3>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" action="/pembelian-penawaran-rating/{{$penjualanRequest->id}}">
                @method('PATCH')
                @csrf
                <div class="form-group row">
                    <label for="rating" class="col-sm-2 col-form-label">Rating</label>

                </div>
                <div class="form-group row">
                    <label for="review" class="col-sm-2 col-form-label">Review</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('review') is-invalid @enderror" id="review" name="review">
                        @error('review')
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