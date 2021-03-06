@extends('layouts.default')
@section('content')
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
</div>
@endif
<div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
        <div class="carousel-item active">
            <img class="d-block img-fluid" src="{{asset('images/image_1.svg')}}" alt="First slide">
        </div>
        <div class="carousel-item">
            <img class="d-block img-fluid" src="{{asset('images/image_2.svg')}}" alt="Second slide">
        </div>
        <div class="carousel-item">
            <img class="d-block img-fluid" src="{{asset('images/image_3.svg')}}" alt="Third slide">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<h3>Produk Terbaru</h3>
<div class="row">
    @foreach($produks as $data)
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card h-100">
            <a href="#"><img class="w-100 h-100 card-img-top mx-auto d-block" src="{{asset('produk_images/'.$data->url)}}" alt=""></a>
            <div class="card-body">
                <small class="font-weight-bold" style="color: #65587f;">{{$data->nama}}</small>
            </div>
            <div class="card-footer bg-white">
                <h6>Rp. {{number_format($data->harga_produk)}}</h6>
                <div class="row">
                    <div class="col">
                        <small class="text-muted">stok {{$data->stok}}</small>
                    </div>
                    <div class="col">
                        <div class="text-right">
                            @if(Auth::check())
                            @if(Auth::User()->id === $data->id_user)
                            <a href="/produk/{{$data->id}}/edit" style="border: hidden" class="btn btn-success btn-primary">Edit</a>
                            @else
                            <a href="/order/{{$data->id}}" style="background-color: #65587f; border: hidden" class="btn btn-primary">Order</a>
                            @endif
                            @else
                            <a href="/order/{{$data->id}}" style="background-color: #65587f; border: hidden" class="btn btn-primary">Order</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<h3>Produk Bulk Buy Terbaru</h3>
<div class="row">
    @foreach($bulkbuys as $data)
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card h-100">
            <a href="#"><img class="w-100 h-100 card-img-top mx-auto d-block" src="{{asset('produk_bulk_buy_images/'.$data->url)}}" alt=""></a>
            <div class="card-body">
                <small class="font-weight-bold" style="color: #65587f;">{{$data->nama}}</small>
            </div>
            <div class="card-footer bg-white">
                <h6>Rp. {{number_format($data->harga_produk)}}</h6>
                <div class="row">
                    <div class="col">
                        <small class="text-muted">Jumlah Target {{$data->jumlah_target}}</small>
                    </div>
                    <div class="col">
                        <div class="text-right">
                            @if(Auth::check())
                            @if(Auth::User()->id === $data->id_user)
                            <a href="/produk-bulk-buy/{{$data->id}}/edit" style="border: hidden" class="btn btn-success btn-primary">Edit</a>
                            @else
                            <a href="/bulkbuy/{{$data->id}}" style="background-color: #65587f; border: hidden" class="btn btn-primary">Order</a>
                            @endif
                            @else
                            <a href="/bulkbuy/{{$data->id}}" style="background-color: #65587f; border: hidden" class="btn btn-primary">Order</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<h3>Request Terbaru</h3>
<div class="row">
    @foreach($requests as $data)
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card h-100">
            <a href="#"><img class="w-100 h-100 card-img-top mx-auto d-block" src="{{asset('request_images/'.$data->url)}}" alt=""></a>
            <div class="card-body">
                <small class="font-weight-bold" style="color: #65587f;">{{$data->nama_req}}</small>
            </div>
            <div class="card-footer bg-white">
                <div class="text-right">
                    <a href="/penawaran/{{$data->id}}" style="background-color: #65587f;" class="btn btn-primary border-0">Ajukan Penawaran</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@stop