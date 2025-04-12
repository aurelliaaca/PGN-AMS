<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes(['verify' => true]);

Route::get('/data', [App\Http\Controllers\DataController::class, 'index'])->name('data');

Route::get('/menu/perangkat/dataperangkat', [DataController::class, 'indexPerangkat'])->name('dataperangkat.index');
Route::get('/brand-perangkat/create', [DataController::class, 'createBrandPerangkat'])->name('brandperangkat.create');
Route::post('/brand-perangkat/store', [DataController::class, 'storeBrandPerangkat'])->name('brandperangkat.store');
Route::get('/brand-perangkat/{brandPerangkat}/edit', [DataController::class, 'editBrandPerangkat'])->name('brandperangkat.edit');
Route::put('/brand-perangkat/{brandPerangkat}', [DataController::class, 'updateBrandPerangkat'])->name('brandperangkat.update');
Route::delete('/brand-perangkat/{brandPerangkat}', [DataController::class, 'destroyBrandPerangkat'])->name('brandperangkat.destroy');
