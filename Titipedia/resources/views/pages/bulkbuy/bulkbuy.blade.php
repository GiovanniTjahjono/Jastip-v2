@extends('layouts.fullview')
@section('content')
<div class="container mt-5">
    <div class="card  shadow-lg p-3 mb-5 bg-white rounded border-0">
        <div class="card-header bg-white">
            <div class="row">
                <div class="col-6">
                    <h3>Order</h3>
                </div>
                @if($produkBulkBuy->id_user !== Auth::user()->id)
                <div class="col-6">
                    <a href="/pesan/{{$produkBulkBuy->id_user}}" class="btn float-right btn-success border-0" style="background-color: #65587f;">Chat Penjual</a>
                </div>
                @endif
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div id="carouselExampleControls" class="carousel slide mt-4" data-ride="carousel">
                        <div class="carousel-inner">
                            <!--Harus ada-->
                            @if(count($gambar) > 0)
                            @for($i = 0;$i < count($gambar); $i++) @if($i===0) <div class="carousel-item active">
                                <img src="{{asset('produk_bulk_buy_images/'.$gambar[$i]->url)}}" class="d-block w-100" alt="...">
                        </div>
                        @else
                        <div class="carousel-item">
                            <img src="{{asset('produk_bulk_buy_images/'.$gambar[$i]->url)}}" class="d-block w-100" alt="...">
                        </div>
                        @endif

                        @endfor
                        @endif
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                <div class="overflow-auto h-50 mt-5">
                    @foreach($penjualanRatingReview as $ratingreview)
                    <div class="row">
                        <div class="col-2">
                            <img class="direct-chat-img" src="{{ asset('photo_profile/'.$ratingreview->foto)}}">
                        </div>
                        <div class="col-10">
                            <?php
                                $bintangKosong = 5 - $ratingreview->rating;
                                for($i = 0; $i < $ratingreview->rating; $i++) {
                                    echo '<small><i class="fa fa-star"></i></small>';
                                }
                                for($i = 0; $i < $bintangKosong; $i++) {
                                    echo '<small><i class="fa fa-star-o"></i></small>';
                                }
                            ?>
                            <br>
                            <small for="">{{$ratingreview->review}}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col">
                <div class="card mt-5 border-0">
                    <div class="card-body">
                        @if (session('status') === "Jumlah Yang Anda Beli Melebihi Batas!")
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @elseif (session('status') === "Saldo Anda Tidak Cukup!")
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif
                        <h3 class="card-title">{{$produkBulkBuy->nama}}</h3>
                        <p class="card-subtitle mb-2 text-muted">{{$kategori[0]->nama_kategori}}</p>
                        <p class="card-text">{{$produkBulkBuy->keterangan}}</p>
                    </div>
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item" id="harga_produk" value="{{$produkBulkBuy->harga_produk}}"><small class="text-muted">Harga produk:
                            </small>Rp.{{$produkBulkBuy->harga_produk}}</li>
                        <li class="list-group-item" id="harga_jasa" value="{{$produkBulkBuy->harga_jasa}}"><small class="text-muted">Harga jasa:
                            </small>Rp.{{$produkBulkBuy->harga_jasa}}</li>
                        <li class="list-group-item"><small class="text-muted">Jumlah Target: </small>{{$produkBulkBuy->jumlah_target}}</li>
                        <li class="list-group-item"><small class="text-muted">Asal Negara: </small>{{$produkBulkBuy->asal_negara}}</li>
                        <li class="list-group-item"><small class="text-muted">Berat: </small>{{$produkBulkBuy->berat}} Kg
                        </li>
                        <li class="list-group-item" id="asal" value="{{$produkBulkBuy->asal_pengiriman}}"><small class="text-muted">Asal Pengiriman:
                            </small>{{$produkBulkBuy->asal_pengiriman}}</li>
                    </ul>

                    <div class="card-body">
                        <form method="POST" action="/bulkbuy/confirm">
                            @csrf
                            <input type="text" hidden class="form-control" id="id_produk" name="id_produk" value="{{$produkBulkBuy->id}}">
                            <input type="text" hidden class="form-control" id="id_pembeli" name="id_pembeli" value="{{Auth::user()->id}}">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Jumlah Pembelian</label>
                                <input type="number" class="form-control" id="jumlah_target" name="jumlah_target" value="1" min="1" max="{{$produkBulkBuy->jumlah_target}}">

                                <input type="text" hidden class="form-control" id="id_produk" name="id_produk" value="{{$produkBulkBuy->id}}">
                                <input type="text" hidden class="form-control" id="id_pembeli" name="id_pembeli" value="{{Auth::user()->id}}">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Pembeli</label>
                                    <input type="text" class="form-control" id="nama" name="nama_pembeli" value="{{Auth::user()->name}}" aria-describedby="emailHelp">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Alamat Pengiriman</label>
                                    <input type="text" class="form-control" id="alamat_pengiriman" name="alamat_pengiriman" value="{{Auth::user()->alamat}}" aria-describedby="emailHelp">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Kota Pengiriman</label>
                                    <select class="form-control" id="kab_id" name="kab_id">
                                        <option selected>Pilih Kota</option>;
                                        <?PHP
                                        $data = json_decode($response, true);
                                        for ($i = 0; $i < count($data['rajaongkir']['results']); $i++) {
                                            echo "<option value='" . $data['rajaongkir']['results'][$i]['city_id'] . "'> " . $data['rajaongkir']['results'][$i]['city_name'] . "</option>";
                                        }
                                        ?>
                                    </select> <input type="text" hidden class="form-control" id="nama_kota" name="nama_kota">
                                </div>
                                <div class="form-group">
                                    <label>Tipe Pengiriman</label>
                                    <select class="form-control" id="tipeService" name="tipeService" required>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Total</label>
                                    <h2 id="totalHarga"></h2>

                                </div>
                                <button type="submit" class="btn btn-success d-block">Beli</button>
                        </form>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        var harga_produk = $('#harga_produk').val();
        var harga_jasa = $('#harga_jasa').val();
        var harga_total = harga_produk + harga_jasa;

        var harga_total_plus_ongkir = 0

        var asal = $('#asal').val();
        localStorage.setItem('harga', harga_total);
        localStorage.setItem('hargaTerakhir', 0);
        var nama_kota = $('#kab_id').find(":selected").text();
        $('#nama_kota').val(nama_kota);

        $('#kab_id').on('change', function(e) {
            //Reset local storage harga
            localStorage.setItem('harga', harga_total);
            localStorage.setItem('hargaTerakhir', 0);
            //Ambil id kabupaten untuk query raja ongkir
            var nama_kota = $('#kab_id').find(":selected").text();
            $('#nama_kota').val(nama_kota);

            var name = $('#kab_id').val();
            var name2 = "{{$produkBulkBuy->asal_pengiriman}}";
            $.post("{{url('/order/get_price')}}", {
                    'kab_id': name,
                    'asal': "{{$produkBulkBuy->asal_pengiriman}}",
                    '_token': "{{csrf_token()}}"
                },
                function(data) {
                    var obj = JSON.parse(data);
                    $('#tipeService').html('');
                    // Mendapatkan harga ongkir
                    var biaya_ongkir = obj.rajaongkir.results[0].costs[0].cost[0].value;
                    for (var i = 0; i < Object.keys(obj.rajaongkir.results[0].costs).length; i++) {
                        $('#tipeService').append('<option value="' + obj.rajaongkir.results[0].costs[i].cost[0].value + ',' + obj.rajaongkir.results[0].costs[i].service + '">' + obj.rajaongkir.results[0].costs[i].service + ' - ' + obj.rajaongkir.results[0].costs[i].description.toLowerCase() + ' - Rp. ' + obj.rajaongkir.results[0].costs[i].cost[0].value + '</option>');
                    }
                    // Simpan biaya orngkir ke local storage
                    localStorage.setItem('biayaOngkir', biaya_ongkir);
                    var hargaSekarang = localStorage.getItem('harga');
                    // Kalkulasi harga produk + jasa + ongkir
                    var hargaJadi = Number(hargaSekarang) + Number(obj.rajaongkir.results[0].costs[0].cost[0].value)
                    // Simpan hasilnya di harga
                    localStorage.setItem('harga', hargaJadi);
                    // Tampilkan d total harga
                    $('#totalHarga').html('');
                    $('#totalHarga').append('<h3>Rp.' + localStorage.getItem('harga') + '</h3><input type="hidden" class="form-control" name="hargaTotalnya" id="totalHargaH3" value="' + localStorage.getItem('harga') + '">');

                });
        });
        $('#tipeService').on('change', function(e) {
            // Ambil biaya ongkir lama
            var hargaOngkirLama = localStorage.getItem('biayaOngkir');
            // Ambil total harga lama 
            var hargaSekarang = localStorage.getItem('harga');
            // Kurangi harga lama dan ongkir lama agar mendapatkan harga produk dan jasa saja
            var hargaSementara = Number(hargaSekarang) - Number(hargaOngkirLama);
            // Ambil value dari tipe servis
            var biayaOngkirBaru1 = $('#tipeService').val();
            var biayaOngkirBaru = biayaOngkirBaru1.split(",")[0];
            // simpan harga ongkir baru ke biaya ongkir di local storage
            localStorage.setItem('biayaOngkir', biayaOngkirBaru);
            // jumlahkan harga jasa + produk + ongkir baru
            var hargaBaru = Number(biayaOngkirBaru) + Number(hargaSementara);
            // simpan kembali hasilnya ke local storage
            localStorage.setItem('harga', hargaBaru);
            // tampilkan
            $('#totalHarga').html('');
            $('#totalHarga').append('<h3>Rp.' + localStorage.getItem('harga') + '</h3><input type="hidden" class="form-control" name="hargaTotalnya" id="totalHargaH3" value="' + localStorage.getItem('harga') + '">');
        });
    });
</script>
@stop