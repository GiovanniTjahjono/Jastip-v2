<?php

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//---------Penamaan ROUTE harus sama dengan nama Controller------------
//---------Penamaan PARAMETER ROUTE harus sama dengan nama PARAMETER Controller dan Model------------

Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index');
Auth::routes();

Route::get('/profile', 'HomeController@index')->name('pages/home');
//kategori
Route::get('/kategori/{kategori}', 'KategoriController@show');
//cari
Route::get('/cari', 'HomeController@cari');
//Produk
//Route::resource('/produk', 'ProdukController'); //Untuk menggunakan method patch, put, delete
Route::get('/produk', 'ProdukController@index');
Route::get('/produk/create', 'ProdukController@create');
Route::get('/produk/{produk}', 'ProdukController@show'); // harus dibawwah, krn kalau diatas akan dibaca menampilkan produk yg idnya create
Route::post('/produk', 'ProdukController@store');
Route::delete('/produk/{produk}', 'ProdukController@destroy');
Route::get('/produk/{produk}/edit', 'ProdukController@edit');
Route::patch('/produk/{produk}', 'ProdukController@update');

//Produk Bulk Buy
//Route::resource('/produk-bulk-buy', 'ProdukBulkBuyController'); //Untuk menggunakan method patch, put, delete
Route::get('/produk-bulk-buy', 'ProdukBulkBuyController@index');
Route::get('/produk-bulk-buy/create', 'ProdukBulkBuyController@create');
Route::get('/produk-bulk-buy/{produkBulkBuy}', 'ProdukBulkBuyController@show'); // harus dibawwah, krn kalau diatas akan dibaca menampilkan produk yg idnya create
Route::post('/produk-bulk-buy', 'ProdukBulkBuyController@store');
Route::delete('/produk-bulk-buy/{produkBulkBuy}', 'ProdukBulkBuyController@destroy');
Route::get('/produk-bulk-buy/{produkBulkBuy}/edit', 'ProdukBulkBuyController@edit');
Route::patch('/produk-bulk-buy/{produkBulkBuy}', 'ProdukBulkBuyController@update');

//request
Route::resource('/req', 'ReqController'); //Untuk menggunakan method patch, put, delete
/*
Route::get('/request', 'ReqController@index');
Route::get('/request/create', 'ReqController@create');
Route::post('/request', 'ReqController@store');
Route::delete('/request/{req}', 'ReqController@destroy');
Route::get('/request/{req}/edit', 'ReqController@edit');
Route::patch('/request/{req}', 'ReqController@update');
*/
//Penawaran
Route::get('/penawaran/{request}', 'PenawaranController@index');
Route::get('/penawaran/{request}/create', 'PenawaranController@create');
Route::post('/penawaran', 'PenawaranController@store');
Route::delete('/penawaran/{penawaran}', 'PenawaranController@destroy');




Route::resource('/permintaan', 'PermintaanController');

//User
//Route::post('/tambahsaldo', 'UserController@update');
Route::get('/Profile/{profile}/edit', 'UserController@edit');

//topup
Route::get('/topup', 'MutasiSaldoController@index');
Route::post('/tambahsaldo', 'MutasiSaldoController@store');
Route::post('/tariksaldo', 'MutasiSaldoController@withdraw');

//pesan
Route::get('/pesan', 'PesanController@index');
Route::get('/pesan/{pesan}', 'PesanController@chat');

//Penjualan Pre-order
Route::get('/order/{produk}', 'PenjualanPreorderController@showProduk');
Route::post('/order/confirm', 'PenjualanPreorderController@store');
Route::get('/order/daftar_pembelian_preorder/{id}', 'PenjualanPreorderController@show');

//Update status preorder
Route::get('/terjual', 'PenjualanPreorderController@indexPreorderTerjual');
Route::get('/terjual/{penjualanPreorder}', 'PenjualanPreorderController@edit');
Route::patch('/terjual/{penjualanPreorder}', 'PenjualanPreorderController@update');
Route::delete('/terjual/{penjualanPreorder}', 'PenjualanPreorderController@destroy');
Route::get('/terjual-konfirmasi/{penjualanPreorder}', 'PenjualanPreorderController@konfirmasiPreorder');

//Penjualan BulkBuy
Route::get('/bulkbuy/{produkBulkBuy}', 'PenjualanPreorderController@showBulkBuy');
Route::post('/bulkbuy/confirm', 'PenjualanPreorderController@storeBulkBuy');
Route::get('/bulkbuy/daftar_pembelian_preorder/{id_bulk}', 'PenjualanPreorderController@showPembelianBulkBuy');

//Update status bulkbuy
Route::get('/penjualan-bulk', 'PenjualanPreorderController@indexBulkBuyTerjual');
Route::get('/penjualan-bulk/{penjualanPreorder}', 'PenjualanPreorderController@editPenjualanBulk');
Route::get('/konfirmasi/{penjualanPreorder}', 'PenjualanPreorderController@konfirmasiBulkBuy');
Route::patch('/penjualan-bulk/{penjualanPreorder}', 'PenjualanPreorderController@updatePenjualanBulk');
Route::delete('/penjualan-bulk/{penjualanPreorder}', 'PenjualanPreorderController@destroyPenjualanBulk');

//pesan
Route::get('/pesan', 'PesanController@index');
Route::get('/pesan/{pesan}', 'PesanController@roomchat');
Route::post('/kirim', 'PesanController@store');

//Profile
Route::get('/profile', 'UserController@index');

//Gambar
Route::resource('/gambar', 'GambarController');
Route::delete('/gambar/{gambar}/{produk}', 'GambarController@destroy');
Route::delete('/gambar-bulk-buy/{gambar}/{produkBulkBuy}', 'GambarController@destroyBulkBuy');
Route::get('/edit-gambar/{gambar}', 'GambarController@edit');
Route::post('/tambah_gambar', 'GambarController@store');
Route::get('/edit-gambar-bulk/{gambar}', 'GambarController@editBulkBuy');
Route::post('/tambah_gambar_bulk', 'GambarController@storeBulkBuy');
Route::get('edit-gambar-request/{gambar}', 'GambarController@editRequest');
Route::post('/tambah_gambar_request', 'GambarController@storeRequest');
Route::delete('/hapus_gambar_request/{gambar}/{req}', 'GambarController@destroyRequest');
//Route::post('/tambah_gambar_bulk/{gambar}/edit', 'GambarController@editBulkBuy');

//RajaOngkir
Route::post('/order/get_price', 'PenjualanPreorderController@RajaOngkir');
