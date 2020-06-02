<?php

namespace App\Http\Controllers;

use App\Produk;
use App\User;
use App\Gambar;
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
        $kategoris = DB::table('kategoris')->get();
        return view('pages.produk.create', compact('kategoris'));
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
            'gambar' => 'required'
        ]);

        //get last id from product
        $produk = DB::table('produks')->orderBy('id', 'desc')->first();
        $id = $produk->id;

        if ($request->hasFile('gambar')) {
            $identity = 0;
            foreach($request->file('gambar') as $image) {
                $filename = $image->getClientOriginalName();
                $extensionTemp = explode(".", $filename);
                $extension = $extensionTemp[count($extensionTemp) - 1]; 
                $image->move("produk_images/", strval($id+1) . "_produk" . strval($identity) . "." . $extension); //penamaan yg bukan array, penamaan array ada di registercontroller
                Gambar::create([
                    'url' => strval($id+1) . "_produk" . strval($identity) . "." . $extension,
                    'id_produk' => $id+1
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
        $gambars = DB::table('gambars')
                ->where('id_produk', '=', $produk->id)
                ->get();
        return view('pages.produk.edit', compact('produk', 'gambars'));
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
            'jenis_produk' => 'required',
            'stok' => 'required',
            'harga_jasa' => 'required',
            'harga_produk' => 'required',
            'berat' => 'required',
            'gambar' => 'required'
        ]);
        //-------------------BELUM SELESAI----------------------
        
        $id = DB::table('produks')->orderBy('id', 'desc')->first()->id + 1;
        $request->file('gambar')->move("produk_images/", strval($id) . "_produk.jpg"); //penamaan yg bukan array, penamaan array ada di registercontroller
        $filename = $id . '_produk.jpg';
        
        if ($request->hasFile('gambar')) {
            $identity = 0;
            foreach($request->file('gambar') as $image) {
                $filename = $image->getClientOriginalName();
                $extensionTemp = explode(".", $filename);
                $extension = $extensionTemp[count($extensionTemp) - 1]; 
                $image->move("produk_images/", strval($id+1) . "_produk" . strval($identity) . "." . $extension); //penamaan yg bukan array, penamaan array ada di registercontroller
                Gambar::create([
                    'url' => strval($id+1) . "_produk" . strval($identity) . "." . $extension,
                    'id_produk' => $id+1
                ]);
                $identity++;
            }
        }
        Produk::where('id', $produk->id)
            ->update([
                'nama' => $request->nama_produk,
                'jenis_produk' => $request->jenis_produk,
                'stok' => $request->stok,
                'harga_jasa' => $request->harga_jasa,
                'harga_produk' => $request->harga_produk,
                'berat' => $request->berat,
                'keterangan' => $request->keterangan,
                'id_user' => $request->id_user,
                'gambar' => $filename
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

