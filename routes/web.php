<?php

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
