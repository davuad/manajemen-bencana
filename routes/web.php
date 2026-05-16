<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DapurUmumController;
use App\Http\Controllers\KebutuhanHarianController;
use App\Http\Controllers\PoskoController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\DetailDistribusiController;
use App\Http\Controllers\DetailPaketController;
use App\Http\Controllers\DistribusiPaketController;
use App\Http\Controllers\PaketBantuanController;

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
    // Route::resource('management_distribusi/distribusi', DistribusiController::class);
    Route::prefix('management-distribusi')
        ->name('management_distribusi.')
        ->group(function () {

            Route::resource('distribusi', DistribusiController::class);
            Route::resource('detail_distribusi', DetailDistribusiController::class);
            Route::resource('paket_bantuan', PaketBantuanController::class);
            Route::resource('detail_paket', DetailPaketController::class);
            Route::resource('distribusi_paket', DistribusiPaketController::class);

            Route::patch('distribusi_paket/{id}/selesai', [DistribusiPaketController::class, 'selesai'])
                ->name('distribusi_paket.selesai');
            Route::get('/distribusi-paket/{id}', [DistribusiPaketController::class, 'show'])
                ->name('management_distribusi.distribusi_paket.show');
        });
});

require __DIR__ . '/auth.php';
