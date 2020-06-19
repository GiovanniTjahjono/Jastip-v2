<?php

namespace App\Http\Controllers;

use App\Kategori;
use Illuminate\Http\Request;
use App\Produk;
use App\ProdukBulkBuy;
use App\Req;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function show(Kategori $kategori)
    {
        //dd($kategori->id);
        $kategoris = DB::table('kategoris')->get();
        $produks = DB::table('produks')
            ->join('users', 'users.id', '=', 'produks.id_user')
            ->join('kategoris', 'produks.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'produks.id', '=', 'gambars.id_produk')->groupBy('produks.id')
            ->select('produks.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->where('produks.stok', '>', 0)
            ->where('produks.id_kategori', $kategori->id)
            ->where('produks.status_produk', 'aktif')
            ->latest()->take(8)->get();
        //dd($produks);
        // Ambil data produk bulk buy
        $bulkbuys = DB::table('produk_bulk_buys')
            ->join('users', 'users.id', '=', 'produk_bulk_buys.id_user')
            ->join('kategoris', 'produk_bulk_buys.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'produk_bulk_buys.id', '=', 'gambars.id_bulkbuy')->groupBy('produk_bulk_buys.id')
            ->select('produk_bulk_buys.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->where('produk_bulk_buys.jumlah_target', '>', 0)
            ->where('produk_bulk_buys.id_kategori', $kategori->id)
            ->latest()->take(8)->get();

        // Ambil data prequest

        $requests = DB::table('requests')
            ->join('users', 'users.id', '=', 'requests.id_user')
            ->join('kategoris', 'requests.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'requests.id', '=', 'gambars.id_request')->groupBy('requests.id')
            ->select('requests.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->where('requests.status_req', 'aktif')
            ->where('requests.id_kategori', $kategori->id)
            ->latest()->take(8)->get();
        return view('pages.kategori', compact('produks', 'kategoris', 'bulkbuys', 'requests'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function edit(Kategori $kategori)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kategori $kategori)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kategori $kategori)
    {
        //
    }
}
