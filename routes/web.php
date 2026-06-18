<?php


use App\Http\Controllers\AnakTerpisahController;
use App\Http\Controllers\BencanaController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengaduanBencanaController;
use App\Http\Controllers\DapurUmumController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DetailDistribusiController;
use App\Http\Controllers\PenerimaDistribusiController;
use App\Http\Controllers\DetailPaketController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\DistribusiPaketController;
use App\Http\Controllers\PaketBantuanController;
use App\Http\Controllers\KorbanController;
use App\Http\Controllers\WargaTerdampakController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RelawanController;
use App\Http\Controllers\JadwalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriBencanaController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KategoriBantuanController;
use App\Http\Controllers\PenjemputanAnakController;
use App\Http\Controllers\KebutuhanHarianController;
use App\Http\Controllers\PoskoController;
use App\Http\Controllers\StokGudangController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\SumberBarangMasukController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;

use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PengambilanController;
use App\Http\Controllers\PengembalianController;
use App\Models\User;

// --- Public Routes ---
Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('verified')->name('dashboard');

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// --- Main Admin Routes (Semua Menggunakan Prefix /admin dan Name admin.) ---
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource('anak_terpisah', AnakTerpisahController::class);

        Route::get('penjemputan', [PenjemputanAnakController::class, 'index'])
            ->name('penjemputan.index');

        Route::get('penjemputan/{anak_id}/jemput', [PenjemputanAnakController::class, 'formJemput'])
            ->name('penjemputan.jemput');

        Route::post('penjemputan', [PenjemputanAnakController::class, 'store'])
            ->name('penjemputan.store');

        Route::get('penjemputan/{id}', [PenjemputanAnakController::class, 'show'])
            ->name('penjemputan.show');

        // --- System Management ---
        Route::model('management_user', User::class);
        Route::resource('management-user', UserController::class)->names('management_user');

        // --- Pengaduan Bencana ---
        Route::get('/pengaduan', [PengaduanBencanaController::class, 'index'])->name('pengaduan_bencana.index');
        Route::get('/pengaduan/create', [PengaduanBencanaController::class, 'create'])->name('pengaduan_bencana.create');
        Route::post('/pengaduan/store', [PengaduanBencanaController::class, 'store'])->name('pengaduan_bencana.store');
        Route::get('/pengaduan/{id}', [PengaduanBencanaController::class, 'show']);
        Route::put('/pengaduan/{id}', [PengaduanBencanaController::class, 'update']);
        Route::delete('/pengaduan/{id}', [PengaduanBencanaController::class, 'destroy']);
        Route::get('/kebutuhan/{id}', [PengaduanBencanaController::class, 'detailKebutuhan']);
        Route::get('/foto/{id}', [PengaduanBencanaController::class, 'detailFoto']);
        Route::delete('/foto/{id}', [PengaduanBencanaController::class, 'hapusFoto']);

        // --- Management Posko ---
        Route::prefix('management-posko')->name('management_posko.')->group(function () {
            // Route::resource('posko', PoskoController::class);
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

        // --- Jadwal Layanan ---
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
        Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
        Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
        Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
        Route::get('/jadwal/cetak-pdf', [JadwalController::class, 'cetak_pdf'])->name('jadwal.cetak');

        // --- Management Distribusi ---
        Route::prefix('management-distribusi')->name('management_distribusi.')->group(function () {
            Route::resource('distribusi', DistribusiController::class);
            Route::resource('detail_distribusi', DetailDistribusiController::class);
            Route::get('penerima_distribusi',[PenerimaDistribusiController::class, 'index'])->name('penerima.index');

            Route::get(
                'penerima_distribusi/create',
                [PenerimaDistribusiController::class, 'create']
            )->name('penerima.create');

            Route::post(
                'penerima_distribusi/store',
                [PenerimaDistribusiController::class, 'store']
            )->name('penerima.store');

            Route::get(
                'penerima_distribusi/edit/{id}',
                [PenerimaDistribusiController::class, 'edit']
            )->name('penerima.edit');

            Route::put(
                'penerima_distribusi/update/{id}',
                [PenerimaDistribusiController::class, 'update']
            )->name('penerima.update');

            Route::delete(
                'penerima_distribusi/delete/{id}',
                [PenerimaDistribusiController::class, 'destroy']
            )->name('penerima.destroy');
            Route::resource('paket_bantuan', PaketBantuanController::class);
            Route::resource('detail_paket', DetailPaketController::class);
            Route::resource('distribusi_paket', DistribusiPaketController::class);

            Route::patch('distribusi_paket/{id}/selesai', [DistribusiPaketController::class, 'selesai'])->name('distribusi_paket.selesai');
            Route::get('distribusi-paket/{id}', [DistribusiPaketController::class, 'show'])->name('distribusi_paket.show');
        });

        // --- Management Korban ---
        Route::prefix('management-korban')->name('management_korban.')->group(function () {
            Route::get('korban/review-pdf', [KorbanController::class, 'reviewPdf'])->name('korban.reviewPdf');
            Route::resource('korban', KorbanController::class);
        });

        // --- Data Master ---
        Route::resource('kategori_bencana', KategoriBencanaController::class);
        Route::resource('bencana', BencanaController::class);
        Route::resource('gudang', GudangController::class);
        Route::resource('kategori_bantuan', KategoriBantuanController::class);
        Route::resource('stok_gudang', StokGudangController::class);

        // --- Gudang Logistik ---
        Route::resource('jenis-barang', JenisBarangController::class);
        Route::resource('sumber-barang', SumberBarangMasukController::class);
        Route::resource('barang', BarangController::class);
        Route::resource('barang-masuk', BarangMasukController::class);

        // --- Data Desa & Warga Terdampak ---
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

        // --- Management Pegawai ---
        Route::prefix('management-pegawai')->name('management_pegawai.')->group(function () {
            Route::resource('pegawai', PegawaiController::class);
            Route::resource('relawan', RelawanController::class);
        });

        // --- Management Barang ---
        Route::prefix('management-barang')->name('management_barang.')->group(function () {
            Route::resource('petugas', PetugasController::class);
            Route::resource('pengambilan', PengambilanController::class);
            Route::resource('pengembalian', PengembalianController::class);
});
    });

// =========================================================================
// --- FITUR JADWAL LAYANAN PASCA BENCANA (MANDIRI / POLOSAN DI LUAR) ---
// =========================================================================

// 1. ROUTE JADWAL ADMIN (BISA CRUD + CETAK)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
    Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    Route::get('/jadwal/cetak-pdf', [JadwalController::class, 'cetak_pdf'])->name('jadwal.cetak');
});

// 2. ROUTE JADWAL KABID (BISA LIHAT + CETAK)
Route::prefix('kabid')->name('kabid.')->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/cetak-pdf', [JadwalController::class, 'cetak_pdf'])->name('jadwal.cetak');
});

// 3. ROUTE JADWAL RELAWAN (HANYA LIHAT)
Route::prefix('relawan')->name('relawan.')->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
});

// 4. ROUTE JADWAL KADUS (HANYA LIHAT)
Route::prefix('kadus')->name('kadus.')->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
});

// 5. ROUTE JADWAL DESA (HANYA LIHAT)
Route::prefix('desa')->name('desa.')->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
});

// 6. ROUTE JADWAL KETUA TIM (HANYA LIHAT)
Route::prefix('ketua_tim')->name('ketua_tim.')->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
});

// 7. ROUTE JADWAL PETUGAS (HANYA LIHAT)
Route::prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
});

// 8. ROUTE JADWAL PEGAWAI (HANYA LIHAT)
Route::prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
});
// --- Role Placeholders (Kosong) ---
Route::middleware(['auth', 'role:relawan'])->prefix('relawan')->name('relawan.')->group(function () {
    Route::prefix('management-posko')->name('management_posko.')->group(function () {
        Route::get('/posko', [PoskoController::class, 'index'])
            ->name('posko.index');

        Route::get('/dapur-umum', [DapurUmumController::class, 'index'])
            ->name('dapur_umum.index');
    });

    Route::prefix('kebutuhan_harian')->name('kebutuhan_harian.')->group(function () {
        Route::get('/{dapur}', [KebutuhanHarianController::class, 'index'])->name('kebutuhan_harian.index');
    });
});

Route::middleware(['auth', 'role:kadus'])->prefix('kadus')->name('kadus.')->group(function () {
    Route::prefix('management-posko')->name('management_posko.')->group(function () {
        Route::get('/posko', [PoskoController::class, 'index'])
            ->name('posko.index');

        Route::get('/dapur-umum', [DapurUmumController::class, 'index'])
            ->name('dapur_umum.index');
    });

});
Route::middleware(['auth', 'role:kabid'])
    ->prefix('kabid')
    ->name('kabid.')
    ->group(function () {

        // =====================
        // MANAGEMENT POSKO
        // =====================
        Route::prefix('management-posko')
            ->name('management_posko.')
            ->group(function () {

                Route::get('/posko', [PoskoController::class, 'index'])
                    ->name('posko.index');

                Route::get('/dapur-umum', [DapurUmumController::class, 'index'])
                    ->name('dapur_umum.index');
            });

        // =====================
        // PENGADUAN BENCANA
        // =====================
        Route::get('/pengaduan',
            [PengaduanBencanaController::class, 'kabidIndex'])
            ->name('pengaduan.index');

        Route::get('/pengaduan/{id}/verifikasi',
            [PengaduanBencanaController::class, 'verifikasi'])
            ->name('pengaduan.verifikasi');

        Route::put('/pengaduan/{id}/verifikasi',
            [PengaduanBencanaController::class, 'simpanVerifikasi'])
            ->name('pengaduan.simpan');
});

Route::middleware(['auth', 'role:desa'])->prefix('desa')->name('desa.')->group(function () {
    Route::prefix('management-posko')->name('management_posko.')->group(function () {
        Route::get('/posko', [PoskoController::class, 'index'])
            ->name('posko.index');

        Route::get('/dapur-umum', [DapurUmumController::class, 'index'])
            ->name('dapur_umum.index');
    });
});
Route::middleware(['auth', 'role:ketua_tim'])
    ->prefix('ketua_tim')
    ->name('ketua_tim.')
    ->group(function () {

        Route::get(
            '/pengaduan',
            [PengaduanBencanaController::class, 'ketuaTimIndex']
        )->name('pengaduan.index');

        Route::get(
            '/pengaduan/{id}/selesai',
            [PengaduanBencanaController::class, 'formSelesai']
        )->name('pengaduan.selesai');

        Route::put(
            '/pengaduan/{id}/selesai',
            [PengaduanBencanaController::class, 'simpanSelesai']
        )->name('pengaduan.simpan');
});

Route::middleware(['auth', 'role:relawan|kadus|desa'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/pengaduan',
            [PengaduanBencanaController::class, 'userIndex'])
            ->name('pengaduan.index');

        Route::get('/pengaduan/create',
            [PengaduanBencanaController::class, 'userCreate'])
            ->name('pengaduan.create');

        Route::post('/pengaduan/store',
            [PengaduanBencanaController::class, 'userStore'])
            ->name('pengaduan.store');
        Route::get('/pengaduan/{id}',
            [PengaduanBencanaController::class, 'showUser'])
            ->name('pengaduan.show');
});

// Route::middleware([
//     'auth',
//     'role:admin|relawan'
// ])->prefix('management_posko')
//     ->name('management_posko.')
//     ->group(function () {

//         Route::resource('posko', PoskoController::class);
//     });

