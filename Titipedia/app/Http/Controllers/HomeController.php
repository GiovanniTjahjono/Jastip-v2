<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Jika ingin tidak melewati login untuk pergi ke halaman ini, hapus code d bawah
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //auth::chek untuk kondisi jika user login
        //if (Auth::check()) {
        $kategoris = DB::table('kategoris')->get();
        // Ambil data produk
        $produks = DB::table('produks')
            ->join('users', 'users.id', '=', 'produks.id_user')
            ->join('kategoris', 'produks.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'produks.id', '=', 'gambars.id_produk')->groupBy('produks.id')
            ->select('produks.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->where('produks.stok', '>', 0)
            ->latest()->take(8)->get();

        // Ambil data produk bulk buy
        $bulkbuys = DB::table('produk_bulk_buys')
            ->join('users', 'users.id', '=', 'produk_bulk_buys.id_user')
            ->join('kategoris', 'produk_bulk_buys.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'produk_bulk_buys.id', '=', 'gambars.id_bulkbuy')->groupBy('produk_bulk_buys.id')
            ->select('produk_bulk_buys.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->where('produk_bulk_buys.jumlah_target', '>', 0)
            ->latest()->take(8)->get();

        // Ambil data prequest

        $requests = DB::table('requests')
            ->join('users', 'users.id', '=', 'requests.id_user')
            ->join('kategoris', 'requests.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'requests.id', '=', 'gambars.id_request')->groupBy('requests.id')
            ->select('requests.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->where('requests.status_req', 'aktif')
            ->latest()->take(8)->get();

        return view('pages.home', compact('produks', 'kategoris', 'bulkbuys', 'requests'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cari(Request $requests)
    {
        $cari = $requests->cari;
        //dd($cari);

        $kategoris = DB::table('kategoris')->get();
        // Ambil data produk
        $produks = DB::table('produks')
            ->join('users', 'users.id', '=', 'produks.id_user')
            ->join('kategoris', 'produks.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'produks.id', '=', 'gambars.id_produk')->groupBy('produks.id')
            ->select('produks.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->where('produks.stok', '>', 0)
            ->where('produks.nama', 'like', "%" . $cari . "%")
            ->latest()->take(8)->get();

        // Ambil data produk bulk buy
        $bulkbuys = DB::table('produk_bulk_buys')
            ->join('users', 'users.id', '=', 'produk_bulk_buys.id_user')
            ->join('kategoris', 'produk_bulk_buys.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'produk_bulk_buys.id', '=', 'gambars.id_bulkbuy')->groupBy('produk_bulk_buys.id')
            ->select('produk_bulk_buys.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->where('produk_bulk_buys.jumlah_target', '>', 0)
            ->where('produk_bulk_buys.nama', 'like', "%" . $cari . "%")
            ->latest()->take(8)->get();

        // Ambil data prequest

        $requests = DB::table('requests')
            ->join('users', 'users.id', '=', 'requests.id_user')
            ->join('kategoris', 'requests.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'requests.id', '=', 'gambars.id_request')->groupBy('requests.id')
            ->select('requests.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->where('requests.status_req', 'aktif')
            ->where('requests.nama_req', 'like', "%" . $cari . "%")
            ->latest()->take(8)->get();

        return view('pages.home', compact('produks', 'kategoris', 'bulkbuys', 'requests'));
    }
}
