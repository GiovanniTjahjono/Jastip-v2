<?php

namespace App\Http\Controllers;

use App\PenjualanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PenjualanRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penjualan_requests = DB::table('penjualan_requests')
                ->join('penawarans', 'penawarans.id', '=', 'penjualan_requests.id_penawaran')
                ->join('requests', 'requests.id', '=', 'penawarans.id_request')
                ->join('kategoris', 'kategoris.id', '=', 'requests.id_kategori')
                ->where('penjualan_requests.id_user', Auth::user()->id)
                ->get();
        /*
        $penjualan_requests = DB::table('penjualan_requests')
                ->join('penawarans', 'penawarans.id', '=', 'penjualan_requests.id_penawaran')
                ->join('requests', 'requests.id', '=', 'penawarans.id_request')
                ->join('kategoris', 'kategoris.id', '=', 'requests.id_kategori')
                ->where('penjualan_requests.id_user', Auth::user()->id)
                ->get();
        */
        return view('pages.penjualan_preorder.penjualan_request', compact('penjualan_requests'));
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
   
        $request->validate([
            'hargaTotalnya' => 'required',
            'nama_kota' => 'required',
            'tipeService' => 'required'
        ]);
        $totalHarga = $request->hargaTotalnya;
        if($totalHarga > Auth::user()->saldo) {
            return redirect()->back()->with('status', 'Saldo Anda Tidak Cukup!');
        } else {
            PenjualanRequest::create([
                'kode_transaksi' => 'R'.Carbon::now()->format('YmdHis'),
                'service' => explode(",", $request->tipeService)[1],
                'ongkir' => explode(",", $request->tipeService)[0],
                'tanggal_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
                'kurir' => 'Tiki',
                'total_harga' => $totalHarga,
                'status_penjualan_req' => 1,
                'id_penawaran' => $request->id_penawaran,
                'id_user' => Auth::user()->id
            ]);
            
            return redirect('/pembelian-request');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PenjualanRequest  $penjualanRequest
     * @return \Illuminate\Http\Response
     */
    public function show(PenjualanRequest $penjualanRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PenjualanRequest  $penjualanRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(PenjualanRequest $penjualanRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenjualanRequest  $penjualanRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PenjualanRequest $penjualanRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PenjualanRequest  $penjualanRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(PenjualanRequest $penjualanRequest)
    {
        //
    }
}
