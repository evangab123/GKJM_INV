<?php

use App\Http\Controllers\BarangTerkunciController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenghapusanBarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PermissionController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/blank', function () {
    return view('blank');
})->name('blank');

// Rute Resource
Route::middleware('auth')->group(function () {
    Route::resource('pengguna', PenggunaController::class)
        ->middleware('role:Super Admin');

    Route::resource('role', RoleController::class)
        ->middleware('role:Super Admin', );

    Route::resource('hak', PermissionController::class)
        ->middleware('role:Super Admin');

    Route::resource('terkunci', BarangTerkunciController::class)
        ->middleware('role:Super Admin');

    Route::resource('barang', BarangController::class)
        ->middleware(['role:Super Admin||Admin Ruang||Majelis']);

    Route::resource('penghapusan', PenghapusanBarangController::class);
    Route::resource('pemakaian', PemakaianController::class);
    Route::resource('pengadaan', PengadaanController::class);
    Route::resource('peminjaman', PeminjamanController::class);
});

Route::get('/pengguna', [PenggunaController::class, 'index'])
    ->middleware('role:Super Admin')
    ->name('pengguna.index');

Route::get('/pengguna/{pengguna}/edit/permissions', [PenggunaController::class, 'getPermissionsByUser'])
    ->middleware('role:Super Admin')
    ->name('pengguna.permissions.edit');

Route::get('/role', [RoleController::class, 'index'])
    ->middleware('role:Super Admin')
    ->name('role.index');

Route::get('/hak', [PermissionController::class, 'index'])
    ->middleware('role:Super Admin')
    ->name('hak.index');

Route::post('/role/{role}/givepermissions', [RoleController::class, 'givePermission'])
    ->middleware('role:Super Admin')
    ->name('role.givepermissions');

Route::get('/roles/{role}/permissions', [RoleController::class, 'getPermissionsByRole'])
    ->middleware('role:Super Admin')
    ->name('role.permissions.edit');

Route::delete('/roles/{role}/permissions/{permission}', [RoleController::class, 'removePermission'])
    ->middleware('role:Super Admin')
    ->name('roles.permissions.destroy');


// Rute barang
Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
// Route::get('/barang/{kode_barang}', [BarangController::class, 'show'])->name('barang.show');

// // Middleware untuk mengelola barang yang dapat diakses oleh role tertentu
Route::put('/barang/{kode_barang}/edit', [BarangController::class, 'update_detail'])
    ->name('barang.update_detail');

Route::get('/barang/{kode_barang}/keterangan', [BarangController::class, 'showKeterangan'])
    ->name('barang.keterangan');

Route::post('/barang/{kode_barang}/penghapusan', [BarangController::class, 'penghapusanbarang'])->name('barang.penghapusanbarang');


Route::get('/keterangan/{id}/edit', [BarangController::class, 'editKeterangan'])
    ->name('keterangan.edit');

Route::post('/keterangan/store/{id}', [BarangController::class, 'storeKeterangan'])
    ->name('keterangan.store');

Route::delete('/keterangan/{id}', [BarangController::class, 'delKeterangan'])->name('keterangan.destroy');

// Route::delete('/penghapusan/{id}', [PenghapusanBarangController::class, 'destroy'])->name('penghapusan.destroy');



// Rute untuk pengadaan
Route::prefix('pengadaan')->group(function () {
    Route::get('/', [PengadaanController::class, 'index'])->name('pengadaan.index');
    Route::post('/', [PengadaanController::class, 'store'])->name('pengadaan.store');
    Route::put('{id}/approve', [PengadaanController::class, 'approve'])->name('pengadaan.approve');
    Route::put('{id}/reject', [PengadaanController::class, 'reject'])->name('pengadaan.reject');
    Route::put('{id}/buatbarang', [PengadaanController::class, 'CreateBarang'])->name('pengadaan.buatbarang');
    // Route::get('{id}/edit', [PengadaanController::class, 'edit'])->name('pengadaan.edit');
    // Route::put('{id}', [PengadaanController::class, 'update'])->name('pengadaan.update');
    // Route::delete('{id}', [PengadaanController::class, 'destroy'])->name('pengadaan.destroy');
});

Route::post('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])
    ->name('peminjaman.kembalikan');
Route::post('/pemakaian/{id}/kembalikan', [PemakaianController::class, 'kembalikan'])
    ->name('pemakaian.kembalikan');



