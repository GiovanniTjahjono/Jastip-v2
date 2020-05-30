<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $kategoris = DB::table('kategoris')->get();

        $produks = DB::table('produks')
            ->join('users', 'users.id', '=', 'produks.id_user')
            ->join('kategoris', 'produks.id_kategori', '=', 'kategoris.id')
            ->join('gambars', 'produks.id', '=', 'gambars.id_produk')->groupBy('produks.id')
            ->select('produks.*', 'users.name', 'gambars.url', 'kategoris.nama_kategori')
            ->latest()->take(8)->get();
        return view('pages.home', compact('produks', 'kategoris'));
    }
}
