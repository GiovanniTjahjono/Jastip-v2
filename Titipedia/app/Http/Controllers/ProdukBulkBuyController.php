<?php

namespace App\Http\Controllers;

use App\ProdukBulkBuy;
use App\User;
use App\Gambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Contracts\DataTable;

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
            ->select('produk_bulk_buys.*', 'users.name', 'kategoris.nama_kategori')
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
        //
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
        return view('pages.produkBulkBuy.create', compact('response', 'kategoris'));
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
            'gambar' => 'required'
        ]);

        //get last id from product
        $produk = DB::table('produks')->orderBy('id', 'desc')->first();
        $id = $produk->id;

        if ($request->hasFile('gambar')) {
            $identity = 0;
            foreach ($request->file('gambar') as $image) {
                $filename = $image->getClientOriginalName();
                $extensionTemp = explode(".", $filename);
                $extension = $extensionTemp[count($extensionTemp) - 1];
                $image->move("produk_images/", strval($id + 1) . "_produk" . strval($identity) . "." . $extension); //penamaan yg bukan array, penamaan array ada di registercontroller
                Gambar::create([
                    'url' => strval($id + 1) . "_produk" . strval($identity) . "." . $extension,
                    'id_produk' => $id + 1
                ]);
                $identity++;
            }
        }
        Produk::create([
            'nama' => $request->nama_produk,
            'stok' => $request->stok,
            'harga_jasa' => $request->harga_jasa,
            'harga_produk' => $request->harga_produk,
            'berat' => $request->berat,
            'keterangan' => $request->keterangan,
            'id_user' => $request->id_user,
            'id_kategori' => $request->nama_kategori
        ]);
        return redirect('produk')->with('status', 'Data Berhasil Ditambahkan!');
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
    }
}
