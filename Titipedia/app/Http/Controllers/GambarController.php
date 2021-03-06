<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gambar;
use Illuminate\Support\Facades\DB;
use App\Produk;
use App\Req;
use App\ProdukBulkBuy;

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

            foreach ($request->file('gambar') as $image) {
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
        }else {
            return redirect()->back();
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
        //Pengecekan extensi gambar
        $id = $request->id_bulkbuy;
        //dd($id);
        if ($request->hasFile('gambar')) {
            $getLastIDImage = DB::table('gambars')
                ->where('id_bulkbuy', $id)
                ->select('url')
                ->orderBy('id', 'desc')->first();;
            //dd($getLastIDImage);
            $identity = explode("_", $getLastIDImage->url); //[0] = '2', [1] = 'produk', [3] = '3.jpg'
            //produk = 1_produk_0.jpg [3]
            //produk bb = 1_produk_bulk_buy_0.jpg [5]
            //explode adalah substring berdasarkan seuatu karakter
            $distinctExtention = explode(".", $identity[4])[0] + 1; //[0] = '3', [1] = 'jpg'
            foreach ($request->file('gambar') as $image) {
                $filename = $image->getClientOriginalName();
                $extensionTemp = explode(".", $filename);
                $extension = $extensionTemp[count($extensionTemp) - 1];
                $image->move("produk_bulk_buy_images/", strval($id) . "_produk_bulk_buy_" . strval($distinctExtention) . "." . $extension); //penamaan yg bukan array, penamaan array ada di registercontroller
                Gambar::create([
                    'url' => strval($id) . "_produk_bulk_buy_" . strval($distinctExtention) . "." . $extension,
                    'id_bulkbuy' => $id
                ]);
                $distinctExtention++;
            }
            return redirect()->back();
        }else {
            return redirect()->back();
        }
    }

    public function storeRequest(Request $request)
    {
        $id = $request->id_request;

        if ($request->hasFile('gambar')) {
            $getLastIDImage = DB::table('gambars')
                ->where('id_request', $id)
                ->select('url')
                ->orderBy('id', 'desc')->first();;

            $identity = explode("_", $getLastIDImage->url); //[0] = '2', [1] = 'produk', [3] = '3.jpg'
            $distinctExtention = explode(".", $identity[2])[0] + 1; //[0] = '3', [1] = 'jpg'

            foreach ($request->file('gambar') as $image) {
                $filename = $image->getClientOriginalName();
                $extensionTemp = explode(".", $filename);
                $extension = $extensionTemp[count($extensionTemp) - 1];
                $image->move("request_images/", strval($id) . "_request_" . strval($distinctExtention) . "." . $extension); //penamaan yg bukan array, penamaan array ada di registercontroller
                Gambar::create([
                    'url' => strval($id) . "_request_" . strval($distinctExtention) . "." . $extension,
                    'id_request' => $id
                ]);
                $distinctExtention++;
            }
            return redirect()->back();
        } else {
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
    public function editBulkBuy(Gambar $gambar)
    {
        $gambars = DB::table('gambars')
            ->where('id_bulkbuy', '=', $gambar->id)
            ->get();
        return view('pages.produkBulkBuy.editGambarBulk', compact('gambars'));
    }
    public function editRequest(Gambar $gambar)
    {
        $gambars = DB::table('gambars')
            ->where('id_request', '=', $gambar->id)
            ->get();
        return view('pages.request.editGambar', compact('gambars'));
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
    // 
    public function destroy(Gambar $gambar, Produk $produk)
    {
        $jumlahGambar = DB::table('gambars')
            ->where('id_produk', '=', $produk->id)
            ->get();
        if (count($jumlahGambar) > 1) {
            Gambar::destroy($gambar->id);
            return redirect()->back()->with('status', 'Berhasil Dihapus!');;
        } else {
            return redirect()->back()->with('status', 'Gagal dihapus, produk setidaknya harus memiliki 1 gambar!');;
        }
    }
    public function destroyBulkBuy(Gambar $gambar, ProdukBulkBuy $produkBulkBuy)
    {
        $jumlahGambarBulk = DB::table('gambars')
            ->where('id_bulkbuy', '=', $produkBulkBuy->id)
            ->get();
        if (count($jumlahGambarBulk) > 1) {
            Gambar::destroy($gambar->id);
            return redirect()->back()->with('status', 'Berhasil Dihapus!');;
        } else {
            return redirect()->back()->with('status', 'Gagal dihapus, produk setidaknya harus memiliki 1 gambar!');;
        }
    }
    
    public function destroyRequest(Gambar $gambar, Req $req)
    {
        $jumlahGambar = DB::table('gambars')
            ->where('id_request', '=', $req->id)
            ->get();
        if (count($jumlahGambar) > 1) {
            Gambar::destroy($gambar->id);
            return redirect()->back()->with('status', 'Berhasil Dihapus!');;
        } else {
            return redirect()->back()->with('status', 'Gagal dihapus, produk setidaknya harus memiliki 1 gambar!');;
        }
    }


}
