<?php

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

Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index');
Auth::routes();

Route::get('/profile', 'HomeController@index')->name('pages/home');

//Produk
Route::resource('/produk', 'ProdukController'); //Untuk menggunakan method patch, put, delete
Route::get('/produk', 'ProdukController@index');
Route::get('/produk/create', 'ProdukController@create');
Route::get('/produk/{produk}', 'ProdukController@show'); // harus dibawwah, krn kalau diatas akan dibaca menampilkan produk yg idnya create
Route::post('/produk', 'ProdukController@store');
Route::delete('/produk/{produk}', 'ProdukController@destroy');
Route::get('/produk/{produk}/edit', 'ProdukController@edit');
Route::patch('/produk/{produk}', 'ProdukController@update');

//Produk Bulk Buy
Route::resource('/produk-bulk-buy', 'ProdukBulkBuyController'); //Untuk menggunakan method patch, put, delete
Route::get('/produk-bulk-buy', 'ProdukBulkBuyController@index');
Route::get('/produk-bulk-buy/create', 'ProdukBulkBuyController@create');
Route::get('/produk-bulk-buy/{produk}', 'ProdukBulkBuyController@show'); // harus dibawwah, krn kalau diatas akan dibaca menampilkan produk yg idnya create
Route::post('/produk-bulk-buy', 'ProdukBulkBuyController@store');
Route::delete('/produk-bulk-buy/{produk}', 'ProdukBulkBuyController@destroy');
Route::get('/produk-bulk-buy/{produk}/edit', 'ProdukBulkBuyController@edit');
Route::patch('/produk-bulk-buy/{produk}', 'ProdukBulkBuyController@update');

//request
Route::resource('/request', 'RequestController'); //Untuk menggunakan method patch, put, delete
Route::get('/request', 'RequestController@index');
Route::get('/request/create', 'RequestController@create');
Route::post('/request', 'RequestController@store');
Route::delete('/request/{req}', 'RequestController@destroy');
Route::get('/request/{req}/edit', 'RequestController@edit');
Route::patch('/request/{req}', 'RequestController@update');

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

//pesan
Route::get('/pesan', 'PesanController@index');
Route::get('/pesan/{pesan}', 'PesanController@roomchat');
Route::post('/kirim', 'PesanController@store');

//Profile
Route::get('/profile', 'UserController@index');

//Gambar
Route::delete('/gambar/{gambar}/{produk}', 'GambarController@destroy');
Route::get('/edit-gambar/{gambar}', 'GambarController@edit');
Route::post('/tambah_gambar', 'GambarController@store');
Route::get('/edit-gambar-bulk/{gambar}', 'GambarController@editBulkBuy');
Route::post('/tambah_gambar_bulk', 'GambarController@storeBulkBuy');
//RajaOngkir
Route::post('/order/get_price', 'PenjualanPreorderController@RajaOngkir');
