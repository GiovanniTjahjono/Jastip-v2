<?php

namespace App\Console\Commands;

use App\PenjualanPreorder;
use App\Produk;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class updateStatusProduk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'produk:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update the produk status according to estimasi_pengiriman';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $produks = DB::table('produks')->get();
        foreach ($produks as $data) {
            $estimasi_pengiriman = $data->estimasi_pengiriman;
            $waktu_sekarang = Carbon::now()->format('Y-m-d H:i:s');
            if ($estimasi_pengiriman < $waktu_sekarang) {
                Produk::where('id', $data->id)
                ->update([
                    'status_produk' => 2,
                ]);
                $preorder_produks = PenjualanPreorder::where('id_produk', $data->id)->get();
                foreach ($preorder_produks as $po) {
                    if ($po->status_order === 'menunggu') {
                        $saldo_lama = User::where('id', $po->id_user)->select('users.saldo')->get()[0]->saldo;
                        $saldo_baru = $saldo_lama + $po->total_harga;
                        User::where('id', $po->id_user)
                            ->update([
                                'saldo' => $saldo_baru
                            ]);
                        PenjualanPreorder::where('id', $po->id)
                        ->update([
                            'status_order' => 5,
                        ]);
                    }
                }
            }
        }
        //update status pengiriman bulk buys
        $penjualan_preorder_produks = PenjualanPreorder::where('id_produk', '!=', 'Null')
                                ->join('produks', 'produks.id', 'penjualan_preorders.id_produk')->get();
        foreach ($penjualan_preorder_produks as $data) {
            $waktu_sekarang = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
            $batas_waktu = strtotime($data->estimasi_pengiriman);
            $sisa_waktu = intval(($batas_waktu - $waktu_sekarang) / 60 / 60 / 24); //Mengasilkan Hari
            if($sisa_waktu < 0 && $data->status_order === 'menunggu') {
                $saldo_lama = User::where('id', $data->id_user)->select('users.saldo')->get()[0]->saldo;
                $saldo_baru = $saldo_lama + $data->total_harga;
                User::where('id', $data->id_user)
                    ->update([
                        'saldo' => $saldo_baru
                    ]);
                PenjualanPreorder::where('id', $data->id)
                    ->update([
                        'status_order' => 5
                    ]);
            }
        }
    }
}
