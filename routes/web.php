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
use App\Http\Controllers\KorbanController;

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\KategoriBencanaController;
use App\Http\Controllers\BencanaController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KategoriBantuanController;
use App\Http\Controllers\StokGudangController;

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

        Route::prefix('kebutuhan_harian')->name('kebutuhan_harian.')->group(function () {
            Route::get('/{dapur}',[KebutuhanHarianController::class, 'index'])->name('index');
            Route::get('/{dapur}/create',[KebutuhanHarianController::class, 'create'])->name('create');
            Route::post('/{dapur}',[KebutuhanHarianController::class, 'store'])->name('store');
            Route::get('/edit/{id}',[KebutuhanHarianController::class, 'edit'])->name('edit');
            Route::put('/update/{id}',[KebutuhanHarianController::class, 'update'])->name('update');
            Route::delete('/delete/{id}',[KebutuhanHarianController::class, 'destroy'])->name('destroy');
        });
    });


    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::model('manajemen_user', User::class);
        Route::resource('manajemen_user', UserController::class);
    });
    // Route::resource('management_distribusi/distribusi', DistribusiController::class);

    Route::resource('management_distribusi/distribusi', DistribusiController::class);
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

        // Korban
        Route::resource('management_korban/korban', KorbanController::class);
        Route::prefix('management-korban')
        ->name('management_korban.')
        ->group(function () {
            Route::get('korban/review-pdf', [KorbanController::class, 'reviewPdf'])
                ->name('korban.reviewPdf');

            Route::resource('korban', KorbanController::class);
        });

});

Route::middleware('auth')->group(function () {

    Route::resource('kategori_bencana', KategoriBencanaController::class);
    Route::resource('bencana', BencanaController::class);
    Route::resource('gudang', GudangController::class);
    Route::resource('kategori_bantuan', KategoriBantuanController::class);
    Route::resource('stok_gudang', StokGudangController::class);
});

require __DIR__ . '/auth.php';
