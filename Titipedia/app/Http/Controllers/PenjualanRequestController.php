<?php

namespace App\Http\Controllers;

use App\PenjualanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Req;
use App\Notifikasi;
use App\PenjualanPreorder;

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
            ->select('penjualan_requests.*', 'requests.nama_req as nama_req', 'requests.jumlah_req as jumlah_req', 'kategoris.nama_kategori as nama_kategori')
            ->get();

        //-------------Dicoba-------------
        $sisa_waktu = 0;
        if (count($penjualan_requests) > 0) {
            $waktu_sekarang = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
            $waktu_pembelian = strtotime(date('Y-m-d', strtotime($penjualan_requests[0]->created_at . ' + 3 days')));
            $sisa_waktu = strval(intval(($waktu_pembelian - $waktu_sekarang) / 60 / 60 / 24)); //Mengasilkan Hari
        }


        return view('pages.penjualan_preorder.penjualan_request', compact('penjualan_requests', 'sisa_waktu'));
    }
    public function indexRequestTerjual()
    {
        $penjualan_request = DB::table('penawarans')
            ->join('penjualan_requests', 'penjualan_requests.id_penawaran', 'penawarans.id')
            ->join('requests', 'requests.id', 'penawarans.id_request')
            ->join('users', 'users.id', 'requests.id_user')
            ->where('penawarans.id_penawar', Auth::user()->id)
            ->select('penjualan_requests.*', 'users.name', 'users.no_hp', 'requests.nama_req as nama_req', 'requests.jumlah_req as jumlah_req', 'requests.kota_req as kota_req', 'requests.alamat_req as alamat_req', 'requests.keterangan as keterangan')
            ->get();
        $sisa_waktu = 0;
        if (count($penjualan_request) > 0) {
            $waktu_sekarang = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
            $waktu_pembelian = strtotime(date('Y-m-d', strtotime($penjualan_request[0]->created_at . ' + 3 days')));
            $sisa_waktu = strval(intval(($waktu_pembelian - $waktu_sekarang) / 60 / 60 / 24)); //Mengasilkan Hari
        }

        return view('pages.penawaran.penjualan_penawaran', compact('penjualan_request', 'sisa_waktu'));
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
    public function showPembelianRequest($id_request)
    {
        //dd('a');
        $id_user = $id_request;
        $penjualan_requests = DB::table('penjualan_requests')
            ->join('penawarans', 'penawarans.id', '=', 'penjualan_requests.id_penawaran')
            ->join('requests', 'requests.id', '=', 'penawarans.id_request')
            ->join('kategoris', 'kategoris.id', '=', 'requests.id_kategori')
            ->where('penjualan_requests.id_user', $id_user)
            ->select('penjualan_requests.*', 'requests.nama_req as nama_req', 'requests.jumlah_req as jumlah_req', 'kategoris.nama_kategori as nama_kategori')
            ->get();
        //$ordsers = DB::table('prenjualan_preorders')->where('id_user', '=', $id)->get();
        //dd($penjualan_requests);
        //dd($id_user);
        return view('pages.penawaran.show_request', compact('penjualan_requests'));;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenjualanRequest  $penjualanPreorder
     * @return \Illuminate\Http\Response
     */
    public function konfirmasiRequest(PenjualanRequest $penjualanRequest)
    {
        //dd($penjualanRequest);
        PenjualanRequest::where('id', $penjualanRequest->id)
            ->update([
                'status_penjualan_req' => 3
            ]);
        $user_penjual = DB::table('penjualan_requests')
            ->join('penawarans', 'penjualan_requests.id_penawaran', '=', 'penawarans.id')
            ->join('users', 'users.id', '=', 'penawarans.id_penawar')
            ->where('penjualan_requests.id', $penjualanRequest->id)
            ->select('users.*', 'penjualan_requests.total_harga as total_harga')->get();
        $saldo_terbaru = $user_penjual[0]->saldo + $user_penjual[0]->total_harga;
        //dd($saldo_terbaru);
        User::where('id', $user_penjual[0]->id)
            ->update([
                'saldo' => $saldo_terbaru
            ]);
        return redirect()->back()->with('status', 'Data Berhasil Diubah!');
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
        if (Auth::user()->saldo >= $totalHarga) {
            PenjualanRequest::create([
                'kode_transaksi' => 'R' . Carbon::now()->format('YmdHis'),
                'service' => explode(",", $request->tipeService)[1],
                'ongkir' => explode(",", $request->tipeService)[0],
                'tanggal_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
                'kurir' => 'Tiki',
                'total_harga' => $totalHarga,
                'status_penjualan_req' => 1,
                'id_penawaran' => $request->id_penawaran,
                'id_user' => Auth::user()->id
            ]);

            $saldo_terbaru = Auth::user()->saldo - $totalHarga;
            User::where('id', Auth::user()->id)
                ->update([
                    'saldo' => $saldo_terbaru
                ]);
            $query_penawaran = DB::table('penawarans')
                ->where('penawarans.id', $request->id_penawaran)->get();
            Notifikasi::create([
                'isi_notifikasi' => "Request Dari " . Auth::user()->name,
                'waktu_kirim' => date("Y-m-d H:i:s"),
                'jenis' => 'request',
                'dibaca' => 'belum',
                'link' => '/penjualan-penawaran',
                'id_penerima' => $query_penawaran[0]->id_penawar,
                'id_trigger' => Auth::user()->id
            ]);
            Req::where('id', $query_penawaran[0]->id_request)
                ->update([
                    'status_req' => 2
                ]);
            return redirect('/pembelian-request');
        } else {
            return redirect()->back()->with('status', 'Saldo Anda Tidak Cukup!');
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
        //dd($penjualanRequest->kode_transaksi);
        //
        return view('pages.penjualan_preorder.edit_request', compact('penjualanRequest'));
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
        $request->validate([
            'nomor_resi' => 'required'
        ]);

        //dd($penjualanRequest->id);
        PenjualanRequest::where('id', $penjualanRequest->id)
            ->update([
                'nomor_resi' => $request->nomor_resi,
                'tanggal_pengiriman' => Carbon::now()->format('Y-m-d H:i:s'),
                'status_penjualan_req' => 2
            ]);
        return redirect('/penjualan-penawaran')->with('status', 'Data Berhasil Diubah!');
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
        // dd($penjualanPreorder->id_produk);
        PenjualanRequest::where('id', $penjualanRequest->id)
            ->update([
                'status_penjualan_req' => 5
            ]);
        $harga = $penjualanRequest->total_harga;
        $saldo_user = DB::table('users')
            ->where('id', $penjualanRequest->id_user)
            ->get();
        $saldo_baru = $harga + $saldo_user[0]->saldo;
        User::where('id', $penjualanRequest->id_user)
            ->update([
                'saldo' => $saldo_baru
            ]);
        return redirect('/penjualan-penawaran')->with('status', 'Data Berhasil Dibatalkan!');
    }
}
