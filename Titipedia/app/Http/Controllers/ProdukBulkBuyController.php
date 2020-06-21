<?php

namespace App\Http\Controllers;

use App\ProdukBulkBuy;
use App\User;
use App\Gambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Contracts\DataTable;
use Monarobase\CountryList\CountryListFacade as Negara;

class ProdukBulkBuyController extends Controller
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
        $produkBulkBuys = DB::table('produk_bulk_buys')
            ->join('users', function ($join) {
                $join->on('produk_bulk_buys.id_user', '=', 'users.id')
                    ->where('users.id', '=', Auth::user()->id);
            })
            ->join('kategoris', 'produk_bulk_buys.id_kategori', '=', 'kategoris.id')
            ->select('produk_bulk_buys.*', 'users.name', 'produk_bulk_buys.asal_negara','kategoris.nama_kategori')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('pages.produkBulkBuy.produkbulkbuy', compact('produkBulkBuys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategoris = DB::table('kategoris')->get();
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
                "key: 20abcef3dbf0bc2149a7412bc9b60005"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $negara = Negara::getList('id', 'json');
        return view('pages.produkBulkBuy.create', compact('response', 'kategoris', 'negara'));
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
            'nama_produk' => 'required',
            'jumlah_target' => 'required',
            'harga_jasa' => 'required',
            'harga_produk' => 'required',
            'berat' => 'required',
            'batas_waktu' => 'required',
            'gambar' => 'required',
            'asal_negara' => 'required'
        ]);
        ProdukBulkBuy::create([
            'nama' => $request->nama_produk,
            'jumlah_target' => $request->jumlah_target,
            'harga_jasa' => $request->harga_jasa,
            'harga_produk' => $request->harga_produk,
            'berat' => $request->berat,
            'batas_waktu' => $request->batas_waktu,
            'asal_pengiriman' => $request->asal_pengiriman,
            'asal_negara' => $request->asal_negara,
            'keterangan' => $request->keterangan,
            'status_bulk' => 'aktif',
            'id_user' => $request->id_user,
            'id_kategori' => $request->nama_kategori
        ]);
        //get last id from product
        $produkbulkbuy = DB::table('produk_bulk_buys')->orderBy('id', 'desc')->first();
        $id = $produkbulkbuy->id;

        if ($request->hasFile('gambar')) {
            $identity = 0;
            foreach ($request->file('gambar') as $image) {
                $filename = $image->getClientOriginalName();
                $extensionTemp = explode(".", $filename);
                $extension = $extensionTemp[count($extensionTemp) - 1];
                $image->move("produk_bulk_buy_images/", strval($id) . "_produk_bulk_buy_" . strval($identity) . "." . $extension); //penamaan yg bukan array, penamaan array ada di registercontroller
                Gambar::create([
                    'url' => strval($id) . "_produk_bulk_buy_" . strval($identity) . "." . $extension,
                    'id_bulkbuy' => $id
                ]);
                $identity++;
            }
        }
        return redirect('produk-bulk-buy')->with('status', 'Data Berhasil Ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProdukBulkBuy  $produkBulkBuy
     * @return \Illuminate\Http\Response
     */
    public function show(ProdukBulkBuy $produkBulkBuy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProdukBulkBuy  $produkBulkBuy
     * @return \Illuminate\Http\Response
     */
    public function edit(ProdukBulkBuy $produkBulkBuy)
    {
        //
        $gambars = DB::table('gambars')
            ->where('id_bulkbuy', '=', $produkBulkBuy->id)
            ->get();
        $kategoris = DB::table('kategoris')->get();
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
                "key: 20abcef3dbf0bc2149a7412bc9b60005"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $negara = Negara::getList('id', 'json');
        return view('pages.produkBulkBuy.edit', compact('produkBulkBuy', 'gambars', 'kategoris', 'response', 'negara'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProdukBulkBuy  $produkBulkBuy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProdukBulkBuy $produkBulkBuy)
    {
        //
        $request->validate([
            'nama_produk' => 'required',
            'jumlah_target' => 'required',
            'harga_jasa' => 'required',
            'harga_produk' => 'required',
            'asal_negara' => 'required',
            'berat' => 'required'
        ]);
        ProdukBulkBuy::where('id', $produkBulkBuy->id)
            ->update([
                'nama' => $request->nama_produk,
                'jumlah_target' => $request->jumlah_target,
                'harga_jasa' => $request->harga_jasa,
                'harga_produk' => $request->harga_produk,
                'berat' => $request->berat,
                'asal_pengiriman' => $request->asal_pengiriman,
                'asal_negara' => $request->asal_negara,
                'keterangan' => $request->keterangan,
                'status_bulk' => 'menunggu',
                'id_user' => $request->id_user,
                'id_kategori' => $request->nama_kategori
            ]);
        return redirect('produk-bulk-buy')->with('status', 'Data Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProdukBulkBuy  $produkBulkBuy
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProdukBulkBuy $produkBulkBuy)
    {
        //
        ProdukBulkBuy::destroy($produkBulkBuy->id);
        //Gambar::destroy($gambar->id_produk);
        return redirect('produk-bulk-buy')->with('status', 'Data Produk Berhasil Dihapus!');
    }
}
