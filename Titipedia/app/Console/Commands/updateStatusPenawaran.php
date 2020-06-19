<?php

namespace App\Console\Commands;

use App\PenjualanPreorder;
use App\PenjualanRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;


class updateStatusPenawaran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update the penawaran shipment status according to 3 days limit since the penawaran is made';

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
        //update status pengiriman penawaran
        $penjualan_request = PenjualanRequest::get();
                                
        foreach ($penjualan_request as $data) {
            $waktu_sekarang = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
            $waktu_pembelian = strtotime(date('Y-m-d', strtotime($data->created_at. ' + 2 days')));
            $sisa_waktu = strval(intval(($waktu_pembelian - $waktu_sekarang) / 60 / 60 / 24)); //Mengasilkan Hari
            
            if($sisa_waktu < 0 && $data->status_penjualan_req === 'menunggu dikirim') {
                $saldo_lama = User::where('id', $data->id_user)->select('users.saldo')->get()[0]->saldo;
                $saldo_baru = $saldo_lama + $data->total_harga;
                User::where('id', $data->id_user)
                    ->update([
                        'saldo' => $saldo_baru
                    ]);
                PenjualanRequest::where('id', $data->id)
                    ->update([
                        'status_penjualan_req' => 5
                    ]);
            }
        }
    }
}
