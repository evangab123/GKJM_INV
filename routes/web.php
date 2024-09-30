<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\RoleController;
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
// Route::put('/pengguna/{pengguna}', [PenggunaController::class, 'update'])->name('pengguna.update');
// Route::put('/pengguna/{pengguna}/edit}', [PenggunaController::class, 'update'])->name('pengguna.edit');
Route::put('/pengguna/create}', [PenggunaController::class, 'update'])->middleware('role:SuperAdmin')->name('pengguna.create');
// Route::get('/pengguna/{pengguna}', [PenggunaController::class, 'show'])->name('pengguna.show');
// Route::put('/pengguna/{pengguna}', [PenggunaController::class, 'update'])->name('pengguna.update');


Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/blank', function () {
    return view('blank');
})->name('blank');

Route::middleware('auth')->group(function () {
    Route::resource('pengguna', PenggunaController::class);
});

Route::get('/pengguna', [PenggunaController::class, 'index'])
    ->middleware('role:SuperAdmin')
    ->name('pengguna.index');

Route::middleware('auth')->group(function () {
    Route::resource('role', RoleController::class);
});
Route::get('/role', [RoleController::class, 'index'])
    ->middleware('role:SuperAdmin')
    ->name('role.index');

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
