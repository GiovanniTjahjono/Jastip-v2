<?php

namespace App\Http\Controllers;

use App\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Notifikasi  $notifikasi
     * @return \Illuminate\Http\Response
     */
    public function show(Notifikasi $notifikasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Notifikasi  $notifikasi
     * @return \Illuminate\Http\Response
     */
    public function edit(Notifikasi $notifikasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notifikasi  $notifikasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notifikasi $notifikasi)
    {
        //
        $notify = DB::table('notifikasis')
            ->where('notifikasis.id', $notifikasi->id)->get();
        //dd($notify[0]->link);
        Notifikasi::where('id', $notifikasi->id)
            ->update([
                'dibaca' => 'sudah'
            ]);
        if ($notify[0]->jenis == 'preorder') {
            return redirect($notify[0]->link);
        } else if ($notify[0]->jenis == 'bulkbuy') {
            return redirect($notify[0]->link);
        } else if ($notify[0]->jenis == 'request') {
            return redirect($notify[0]->link);
        } else if ($notify[0]->jenis == 'penawaran') {
            return redirect($notify[0]->link);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notifikasi  $notifikasi
     * @return \Illuminate\Http\Response
     */
    public function updateDibaca(Request $request, Notifikasi $notifikasi)
    {
        //
        //dd($notify[0]->link);
        Notifikasi::where('id_penerima', Auth::user()->id)
            ->update([
                'dibaca' => 'sudah'
            ]);
        return redirect('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notifikasi  $notifikasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notifikasi $notifikasi)
    {
        //
        Notifikasi::destroy($notifikasi->id);
    }
}
