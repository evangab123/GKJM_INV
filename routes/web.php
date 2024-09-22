<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BasicController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\BarangController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');
Route::put('/basic/{pengguna}', [BasicController::class, 'update'])->name('basic.update');
Route::get('/pengguna/{pengguna}', [PenggunaController::class, 'show'])->name('pengguna.show');
Route::put('/pengguna/{pengguna}', [PenggunaController::class, 'update'])->name('pengguna.update');


Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/blank', function () {
    return view('blank');
})->name('blank');

Route::middleware('auth')->group(function () {
    Route::resource('basic', BasicController::class);
});

Route::get('/basic', [BasicController::class, 'index'])
    ->middleware('role:SuperAdmin')
    ->name('basic.index');

Route::middleware('auth')->group(function () {
    Route::resource('barang', BarangController::class);
});

Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
Route::get('/barang/{kode_barang}', [BarangController::class, 'show'])->name('barang.show');
Route::put('/barang/{kode_barang}', [BarangController::class, 'update'])->name('barang.update');

Route::put('/barang/{kode_barang}/edit', [BarangController::class, 'update_detail'])->name('barang.update_detail');
Route::get('/barang/{kode_barang}/keterangan', [BarangController::class, 'showKeterangan'])->name('barang.keterangan');
Route::get('/keterangan/{id}/edit', [BarangController::class, 'editKeterangan'])->name('keterangan.edit');
Route::put('/keterangan/{id}', [BarangController::class, 'updateKeterangan'])->name('keterangan.update');
Route::post('/keterangan/store/{id}', [BarangController::class, 'storeKeterangan'])->name('keterangan.store');


