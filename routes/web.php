<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DapurUmumController;
use App\Http\Controllers\KebutuhanHarianController;
use App\Http\Controllers\PoskoController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\DetailDistribusiController;
use App\Http\Controllers\PenerimaDistribusiController;
use App\Http\Controllers\DetailPaketController;
use App\Http\Controllers\DistribusiPaketController;
use App\Http\Controllers\PaketBantuanController;
use App\Http\Controllers\KorbanController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\WargaTerdampakController;
use App\Http\Controllers\JadwalController;

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
            Route::get('/{dapur}', [KebutuhanHarianController::class, 'index'])->name('index');
            Route::get('/{dapur}/create', [KebutuhanHarianController::class, 'create'])->name('create');
            Route::post('/{dapur}', [KebutuhanHarianController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [KebutuhanHarianController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [KebutuhanHarianController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [KebutuhanHarianController::class, 'destroy'])->name('destroy');
        });
    });


    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {


            Route::model('manajemen_user', User::class);
            Route::resource('manajemen_user', UserController::class);

            Route::prefix('management-distribusi')
                ->name('management_distribusi.')
                ->group(function () {

                    Route::resource('distribusi', DistribusiController::class);
                    Route::resource('detail_distribusi', DetailDistribusiController::class);
                    Route::resource('penerima_distribusi', PenerimaDistribusiController::class);
                    Route::resource('paket_bantuan', PaketBantuanController::class);
                    Route::resource('detail_paket', DetailPaketController::class);
                    Route::resource('distribusi_paket', DistribusiPaketController::class);

                    Route::patch(
                        'distribusi_paket/{id}/selesai',
                        [DistribusiPaketController::class, 'selesai']
                    )->name('distribusi_paket.selesai');

                    Route::get(
                        'distribusi-paket/{id}',
                        [DistribusiPaketController::class, 'show']
                    )->name('distribusi_paket.show');

                    Route::prefix('management-distribusi')
                    ->name('management_distribusi.')
                    ->group(function () {

                    Route::resource('distribusi', DistribusiController::class);
                    Route::resource('detail_distribusi', DetailDistribusiController::class);
                });
                });
        });
    // Route::resource('management_distribusi/distribusi', DistribusiController::class);

    Route::resource('management_distribusi/distribusi', DistribusiController::class);



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
Route::middleware('auth')->group(function () {
    Route::get('/data-desa', [DesaController::class, 'index'])->name('desa.index');
    Route::get('/data-desa/create', [DesaController::class, 'create'])->name('desa.create');
    Route::post('/data-desa/store', [DesaController::class, 'store'])->name('desa.store');
    Route::get('/data-desa/detail/{id}', [DesaController::class, 'detail'])->name('desa.detail');
    Route::get('/data-desa/edit/{id}', [DesaController::class, 'edit'])->name('desa.edit');
    Route::post('/data-desa/update/{id}', [DesaController::class, 'update'])->name('desa.update');
    Route::get('/data-desa/delete/{id}', [DesaController::class, 'delete'])->name('desa.delete');

    Route::get('/warga-terdampak', [WargaTerdampakController::class, 'index'])->name('warga.index');
    Route::get('/warga-terdampak/create', [WargaTerdampakController::class, 'create'])->name('warga.create');
    Route::post('/warga-terdampak/store', [WargaTerdampakController::class, 'store'])->name('warga.store');
    Route::get('/warga-terdampak/detail/{id}', [WargaTerdampakController::class, 'detail'])->name('warga.detail');
    Route::get('/warga-terdampak/edit/{id}', [WargaTerdampakController::class, 'edit'])->name('warga.edit');
    Route::post('/warga-terdampak/update/{id}', [WargaTerdampakController::class, 'update'])->name('warga.update');
    Route::get('/warga-terdampak/delete/{id}', [WargaTerdampakController::class, 'delete'])->name('warga.delete');
    Route::post('/warga-terdampak/ubah-status/{id}', [WargaTerdampakController::class, 'ubahStatus'])->name('warga.ubahStatus');
});



// Jadwal Layanan (Khusus Admin)

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
    Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

    // Route custom untuk cetak PDF
    Route::get('/jadwal/cetak-pdf', [JadwalController::class, 'cetak_pdf'])->name('jadwal.cetak');
});

Route::middleware(['auth', 'role:relawan'])->prefix('relawan')->name('relawan.')->group(function () {
    
});
Route::middleware(['auth', 'role:kadus'])->prefix('kadus')->name('kadus.')->group(function () {
    
});
Route::middleware(['auth', 'role:kabid'])->prefix('kabid')->name('kabid.')->group(function () {
    
});
Route::middleware(['auth', 'role:desa'])->prefix('desa')->name('desa.')->group(function () {
    
});
Route::middleware(['auth', 'role:ketua_tim'])->prefix('ketua_tim')->name('ketua_tim.')->group(function () {
    
});

