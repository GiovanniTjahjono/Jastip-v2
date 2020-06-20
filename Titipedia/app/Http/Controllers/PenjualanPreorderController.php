<?php

namespace App\Http\Controllers;

use App\PenjualanPreorder;
use App\User;
use Illuminate\Http\Request;
use App\Produk;
use App\ProdukBulkBuy;
use Carbon\Carbon;
use App\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenjualanPreorderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function indexPreorderTerjual()
    {
        $id_user = Auth::user()->id;
        $penjualanOrders = DB::table('penjualan_preorders')
            ->join('produks', 'produks.id', '=', 'penjualan_preorders.id_produk')
            ->join('users', 'users.id', '=', 'produks.id_user')
            ->join('kategoris', 'kategoris.id', '=', 'produks.id_kategori')
            ->where('users.id', $id_user)
            ->select('penjualan_preorders.*', 'produks.nama as nama', 'produks.asal_negara','kategoris.nama_kategori', 'produks.estimasi_pengiriman')
            ->get();
        //------------FIX-------------
        $sisa_waktu = 0;
        if (count($penjualanOrders) > 0) {
            $waktu_sekarang = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
            $waktu_pembelian = strtotime($penjualanOrders[0]->estimasi_pengiriman);
            $sisa_waktu = strval(intval(($waktu_pembelian - $waktu_sekarang) / 60 / 60 / 24)); //Mengasilkan Hari
        }
        return view('pages.penjualan_preorder.penjualan_preorder', compact('penjualanOrders', 'sisa_waktu'));
    }
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function indexBulkBuyTerjual()
    {
        $id_user = Auth::user()->id;
        $penjualanOrders = DB::table('penjualan_preorders')
            ->join('produk_bulk_buys', 'produk_bulk_buys.id', '=', 'penjualan_preorders.id_bulkbuy')
            ->join('users', 'users.id', '=', 'produk_bulk_buys.id_user')
            ->join('kategoris', 'kategoris.id', '=', 'produk_bulk_buys.id_kategori')
            ->where('users.id', $id_user)
            ->select('penjualan_preorders.*', 'produk_bulk_buys.asal_negara', 'produk_bulk_buys.nama as nama', 'produk_bulk_buys.jumlah_target as jumlah_target', 'kategoris.nama_kategori', 'produk_bulk_buys.batas_waktu')
            ->get();
        //------------FIX--------------
        $sisa_waktu = 0;
        if (count($penjualanOrders) > 0) {
            $waktu_sekarang = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
            $waktu_pembelian = strtotime($penjualanOrders[0]->batas_waktu);
            $sisa_waktu = strval(intval(($waktu_pembelian - $waktu_sekarang) / 60 / 60 / 24)); //Mengasilkan Hari
        }
        return view('pages.penjualan_preorder.penjualan_bulk_buy', compact('penjualanOrders', 'sisa_waktu'));
    }
    public function editPreorderTerjual(PenjualanPreorder $prenjualan_preorder)
    {
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation
        $request->validate([
            'id_produk' => 'required',
            'id_pembeli' => 'required',
            'stok_pembelian' => 'required',
            'nama_pembeli' => 'required',
            'alamat_pengiriman' => 'required',
            'nama_kota' => 'required',
            'tipeService' => 'required',
            'hargaTotalnya' => 'required'
        ]);
        $produk_stok = DB::table('produks')
            ->where('produks.id', '=', $request->id_produk)
            ->get();
        if ($request->stok_pembelian > $produk_stok[0]->stok || $request->stok_pembelian < 1) {
            return redirect()->back()->with('status', 'Jumlah Yang Anda Beli Melebihi Batas!');
        } else {
            if (Auth::user()->saldo >= $request->hargaTotalnya) {
                $saldo_terbaru = Auth::user()->saldo - $request->hargaTotalnya;
                User::where('id', Auth::user()->id)
                    ->update([
                        'saldo' => $saldo_terbaru
                    ]);
                PenjualanPreorder::create([
                    'kode_transaksi' => Carbon::now()->format('mdHis') . $request->id_pembeli . $request->id_produk,
                    'kuantitas' => $request->stok_pembelian,
                    'total_harga' => $request->hargaTotalnya,
                    'kurir' => 'Tiki',
                    'service' => explode(",", $request->tipeService)[1],
                    'ongkir' => explode(",", $request->tipeService)[0],
                    'tanggal_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
                    'status_order' => 'menunggu',
                    'id_user' => $request->id_pembeli,
                    'id_produk' => $request->id_produk
                ]);
                $stok_baru = $produk_stok[0]->stok - $request->stok_pembelian;

                DB::table('produks')
                    ->where('id', $request->id_produk)
                    ->update(['stok' => $stok_baru]);
                //cara 3
                //produk::create($request->all());//all akan mengambil semua data fillable yang ada di model produk
                $kategoris = DB::table('kategoris')->get();

                $orders = DB::table('penjualan_preorders')
                    ->where('penjualan_preorders.id_user', '=', $request->id_pembeli)
                    ->join('produks', 'produks.id', '=', 'penjualan_preorders.id_produk')
                    ->join('kategoris', 'produks.id_kategori', '=', 'kategoris.id')
                    ->latest('penjualan_preorders.created_at')->get();
                //$ordsers = DB::table('prenjualan_preorders')->where('id_user', '=', $id)->get();
                $penerima = DB::table('users')
                    ->join('produks', 'produks.id_user', '=', 'users.id')
                    ->where('produks.id', $request->id_produk)
                    ->select('users.*')->get();
                Notifikasi::create([
                    'isi_notifikasi' => "Preorder Dari " . Auth::user()->name,
                    'waktu_kirim' => date("Y-m-d H:i:s"),
                    'jenis' => 'preorder',
                    'dibaca' => 'belum',
                    'link' => '/terjual',
                    'id_penerima' => $penerima[0]->id,
                    'id_trigger' => Auth::user()->id
                ]);
                //return view('pages.preorder.show', compact('orders'));
                return redirect('order/daftar_pembelian_preorder/' . Auth::user()->id);
            } else {
                return redirect()->back()->with('status', 'Saldo Anda Tidak Cukup!');
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeBulkBuy(Request $request)
    {
        //validation
        // $request->validate([
        //     'id_produk' => 'required',
        //     'id_pembeli' => 'required',
        //     'jumlah_target' => 'required',
        //     'nama_pembeli' => 'required',
        //     'alamat_pengiriman' => 'required',
        //     'nama_kota' => 'required',
        //     'tipeService' => 'required',
        //     'hargaTotalnya' => 'required'
        // ]);
        $jumlah_target = DB::table('produk_bulk_buys')
            ->where('produk_bulk_buys.id', $request->id_produk)
            ->get();
        //pengecekan stok
        if ($request->jumlah_target > $jumlah_target[0]->jumlah_target || $request->jumlah_target < 1) {
            return redirect()->back()->with('status', 'Jumlah Yang Anda Beli Melebihi Batas!');
        } else {
            if (Auth::user()->saldo >= $request->hargaTotalnya) {
                $saldo_terbaru = Auth::user()->saldo - $request->hargaTotalnya;
                User::where('id', Auth::user()->id)
                    ->update([
                        'saldo' => $saldo_terbaru
                    ]);
                PenjualanPreorder::create([
                    'kode_transaksi' => Carbon::now()->format('mdHis') . $request->id_pembeli . $request->id_produk,
                    'kuantitas' => $request->jumlah_target,
                    'total_harga' => $request->hargaTotalnya,
                    'kurir' => 'Tiki',
                    'service' => explode(",", $request->tipeService)[1],
                    'ongkir' => explode(",", $request->tipeService)[0],
                    'tanggal_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
                    'status_order' => 'menunggu',
                    'id_user' => $request->id_pembeli,
                    'id_bulkbuy' => $request->id_produk
                ]);
                $target_terbaru = $jumlah_target[0]->jumlah_target - $request->jumlah_target;
                DB::table('produk_bulk_buys')
                    ->where('id', $request->id_produk)
                    ->update(['jumlah_target' => $target_terbaru]);

                $kategoris = DB::table('kategoris')->get();

                $orders = DB::table('penjualan_preorders')
                    ->where('penjualan_preorders.id_user', '=', $request->id_pembeli)
                    ->join('produk_bulk_buys', 'produk_bulk_buys.id', '=', 'penjualan_preorders.id_bulkbuy')
                    ->join('kategoris', 'produk_bulk_buys.id_kategori', '=', 'kategoris.id')
                    ->latest('penjualan_preorders.created_at')->get();
                //$ordsers = DB::table('prenjualan_preorders')->where('id_user', '=', $id)->get();
                //dd($orders);
                $penerima = DB::table('users')
                    ->join('produk_bulk_buys', 'produk_bulk_buys.id_user', '=', 'users.id')
                    ->where('produk_bulk_buys.id', $request->id_produk)
                    ->select('users.*')->get();
                Notifikasi::create([
                    'isi_notifikasi' => "Bulk-Buy Dari " . Auth::user()->name,
                    'waktu_kirim' => date("Y-m-d H:i:s"),
                    'jenis' => 'bulkbuy',
                    'dibaca' => 'belum',
                    'link' => '/penjualan-bulk',
                    'id_penerima' => $penerima[0]->id,
                    'id_trigger' => Auth::user()->id
                ]);
                //return view('pages.bulkbuy.show', compact('orders'));
                return redirect('bulkbuy/daftar_pembelian_preorder/' . Auth::user()->id);
            } else {
                return redirect()->back()->with('status', 'Saldo Anda Tidak Cukup!');
            }
        }
        // if ($jumlah_target[0]->jumlah_target = 0) {
        //     DB::table('produk_bulk_buys')
        //         ->where('id', $request->id_produk)
        //         ->update(['status_bulk' => 'diproses']);
        //     DB::table('penjualan_preorders')
        //         ->where('penjualan_preorders', $request->id_produk)
        //         ->update(['status', 'dikirim']);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id_user = $id;
        $orders = DB::table('penjualan_preorders')
            ->where('penjualan_preorders.id_user', '=', $id)
            ->join('produks', 'produks.id', '=', 'penjualan_preorders.id_produk')
            ->join('kategoris', 'produks.id_kategori', '=', 'kategoris.id')
            ->select('penjualan_preorders.*', 'produks.nama as nama', 'produks.asal_negara','kategoris.nama_kategori as nama_kategori', 'produks.estimasi_pengiriman')
            ->latest('penjualan_preorders.created_at')->get();
        //-------------------FIX-------------------   
        $sisa_waktu = 0;
        if (count($orders) > 0) {
            $waktu_sekarang = strtotime(date(Carbon::now()->format('Y-m-d H:i:s')));
            $waktu_pembelian = strtotime($orders[0]->estimasi_pengiriman);
            $sisa_waktu = strval(intval(($waktu_pembelian - $waktu_sekarang) / 60 / 60 / 24)); //Mengasilkan Hari
        }
        return view('pages.preorder.show', compact('orders', 'sisa_waktu'));
    }
    public function showPembelianBulkBuy($id_bulk)
    {
        $id_user = $id_bulk;
        $orders = DB::table('penjualan_preorders')
            ->where('penjualan_preorders.id_user', '=', $id_user)
            ->join('produk_bulk_buys', 'produk_bulk_buys.id', '=', 'penjualan_preorders.id_bulkbuy')
            ->join('kategoris', 'produk_bulk_buys.id_kategori', '=', 'kategoris.id')
            ->select('penjualan_preorders.*', 'produk_bulk_buys.nama as nama', 'produk_bulk_buys.asal_negara', 'kategoris.nama_kategori as nama_kategori', 'produk_bulk_buys.batas_waktu')
            ->latest('penjualan_preorders.created_at')->get();
        //-------------------FIX-------------------   
        $sisa_waktu = 0;
        if (count($orders) > 0) {
            $waktu_sekarang = strtotime(date(Carbon::now()->format('Y-m-d H:i:s')));
            $waktu_pembelian = strtotime($orders[0]->batas_waktu);
            $sisa_waktu = strval(intval(($waktu_pembelian - $waktu_sekarang) / 60 / 60 / 24)); //Mengasilkan Hari
        }
        return view('pages.bulkbuy.show', compact('orders', 'sisa_waktu'));;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Produk  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function showProduk(Produk $produk)
    {
        if ($produk->status_produk !== 'tidak aktif') {
            //Get nama kota list
            $key = Config::get('RAJA_ONGKIR_API');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://api.rajaongkir.com/starter/city",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "key: b4cf42007b63acb57e34af6c70bddd8d"
                ),
            ));

            // Response
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            $penjualanRatingReview = DB::table('penjualan_preorders')
                ->join('produks', 'penjualan_preorders.id_produk', '=', 'produks.id')
                ->join('users', 'penjualan_preorders.id_user', '=', 'users.id')
                ->where('produks.id', $produk->id)
                ->select('penjualan_preorders.*', 'users.name as name', 'users.foto as foto')->get();
            //dd($penjualanRatingReview);
            $kategori = DB::table('kategoris')->where('id', '=', $produk->id_kategori)->get();
            $gambar = DB::table('gambars')->where('id_produk', '=', $produk->id)->get();
            return view('pages.preorder.preorder', compact('produk', 'kategori', 'response', 'gambar', 'penjualanRatingReview'));
        } else {
            return redirect(('/home'));
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\ProdukBulkBuy  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function showBulkBuy(ProdukBulkBuy $produkBulkBuy)
    {
        //Get nama kota list
        $key = Config::get('RAJA_ONGKIR_API');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.rajaongkir.com/starter/city",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: b4cf42007b63acb57e34af6c70bddd8d"

            ),
        ));

        // Response
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $kategori = DB::table('kategoris')->where('id', '=', $produkBulkBuy->id_kategori)->get();
        $gambar = DB::table('gambars')->where('id_bulkbuy', '=', $produkBulkBuy->id)->get();
        $penjualanRatingReview = DB::table('penjualan_preorders')
            ->join('produk_bulk_buys', 'penjualan_preorders.id_bulkbuy', '=', 'produk_bulk_buys.id')
            ->join('users', 'penjualan_preorders.id_user', '=', 'users.id')
            ->where('produk_bulk_buys.id', $produkBulkBuy->id)
            ->select('penjualan_preorders.*', 'users.name as name', 'users.foto as foto')->get();
        // $count_target = DB::table('penjualan_preorders')
        //     ->where('penjualan_preorders.id_bulkbuy', $produkBulkBuy->id)
        //     ->count();
        // dd($count_target);
        return view('pages.bulkbuy.bulkbuy', compact('produkBulkBuy', 'kategori', 'response', 'gambar', 'penjualanRatingReview'));
    }
    public function RajaOngkir(Request $request)
    {
        $nama_kota_asal_pengiriman = $request['asal'];
        $key = Config::get('RAJA_ONGKIR_API');
        $curl1 = curl_init();
        curl_setopt_array($curl1, array(
            CURLOPT_URL => "http://api.rajaongkir.com/starter/city",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: b4cf42007b63acb57e34af6c70bddd8d"
            ),
        ));

        $response1 = curl_exec($curl1);
        $err1 = curl_error($curl1);

        curl_close($curl1);
        $id_kab = '';
        $data = json_decode($response1, true);
        for ($i = 0; $i < count($data['rajaongkir']['results']); $i++) {
            if ($data['rajaongkir']['results'][$i]['city_name'] === $nama_kota_asal_pengiriman) {
                $id_kab = $data['rajaongkir']['results'][$i]['city_id'];
            }
        }

        Log::debug($request['kab_id']);
        Log::debug($id_kab);
        $asal = $id_kab;
        $id_kabupaten = $request['kab_id'];
        $kurir = 'tiki'; //$request['kurir'];
        $berat = 1;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=" . $asal . "&destination=" . $id_kabupaten . "&weight=" . $berat . "&courier=" . $kurir . "",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: b4cf42007b63acb57e34af6c70bddd8d"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {

            return $response;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function edit(PenjualanPreorder $penjualanPreorder)
    {
        return view('pages.penjualan_preorder.edit', compact('penjualanPreorder'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function editPenjualanBulk(PenjualanPreorder $penjualanPreorder)
    {
        return view('pages.penjualan_preorder.edit_bulk_buy', compact('penjualanPreorder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PenjualanPreorder $penjualanPreorder)
    {

        $request->validate([
            'nomer_resi' => 'required'
        ]);

        //dd($request);
        PenjualanPreorder::where('id', $penjualanPreorder->id)
            ->update([
                'nomor_resi' => $request->nomer_resi,
                'tanggal_pengiriman' => Carbon::now()->format('Y-m-d H:i:s'),
                'status_order' => 2
            ]);
        return redirect('/terjual')->with('status', 'Data Berhasil Diubah!');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function updatePenjualanBulk(Request $request, PenjualanPreorder $penjualanPreorder)
    {

        $request->validate([
            'nomer_resi' => 'required'
        ]);

        //dd($penjualanPreorder->id);
        PenjualanPreorder::where('id', $penjualanPreorder->id)
            ->update([
                'nomor_resi' => $request->nomer_resi,
                'tanggal_pengiriman' => Carbon::now()->format('Y-m-d H:i:s'),
                'status_order' => 2
            ]);
        return redirect('penjualan-bulk')->with('status', 'Data Berhasil Diubah!');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function editRating(PenjualanPreorder $penjualanPreorder)
    {
        return view('pages.penjualan_preorder.rating_preorder', compact('penjualanPreorder'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function updateRating(Request $request, PenjualanPreorder $penjualanPreorder)
    {

        $request->validate([
            'review' => 'required'
        ]);

        //dd($request->review);
        PenjualanPreorder::where('id', $penjualanPreorder->id)
            ->update([
                'review' => $request->review
            ]);
        return redirect('/order/daftar_pembelian_preorder/' . Auth::user()->id)->with('status', 'Pemberian Rating dan Review Berhasil!');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function editRatingBulkBuy(PenjualanPreorder $penjualanPreorder)
    {
        return view('pages.penjualan_preorder.rating_bulkbuy', compact('penjualanPreorder'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function updateRatingBulkBuy(Request $request, PenjualanPreorder $penjualanPreorder)
    {

        $request->validate([
            'review' => 'required'
        ]);

        //dd($request->review);
        PenjualanPreorder::where('id', $penjualanPreorder->id)
            ->update([
                'review' => $request->review
            ]);
        return redirect('/bulkbuy/daftar_pembelian_preorder/' . Auth::user()->id)->with('status', 'Pemberian Rating dan Review Berhasil!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function konfirmasiPreorder(PenjualanPreorder $penjualanPreorder)
    {
        //dd($penjualanPreorder);
        PenjualanPreorder::where('id', $penjualanPreorder->id)
            ->update([
                'status_order' => 3
            ]);
        $user_penjual = DB::table('penjualan_preorders')
            ->join('produks', 'produks.id', '=', 'penjualan_preorders.id_produk')
            ->join('users', 'users.id', '=', 'produks.id_user')
            ->join('kategoris', 'kategoris.id', '=', 'produks.id_kategori')
            ->where('penjualan_preorders.id', $penjualanPreorder->id)
            ->select('users.*', 'penjualan_preorders.total_harga as total_harga')->get();
        $saldo_terbaru = $user_penjual[0]->saldo + $user_penjual[0]->total_harga;
        //dd($user_penjual[0]->id);
        User::where('id', $user_penjual[0]->id)
            ->update([
                'saldo' => $saldo_terbaru
            ]);
        return redirect()->back()->with('status', 'Data Berhasil Diubah!');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function konfirmasiBulkBuy(PenjualanPreorder $penjualanPreorder)
    {
        PenjualanPreorder::where('id', $penjualanPreorder->id)
            ->update([
                'status_order' => 3
            ]);
        $user_penjual = DB::table('penjualan_preorders')
            ->join('produk_bulk_buys', 'produk_bulk_buys.id', '=', 'penjualan_preorders.id_bulkbuy')
            ->join('users', 'users.id', '=', 'produk_bulk_buys.id_user')
            ->join('kategoris', 'kategoris.id', '=', 'produk_bulk_buys.id_kategori')
            ->where('penjualan_preorders.id', $penjualanPreorder->id)
            ->select('users.*', 'penjualan_preorders.total_harga as total_harga')->get();
        $saldo_terbaru = $user_penjual[0]->saldo + $user_penjual[0]->total_harga;
        //dd($user_penjual[0]->id);
        User::where('id', $user_penjual[0]->id)
            ->update([
                'saldo' => $saldo_terbaru
            ]);
        return redirect()->back()->with('status', 'Data Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PenjualanPreorder  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PenjualanPreorder $penjualanPreorder)
    {
        // dd($penjualanPreorder->id_produk);
        PenjualanPreorder::where('id', $penjualanPreorder->id)
            ->update([
                'status_order' => 5
            ]);
        $stok = $penjualanPreorder->kuantitas;
        $harga = $penjualanPreorder->total_harga;
        $saldo_user = DB::table('users')
            ->where('id', $penjualanPreorder->id_user)
            ->get();
        $produk = DB::table('produks')
            ->join('penjualan_preorders', 'penjualan_preorders.id_produk', '=', 'produks.id')
            ->where('penjualan_preorders.id', $penjualanPreorder->id)
            ->select('produks.*')->get();
        $saldo_baru = $harga + $saldo_user[0]->saldo;
        $stok_update = $stok + $produk[0]->stok;
        //dd($stok);
        User::where('id', $penjualanPreorder->id_user)
            ->update([
                'saldo' => $saldo_baru
            ]);
        Produk::where('id', $penjualanPreorder->id_produk)
            ->update([
                'stok' => $stok_update
            ]);
        return redirect('/terjual')->with('status', 'Data Berhasil Dibatalkan!');
    }
    public function destroyPenjualanBulk(PenjualanPreorder $penjualanPreorder)
    {
        //dd($penjualanPreorder->id_bulkbuy);
        PenjualanPreorder::where('id', $penjualanPreorder->id)
            ->update([
                'status_order' => 5
            ]);
        $jumlah_target = $penjualanPreorder->kuantitas;
        $harga = $penjualanPreorder->total_harga;
        $saldo_user = DB::table('users')
            ->where('id', $penjualanPreorder->id_user)
            ->get();
        $bulkbuy = DB::table('produk_bulk_buys')
            ->join('penjualan_preorders', 'penjualan_preorders.id_bulkbuy', '=', 'produk_bulk_buys.id')
            ->where('penjualan_preorders.id', $penjualanPreorder->id)
            ->select('produk_bulk_buys.*')->get();
        //dd($bulkbuy);
        $saldo_baru = $harga + $saldo_user[0]->saldo;
        $target_update = $jumlah_target + $bulkbuy[0]->jumlah_target;
        User::where('id', $penjualanPreorder->id_user)
            ->update([
                'saldo' => $saldo_baru
            ]);
        ProdukBulkBuy::where('id', $penjualanPreorder->id_bulkbuy)
            ->update([
                'jumlah_target' => $target_update
            ]);
        return redirect('penjualan-bulk')->with('status', 'Data Berhasil Dibatalkan!');
    }
}
