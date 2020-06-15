<?php

namespace App\Http\Controllers;

use App\PenjualanPreorder;
use App\User;
use Illuminate\Http\Request;
use App\Produk;
use App\ProdukBulkBuy;
use Carbon\Carbon;
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
            ->select('penjualan_preorders.*', 'produks.nama as nama', 'kategoris.nama_kategori')
            ->get();
        return view('pages.penjualan_preorder.penjualan_preorder', compact('penjualanOrders'));
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
            ->select('penjualan_preorders.*', 'produk_bulk_buys.nama as nama', 'produk_bulk_buys.jumlah_target as jumlah_target', 'kategoris.nama_kategori')
            ->get();
        //dd($jumlah_target);
        return view('pages.penjualan_preorder.penjualan_bulk_buy', compact('penjualanOrders'));
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
                return view('pages.preorder.show', compact('orders'));
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
                return view('pages.bulkbuy.show', compact('orders'));
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
            ->latest('penjualan_preorders.created_at')->get();
        //$ordsers = DB::table('orders')->where('id_user', '=', $id)->get();
        return view('pages.preorder.show', compact('orders'));
    }
    public function showPembelianBulkBuy($id_bulk)
    {
        $id_user = $id_bulk;
        $orders = DB::table('penjualan_preorders')
            ->where('penjualan_preorders.id_user', '=', $id_user)
            ->join('produk_bulk_buys', 'produk_bulk_buys.id', '=', 'penjualan_preorders.id_bulkbuy')
            ->join('kategoris', 'produk_bulk_buys.id_kategori', '=', 'kategoris.id')
            ->select('penjualan_preorders.*', 'produk_bulk_buys.nama as nama', 'kategoris.nama_kategori as nama_kategori')
            ->latest('penjualan_preorders.created_at')->get();
        //$ordsers = DB::table('prenjualan_preorders')->where('id_user', '=', $id)->get();
        //dd($id_user);
        return view('pages.bulkbuy.show', compact('orders'));;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Produk  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function showProduk(Produk $produk)
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

        $kategori = DB::table('kategoris')->where('id', '=', $produk->id_kategori)->get();
        $gambar = DB::table('gambars')->where('id_produk', '=', $produk->id)->get();
        return view('pages.preorder.preorder', compact('produk', 'kategori', 'response', 'gambar'));
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
        // $count_target = DB::table('penjualan_preorders')
        //     ->where('penjualan_preorders.id_bulkbuy', $produkBulkBuy->id)
        //     ->count();
        // dd($count_target);
        return view('pages.bulkbuy.bulkbuy', compact('produkBulkBuy', 'kategori', 'response', 'gambar'));
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

        PenjualanPreorder::where('id', $penjualanPreorder->id)
            ->update([
                'status_order' => 5
            ]);

        $harga = $penjualanPreorder->total_harga;
        $saldo_user = DB::table('users')
            ->where('id', $penjualanPreorder->id_user)
            ->get();
        $saldo_baru = $harga + $saldo_user[0]->saldo;
        //dd($saldo_baru);
        User::where('id', $penjualanPreorder->id_user)
            ->update([
                'saldo' => $saldo_baru
            ]);
        return redirect('/terjual')->with('status', 'Data Berhasil Dibatalkan!');
    }
    public function destroyPenjualanBulk(PenjualanPreorder $penjualanPreorder)
    {

        PenjualanPreorder::where('id', $penjualanPreorder->id)
            ->update([
                'status_order' => 5
            ]);

        $harga = $penjualanPreorder->total_harga;
        $saldo_user = DB::table('users')
            ->where('id', $penjualanPreorder->id_user)
            ->get();
        $saldo_baru = $harga + $saldo_user[0]->saldo;
        //dd($saldo_user[0]->saldo);
        User::where('id', $penjualanPreorder->id_user)
            ->update([
                'saldo' => $saldo_baru
            ]);
        return redirect('penjualan-bulk')->with('status', 'Data Berhasil Dibatalkan!');
    }
}
