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
        ->join('users', function($join) {
            $join->on('produk_bulk_buys.id_user', '=', 'users.id')
            ->where('users.id', '=', Auth::user()->id);
        })
        ->join('kategoris', 'produk_bulk_buys.id_kategori', '=', 'kategoris.id')
        ->select('produk_bulk_buys.*', 'users.name', 'kategoris.nama_kategori')
        ->get();
        return view('pages.produkBulkBuy.produk', compact('produkBulkBuys'));
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
