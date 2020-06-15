@extends('layouts.fullview')
@section('content')
<div class="container mt-5">
    <div class="card  shadow-lg p-3 mb-5 bg-white rounded border-0">
        <div class="card-header bg-white">
            <div class="row">
                <div class="col-6">
                    <h3>Order</h3>
                </div>
            </div>
        </div>
        <div class="container">

            <div class="col">
                <div class="card border-0">
                    <div class="card-body">

                        <ul class="list-group list-group-flush">

                            <li class="list-group-item" id="harga_produk" value="{{$penawaran->harga_produk_penawaran}}"
                                hidden><small class="text-muted" hidden>Harga produk:
                                </small>Rp.{{number_format($penawaran->harga_produk_penawaran)}}</li>
                            <li class="list-group-item" id="harga_jasa" value="{{$penawaran->harga_jasa_penawaran}}"
                                hidden><small class="text-muted" hidden>Harga jasa:
                                </small>Rp.{{number_format($penawaran->harga_jasa_penawaran)}}</li>


                            <li class="list-group-item" id="asal" value="{{$penawaran->kota_penawaran}}" hidden><small
                                    class="text-muted" hidden>Asal Pengiriman:
                                </small>{{$penawaran->kota_penawaran}}</li>
                        </ul>
                        <form action="/pembelian-penawaran" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" id="id_penawaran" name="id_penawaran" class="form-control" value="{{$penawaran->id}}" hidden>
                            </div>
                            <div class="form-group">
                                <label>Nama Penawar</label>
                                <input type="text" class="form-control" value="{{$namaPenawar->name}}" disabled>
                            </div>
                            <div class="form-group">
                                <label>Harga Produk</label>
                                <input type="text" class="form-control" id="harga_produk"
                                    value="Rp. {{number_format($penawaran->harga_produk_penawaran)}}" disabled>
                            </div>
                            <div class="form-group">
                                <label>Harga Jasa</label>
                                <input type="text" class="form-control" id="harga_jasa"
                                    value="Rp. {{number_format($penawaran->harga_produk_penawaran)}}" disabled>
                            </div>
                            <div class="form-group">
                                <label>Asal Kota Penawar</label>
                                <input type="text" class="form-control" id="asal" value="{{$penawaran->kota_penawaran}}"
                                    disabled>
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
                                </select>
                                <input type="text" hidden class="form-control" id="nama_kota" name="nama_kota">
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
            var name2 = "{{$penawaran->kota_penawaran}}";
            $.post("{{url('/order/get_price')}}", {
                    'kab_id': name,
                    'asal': "{{$penawaran->kota_penawaran}}",
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