<?php

namespace App\Http\Controllers;

use App\Pesan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Notifikasi;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB;

class PesanController extends Controller
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
        // $queryuser = DB::table('users')
        //     ->join('pesans', function ($join) {
        //         $join->on('users.id', '=', 'pesans.id_penerima')
        //             ->orON('users.id', '=', 'pesans.id_pengirim')
        //             ->where('pesans.id_penerima', 'users.id')
        //             ->where('pesans.id_pengirim', Auth::user()->id)
        //             ->orWhere('pesans.id_penerima', Auth::user()->id)
        //             ->Where('pesans.id_pengirim', 'users.id')
        //             // ->where(function ($query) {
        //             //     $query->where('id_penerima', Auth::user()->id);
        //             // })
        //         ;
        //     })
        //     ->select('users.*', DB::raw('max(pesans.waktu_kirim) as waktu_kirim'))
        //     ->groupBy('users.id')
        //     ->orderBy('waktu_kirim', 'desc')->get();
        $id = Auth::user()->id;
        $queryuser = DB::select("SELECT u.*, MAX(p.waktu_kirim)as waktu_kirim FROM pesans as p JOIN users as u ON p.id_penerima = u.id OR p.id_pengirim = u.id WHERE p.id_penerima = u.id AND p.id_pengirim = $id OR p.id_penerima = $id AND p.id_pengirim = u.id GROUP BY u.id ORDER BY p.waktu_kirim DESC");
        $cek = 'user';
        return view('pages.pesan.pesanDetail', compact('queryuser', 'cek'));
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
        Pesan::create([
            'id_pengirim' => Auth::user()->id,
            'id_penerima' => $request->id_penerima,
            'isi_pesan' => $request->isi_pesan,
            'waktu_kirim' => date("Y-m-d H:i:s"),
            'dibaca' => 'belum'
        ]);
        //adding notify
        // Notifikasi::create([
        //     'isi_notifikasi' => 'coba',
        //     'waktu_kirim' => date("Y-m-d H:i:s"),
        //     'jenis' => 'pesan',
        //     'dibaca' => 'belum',
        //     'id_penerima' => $request->id_penerima,
        //     'id_trigger' => Auth::user()->id
        // ]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pesan  $pesan
     * @return \Illuminate\Http\Response
     */
    public function show(Pesan $pesan)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function roomchat(Request $request)
    {
        $idlawan = $request->pesan;
        Pesan::where('id_penerima', Auth::user()->id)
            ->where('id_pengirim', $idlawan)
            ->update([
                'dibaca' => 'sudah'
            ]);
        //query pesan
        $pesan = DB::table('pesans')
            // ->where('id_penerima', Auth::user()->id)
            // ->orWhere('id_penerima', $request->pesan)
            // ->where('id_pengirim', Auth::user()->id)
            // ->orWhere('id_pengirim', $request->pesan)
            // ->orderBy('id', 'asc')->get();
            //->where('id_penerima', Auth::user()->id)
            //->orWhere('id_penerima', $request->pesan)
            ->where(function ($query) use ($request) {
                $query->where('id_penerima', Auth::user()->id)
                    ->orWhere('id_penerima', $request->pesan);
            })
            ->where(function ($query) use ($request) {
                $query->where('id_pengirim', Auth::user()->id)
                    ->orWhere('id_pengirim', $request->pesan);
            })
            ->orderBy('id', 'asc')->get();

        $user1 = DB::table('users')
            ->where('id', Auth::user()->id)->get();
        $user2 = DB::table('users')
            ->where('id', $request->pesan)->get();
        $id = Auth::user()->id;
        //query list user chat
        $queryuser = DB::select("SELECT u.*, MAX(p.waktu_kirim)as waktu_kirim 
                                    FROM pesans as p 
                                    JOIN users as u 
                                    ON p.id_penerima = u.id OR p.id_pengirim = u.id 
                                    WHERE p.id_penerima = u.id AND p.id_pengirim = $id 
                                    OR p.id_penerima = $id AND p.id_pengirim = u.id 
                                    GROUP BY u.id ORDER BY p.waktu_kirim DESC");
        $cek = 'room';
        //dd($idlawan);
        return view('pages.pesan.pesan', compact('pesan', 'user1', 'user2', 'cek', 'queryuser'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pesan  $pesan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pesan $pesan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pesan  $pesan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pesan $pesan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pesan  $pesan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pesan $pesan)
    {
        //
    }
}
