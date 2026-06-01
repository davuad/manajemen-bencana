<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DapurUmumController;
use App\Http\Controllers\KebutuhanHarianController;
use App\Http\Controllers\PengambilanController;
use App\Http\Controllers\PoskoController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\StokPoskoController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::prefix('management-posko')->name('management_posko.')->group(function () {
        Route::resource('posko', PoskoController::class);
        Route::resource('dapur_umum', DapurUmumController::class);
        Route::resource('kebutuhan_harian', KebutuhanHarianController::class);
    });


    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::model('manajemen_user', User::class);
        Route::resource('manajemen_user', UserController::class);
    });
   Route::prefix('manajemen_barang')->name('manajemen_barang.')->group(function () {
        Route::resource('petugas', PetugasController::class);

        Route::resource('pengambilan', PengambilanController::class)
        ->except(['destroy', 'show']);
        Route::get('pengambilan/{id}', [PengambilanController::class, 'show'])
        ->name('pengambilan.show');
        Route::put('pengambilan/{id}/batal', [PengambilanController::class, 'batal'])
        ->name('pengambilan.batal');


Route::resource('pengembalian', PengembalianController::class);

/**
 * AJAX GET SINGLE
 */
Route::get(
    'pengembalian/get/{id}',
    [PengembalianController::class, 'getPengambilan']
)->name('pengembalian.get');

/**
 * AJAX GET BERDASARKAN BENCANA
 */
Route::get(
    'pengembalian/get-bencana/{id}',
    [PengembalianController::class, 'getByBencana']
)->name('pengembalian.getBencana');
/**
 * BULK ACTION (tetap kamu pakai)
 */
Route::post(
    'pengembalian/bulk-delete',
    [PengembalianController::class, 'bulkDelete']
)->name('pengembalian.bulkDelete');

Route::post(
    'pengembalian/bulk-selesai',
    [PengembalianController::class, 'bulkSelesai']
)->name('pengembalian.bulkSelesai');

Route::post(
    'pengembalian/bulk-status',
    [PengembalianController::class, 'bulkStatus']
)->name('pengembalian.bulkStatus');

        Route::resource('manajemen_barang/stok_posko', StokPoskoController::class);


        
    }); 
});

require __DIR__ . '/auth.php';
