<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        \Bezhanov\Faker\ProviderCollectionHelper::addAllProvidersTo($faker);
        foreach (range(0, 10) as $i) {
            DB::table('produks')->insert([
                'nama' => $faker->productName,
                'stok' => 9,
                'harga_jasa' => 80000,
                'harga_produk' => 2000,
                'berat' => 1,
                'keterangan' => '',
                'id_user' => 1,
                'asal_pengiriman' => 'Surabaya',
                'asal_negara' => 'Indonesia',
                'id_kategori' => 1,
                'status_produk' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'estimasi_pengiriman' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            DB::table('gambars')->insert([
               'id_produk' => $i,
               'url' => 'produk.jpg'
            ]);
        }
    }
}
