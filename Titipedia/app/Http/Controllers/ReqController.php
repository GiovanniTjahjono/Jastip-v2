<?php

namespace App\Http\Controllers;

use App\Req;
use Illuminate\Http\Request;
use App\Gambar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReqController extends Controller
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
        $pengguna = Auth::user()->id;
        $request = DB::table('requests')->where('id_user', '=', $pengguna)->get();
        return view('pages.request.request', compact('request'));
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
        return view('pages.request.create', compact('response', 'kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_req' => 'required',
            'jumlah_req' => 'required',
            'alamat_req' => 'required',
            'kota_req' => 'required',
            'status_req' => 'required',
            'gambar' => 'required',
            'id_kategori' => 'required'
        ]);
        //
        Req::create([
            'nama_req' => $request->nama_req,
            'jumlah_req' => $request->jumlah_req,
            'alamat_req' => $request->alamat_req,
            'kota_req' => $request->kota_req,
            'status_req' => $request->status_req,
            'keterangan' => $request->keterangan,
            'id_user' => $request->id_user,
            'id_kategori' => $request->id_kategori
        ]);
        $getRequestRowID = DB::table('requests')->orderBy('id', 'desc')->first();
        $id = $getRequestRowID->id;
        if ($request->hasFile('gambar')) {
            $identity = 0;
            foreach($request->file('gambar') as $image) {
                $filename = $image->getClientOriginalName();
                $extensionTemp = explode(".", $filename);
                $extension = $extensionTemp[count($extensionTemp) - 1]; 
                $image->move("request_images/", strval($id) . "_request_" . strval($identity) . "." . $extension); //penamaan yg bukan array, penamaan array ada di registercontroller
                Gambar::create([
                    'url' => strval($id) . "_request_" . strval($identity) . "." . $extension,
                    'id_request' => $id
                ]);
                $identity++;
            }
        }

        return redirect('req')->with('status', 'Data Berhasil Ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Req  $req
     * @return \Illuminate\Http\Response
     */
    public function show(Req $req)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Req  $req
     * @return \Illuminate\Http\Response
     */
    public function edit(Req $req)
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
        $gambars = DB::table('gambars')
                ->where('id_request', '=', $req->id)
                ->get();
        //return view('pages.produk.edit', compact('produk', 'gambars', 'kategoris'));
        //$request = DB::table('requests')->where('id', $req)->get();
        //dd($req);
        return view('pages.request.edit', compact('req', 'response', 'gambars', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Req  $req
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Req $req)
    {
        $request->validate([
            'nama_req' => 'required',
            'jumlah_req' => 'required',
            'alamat_req' => 'required',
            'kota_req' => 'required',
            'status_req' => 'required',
            'id_kategori' => 'required'
        ]);
        Req::where('id', $req->id)
            ->update([
                'nama_req' => $request->nama_req,
                'jumlah_req' => $request->jumlah_req,
                'alamat_req' => $request->alamat_req,
                'kota_req' => $request->kota_req,
                'status_req' => $request->status_req,
                'keterangan' => $request->keterangan,
                'id_user' => $request->id_user,
                'id_kategori' => $request->id_kategori
            ]);
        return redirect('/req')->with('status', 'Data Request Order Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Req  $req
     * @return \Illuminate\Http\Response
     */
    public function destroy(Req $req)
    {
        Req::destroy($req->id);
        Gambar::destroy($req->id_request);
        return redirect()->back()->with('status', 'Data Request Order Berhasil Dihapus!');
    }
}
