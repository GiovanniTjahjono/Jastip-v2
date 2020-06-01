<?php

namespace App\Http\Controllers;

use App\Request as RequestModel;
use App\User;
use App\Gambar;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
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
        return view('pages.request.create', compact('response'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        //validation
        $req->validate([
            'nama_req' => 'required',
            'jumlah_req' => 'required',
            'alamat_req' => 'required',
            'kota_req' => 'required',
            'status_req' => 'required',
            'gambar' => 'required',
        ]);
        //penamaan gambar/foto

        $getIDForFileName = DB::table('requests')->orderBy('id', 'desc')->get();
        $id = 0;
        if (count($getIDForFileName) > 0) {
            $id = $getIDForFileName->first()->id + 1;
        }
        //
        RequestModel::create([
            'nama_req' => $req->nama_req,
            'jumlah_req' => $req->jumlah_req,
            'alamat_req' => $req->alamat_req,
            'kota_req' => $req->kota_req,
            'status_req' => $req->status_req,
            'keterangan' => $req->keterangan,
            'id_user' => $req->id_user
        ]);
        $getRequestRowID = DB::table('requests')->orderBy('id', 'desc')->first();
        $id = $getRequestRowID->id;
        if ($req->hasFile('gambar')) {
            $identity = 0;
            foreach($req->file('gambar') as $image) {
                $filename = $image->getClientOriginalName();
                $extensionTemp = explode(".", $filename);
                $extension = $extensionTemp[count($extensionTemp) - 1]; 
                $image->move("produk_images/", strval($id+1) . "_produk" . strval($identity) . "." . $extension); //penamaan yg bukan array, penamaan array ada di registercontroller
                Gambar::create([
                    'url' => strval($id+1) . "_request" . strval($identity) . "." . $extension,
                    'id_request' => $id+1
                ]);
                $identity++;
            }
        }

        return redirect('request')->with('status', 'Data Berhasil Ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestModel $req)
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
        return view('pages.request.edit', compact('req', 'response'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Request $req)
    {
        $request->validate([
            'nama_req' => 'required',
            'jumlah_req' => 'required',
            'alamat_req' => 'required',
            'kota_req' => 'required',
            'status_req' => 'required',
        ]);
        //penamaan gambar/foto
        $id = DB::table('reqs')->orderBy('id', 'desc')->first()->id + 1;
        $request->file('gambar')->move("request_images/", strval($id) . "_request.jpg"); //penamaan yg bukan array, penamaan array ada di registercontroller
        $filename = $id . '_request.jpg';

        //
        Request::where('id', $req->id)
            ->update([
                'nama_req' => $request->nama_req,
                'jumlah_req' => $request->jumlah_req,
                'alamat_req' => $request->alamat_req,
                'kota_req' => $request->kota_req,
                'status_req' => $request->status_req,
                'keterangan' => $request->keterangan,
                'id_user' => $request->id_user,
                'gambar' => $filename
            ]);
        return redirect('request')->with('status', 'Data Request Order Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Request::destroy($request->id);
        return redirect('request')->with('status', 'Data Request Order Berhasil Dihapus!');
    }
}
