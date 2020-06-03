<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gambar;
use Illuminate\Support\Facades\DB;

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
            dd($getLastIDImage);
                            $identity = explode("_", $getLastIDImage)[count($getLastIDImage) - 1];

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
    public function destroy(Gambar $gambar)
    {
        Gambar::destroy($gambar->id);
        return redirect()->back();
    }
}
