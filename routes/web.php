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
