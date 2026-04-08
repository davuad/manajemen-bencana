<?php

use App\Http\Controllers\DapurUmumController;
use App\Http\Controllers\KebutuhanHarianController;
use App\Http\Controllers\PoskoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/p', function () {
    return view('p');
});

Route::get('/posko', [PoskoController::class, 'index'])->name('management_posko.posko.index');
Route::get('/posko/create', [PoskoController::class, 'create'])->name('management_posko.posko.create');
Route::post('/posko', [PoskoController::class, 'store'])->name('management_posko.posko.store');
Route::get('/posko/{id}/edit', [PoskoController::class, 'edit'])->name('management_posko.posko.edit');
Route::put('/posko/{id}', [PoskoController::class, 'update'])->name('management_posko.posko.update');
Route::delete('/posko/{id}', [PoskoController::class, 'destroy'])->name('management_posko.posko.destroy');

Route::get('/dapur-umum', [DapurUmumController::class, 'index'])->name('management_posko.dapur_umum.index');
Route::get('/dapur-umum/create', [DapurUmumController::class, 'create'])->name('management_posko.dapur_umum.create');
Route::post('/dapur-umum', [DapurUmumController::class, 'store'])->name('management_posko.dapur_umum.store');
Route::get('/dapur-umum/{id}/edit', [DapurUmumController::class, 'edit'])->name('management_posko.dapur_umum.edit');
Route::put('/dapur-umum/{id}', [DapurUmumController::class, 'update'])->name('management_posko.dapur_umum.update');
Route::delete('/dapur-umum/{id}', [DapurUmumController::class, 'destroy'])->name('management_posko.dapur_umum.destroy');

Route::get('/kebutuhan-harian', [KebutuhanHarianController::class, 'index'])->name('management_posko.kebutuhan_harian.index');
Route::get('/kebutuhan-harian/create', [KebutuhanHarianController::class, 'create'])->name('management_posko.kebutuhan_harian.create');
Route::post('/kebutuhan-harian', [KebutuhanHarianController::class, 'store'])->name('management_posko.kebutuhan_harian.store');
Route::get('/kebutuhan-harian/{id}/edit', [KebutuhanHarianController::class, 'edit'])->name('management_posko.kebutuhan_harian.edit');
Route::put('/kebutuhan-harian/{id}', [KebutuhanHarianController::class, 'update'])->name('management_posko.kebutuhan_harian.update');
Route::delete('/kebutuhan-harian/{id}', [KebutuhanHarianController::class, 'destroy'])->name('management_posko.kebutuhan_harian.destroy');
