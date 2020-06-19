<?php

namespace App\Http\Controllers;

use App\Produk;
use App\User;
use App\Gambar;
use App\PenjualanPreorder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Contracts\DataTable;


class ProdukController extends Controller
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
        $produks = DB::table('produks')
            ->join('users', function($join) {
                $join->on('produks.id_user', '=', 'users.id')
                ->where('users.id', '=', Auth::user()->id);
            })
            ->join('kategoris', 'produks.id_kategori', '=', 'kategoris.id')
            ->select('produks.*', 'users.name', 'kategoris.nama_kategori')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('pages.produk.produk', compact('produks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $kategoris = DB::table('kategoris')->get();
        return view('pages.produk.create', compact('response', 'kategoris'));
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
            'stok' => 'required',
            'harga_jasa' => 'required',
            'harga_produk' => 'required',
            'berat' => 'required',
            'keterangan' => 'required',
            'gambar' => 'required',
            'estimasi_pengiriman' => 'required'
        ]);

        $status_produk = 1;

        if($request->estimasi_pengiriman < Carbon::now()->format('Y-m-d')) {
           $status_produk = 2;
        } 
        Produk::create([
            'nama' => $request->nama_produk,
            'stok' => $request->stok,
            'harga_jasa' => $request->harga_jasa,
            'harga_produk' => $request->harga_produk,
            'berat' => $request->berat,
            'keterangan' => $request->keterangan,
            'asal_pengiriman' => $request->asal_pengiriman,
            'id_user' => $request->id_user,
            'id_kategori' => $request->nama_kategori,
            'status_produk' => $status_produk,
            'estimasi_pengiriman' => $request->estimasi_pengiriman
        ]);
        
        //get last id from product
        $produk = DB::table('produks')->orderBy('id', 'desc')->first();
        $id = $produk->id;
        //dd($id);
        if ($request->hasFile('gambar')) {
            $identity = 0;
            foreach($request->file('gambar') as $image) {
                $filename = $image->getClientOriginalName();
                $extensionTemp = explode(".", $filename);
                $extension = $extensionTemp[count($extensionTemp) - 1]; 
                $image->move("produk_images/", strval($id) . "_produk_" . strval($identity) . "." . $extension); //penamaan yg bukan array, penamaan array ada di registercontroller
                Gambar::create([
                    'url' => strval($id) . "_produk_" . strval($identity) . "." . $extension,
                    'id_produk' => $id
                ]);
                $identity++;
            }
        }
        return redirect('produk')->with('status', 'Data Berhasil Ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function show(Produk $produk)
    {
        
        return view('pages.produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function edit(Produk $produk)
    {   
        $kategoris = DB::table('kategoris')->get();
        $gambars = DB::table('gambars')
                ->where('id_produk', '=', $produk->id)
                ->get();
        return view('pages.produk.edit', compact('produk', 'gambars', 'kategoris'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required',
            'stok' => 'required',
            'nama_kategori' => 'required',
            'harga_jasa' => 'required',
            'harga_produk' => 'required',
            'berat' => 'required'
        ]);
        Produk::where('id', $produk->id)
            ->update([
                'nama' => $request->nama_produk,
                'stok' => $request->stok,
                'id_kategori' => $request->nama_kategori,
                'harga_jasa' => $request->harga_jasa,
                'harga_produk' => $request->harga_produk,
                'berat' => $request->berat,
                'keterangan' => $request->keterangan,
                'id_user' => $request->id_user
            ]);
       
        return redirect('produk')->with('status', 'Data Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produk $produk)
    {
        Produk::destroy($produk->id);
        //Gambar::destroy($gambar->id_produk);
        return redirect('produk')->with('status', 'Data Produk Berhasil Dihapus!');
    }
    
}

