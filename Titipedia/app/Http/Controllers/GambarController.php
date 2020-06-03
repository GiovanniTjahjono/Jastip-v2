<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gambar;
use Illuminate\Support\Facades\DB;
use App\Produk;

class GambarController extends Controller
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
        $id = $request->id_produk;

        if ($request->hasFile('gambar')) {
            $getLastIDImage = DB::table('gambars')
                            ->where('id_produk', $id)
                            ->select('url')
                            ->orderBy('id', 'desc')->first();;
            
            $identity = explode("_", $getLastIDImage->url); //[0] = '2', [1] = 'produk', [3] = '3.jpg'
            $distinctExtention = explode(".", $identity[2])[0] + 1; //[0] = '3', [1] = 'jpg'

            foreach($request->file('gambar') as $image) {
                $filename = $image->getClientOriginalName();
                $extensionTemp = explode(".", $filename);
                $extension = $extensionTemp[count($extensionTemp) - 1]; 
                $image->move("produk_images/", strval($id) . "_produk_" . strval($distinctExtention) . "." . $extension); //penamaan yg bukan array, penamaan array ada di registercontroller
                Gambar::create([
                    'url' => strval($id) . "_produk_" . strval($distinctExtention) . "." . $extension,
                    'id_produk' => $id
                ]);
                $distinctExtention++;
            }
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Gambar $gambar)
    {
        $gambars = DB::table('gambars')
                ->where('id_produk', '=', $gambar->id)
                ->get();
        return view('pages.produk.editGambar', compact('gambars'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gambar $gambar, Produk $produk)
    {
        $jumlahGambar = DB::table('gambars')
                ->where('id_produk', '=', $produk->id)
                ->get();
        if(count($jumlahGambar) > 1) {
            Gambar::destroy($gambar->id);
            return redirect()->back()->with('status', 'Berhasil Dihapus!');;
        }
        else {
            return redirect()->back()->with('status', 'Gagal dihapus, produk setidaknya harus memiliki 1 gambar!');;
        }
        
    }
}
