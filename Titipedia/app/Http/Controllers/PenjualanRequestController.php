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
use App\Penawaran;
use App\PenjualanPreorder;
use App\Produk;

class PenjualanRequestController extends Controller
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
        $penjualan_requests = DB::table('penjualan_requests')
            ->join('penawarans', 'penawarans.id', '=', 'penjualan_requests.id_penawaran')
            ->join('requests', 'requests.id', '=', 'penawarans.id_request')
            ->join('kategoris', 'kategoris.id', '=', 'requests.id_kategori')
            ->where('penjualan_requests.id_user', Auth::user()->id)
            ->select('penjualan_requests.*', 'requests.nama_req as nama_req', 'requests.jumlah_req as jumlah_req', 'kategoris.nama_kategori as nama_kategori')
            ->get();


        $batas_waktu = [];
        foreach ($penjualan_requests as $data) {
            $sisa_waktu = 0;
            $waktu_sekarang = strtotime(date(Carbon::now()->format('Y-m-d H:i:s')));
            $waktu_pembelian = strtotime(date('Y-m-d', strtotime($data->created_at . ' + 3 days')));
            $sisa_waktu = strval(intval(($waktu_pembelian - $waktu_sekarang) / 60 / 60 / 24)); //Mengasilkan Hari
            array_push($batas_waktu, $sisa_waktu);
        }


        return view('pages.penawaran.show_request', compact('penjualan_requests', 'batas_waktu'));
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

        $batas_waktu = [];
        foreach ($penjualan_requests as $data) {
            $sisa_waktu = 0;
            $waktu_sekarang = strtotime(date(Carbon::now()->format('Y-m-d H:i:s')));
            $waktu_pembelian = strtotime(date('Y-m-d', strtotime($data->created_at . ' + 3 days')));
            $sisa_waktu = strval(intval(($waktu_pembelian - $waktu_sekarang) / 60 / 60 / 24)); //Mengasilkan Hari
            array_push($batas_waktu, $sisa_waktu);
        }

        return view('pages.penawaran.show_request', compact('penjualan_requests', 'batas_waktu'));;
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
            Penawaran::where('id', $request->id_penawaran)
                ->update([
                    'status' => 'diterima'
                ]);
            Penawaran::where('id_request', $query_penawaran[0]->id_request)
                ->where('status', 'menunggu')
                ->update([
                    'status' => 'ditolak'
                ]);
            Notifikasi::create([
                'isi_notifikasi' => "Request Dari " . Auth::user()->name,
                'waktu_kirim' => date("Y-m-d H:i:s"),
                'jenis' => 'request',
                'dibaca' => 'belum',
                'link' => '/penjualan-penawaran',
                'id_penerima' => $query_penawaran[0]->id_penawar,
                'id_trigger' => Auth::user()->id
            ]);
            $query_penawar_notif = DB::table('penawarans')
                ->where('status', 'ditolak')
                ->where('id_request', $query_penawaran[0]->id_request)
                ->get();
            foreach ($query_penawar_notif as $penawar) {
                Notifikasi::create([
                    'isi_notifikasi' => "Penawaran Ditolak " . Auth::user()->name,
                    'waktu_kirim' => date("Y-m-d H:i:s"),
                    'jenis' => 'penawaran',
                    'dibaca' => 'belum',
                    'link' => '/penawaran/' . $request->id_penawaran,
                    'id_penerima' => $penawar->id_penawar,
                    'id_trigger' => Auth::user()->id
                ]);
            }
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\PenjualanRequest  $penjualanRequest
     * @return \Illuminate\Http\Response
     */
    public function editRatingReq(PenjualanRequest $penjualanRequest)
    {
        return view('pages.penjualan_preorder.rating_request', compact('penjualanRequest'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenjualanRequest  $penjualanRequest
     * @return \Illuminate\Http\Response
     */


    public function updateRatingReq(Request $request, PenjualanRequest $penjualanRequest)
    {

        $request->validate([
            'review' => 'required',
            'rating' => 'required'
        ]);

        //dd($request->review);
        
        PenjualanRequest::where('id', $penjualanRequest->id)
            ->update([
                'review' => $request->review,
                'rating' => $request->rating
            ]);
                
       //-------------------------------------------------------------------------------------------

       $id_penjual = PenjualanRequest::join('penawarans', 'penawarans.id', 'penjualan_requests.id_penawaran')->where('penjualan_requests.id', $penjualanRequest->id_penawaran)->get()[0]->id_penawar;
       // Ambil semua PO yang sudah terjual dari penjual x
       $produks = PenjualanPreorder::join('produks', 'produks.id', 'penjualan_preorders.id_produk')
           ->where('produks.id_user', $id_penjual)
           ->where('penjualan_preorders.rating', '!=', 0)
           ->get();
       // Ambil semua PO Bulkbuy yang sudah terjual dari penjual x
       $produkBulkBuys = PenjualanPreorder::join('produk_bulk_buys', 'produk_bulk_buys.id', 'penjualan_preorders.id_bulkbuy')
           ->where('produk_bulk_buys.id_user', $id_penjual)
           ->where('penjualan_preorders.rating', '!=', 0)
           ->get();
       // Ambil semua Penawaran yang sudah terjual dari penjual x
       $penawarans = PenjualanRequest::join('penawarans', 'penawarans.id', 'penjualan_requests.id_penawaran')
           ->join('requests', 'requests.id', 'penawarans.id_request')
           ->where('penawarans.id_penawar', $id_penjual)
           ->where('penjualan_requests.rating', '!=', 0)
           ->get();
       
       $jumlah_rating = intval(count($produks)) + intval(count($produkBulkBuys)) + intval(count($penawarans));
       $nilai_rating = 0;
       
       foreach ($produks as $data) {
           $nilai_rating += intval($data->rating);
       }
       foreach ($produkBulkBuys as $data) {
           $nilai_rating += intval($data->rating);
       }
       foreach ($penawarans as $data) {
           $nilai_rating += intval($data->rating);
       }

       $rating_user = $nilai_rating/$jumlah_rating;
       User::where('id', $id_penjual)->update(['rating' => $rating_user]);

        return redirect('/pembelian-request/daftar_pembelian_request/' . Auth::user()->id)->with('status', 'Pemberian Rating dan Review Berhasil!');
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
