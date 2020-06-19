@extends('layouts.pesanview')
@section('content')
<!-- =========================================================== -->
<div class="container mt-4 direct-chat direct-chat-success">
    <div class="card  shadow-lg p-3 mb-5 bg-white rounded border-0">
        <div class="card-header bg-white">
            <h3><?= $user2->first()->name; ?></h3>
        </div>
        <div class="card-body">
            <div class="box-body">
                <div class="direct-chat-messages">
                    @foreach($pesan as $data)
                    @if ($data->id_pengirim === Auth::user()->id)
                    <div class="direct-chat-msg">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left"><?= $user1->first()->name; ?></span>
                            <span class="direct-chat-timestamp pull-right">{{$data->waktu_kirim}}</span>
                        </div>
                        <img class="direct-chat-img" src="{{ asset('photo_profile/'.Auth::user()->foto)}}" alt="Message User Image"><!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                            {{$data->isi_pesan}}
                        </div>
                    </div>
                    @else
                    <div class="direct-chat-msg right">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-right"><?= $user2->first()->name; ?></span>
                            <span class="direct-chat-timestamp pull-left">{{$data->waktu_kirim}}</span>
                        </div>
                        <img class="direct-chat-img" src="{{ asset('photo_profile/'.$data->foto)}}" alt="Message User Image"><!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                            {{$data->isi_pesan}}
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <form action="/kirim" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="isi_pesan" placeholder="Ketik ..." class="form-control" required>
                        <input type="hidden" name="id_penerima" value="{{$user2->first()->id}}" />
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary btn-flat">Kirim</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop