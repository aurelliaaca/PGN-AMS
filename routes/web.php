<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\HistoriController;
use App\Http\Controllers\RackController;


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes(['verify' => true]);

Route::get('/data', [App\Http\Controllers\DataController::class, 'index'])->name('data');

Route::get('/menu/data/dataperangkat', [DataController::class, 'indexPerangkat'])->name('dataperangkat.index');
Route::get('/brand-perangkat/create', [DataController::class, 'createBrandPerangkat'])->name('brandperangkat.create');
Route::post('/brand-perangkat/store', [DataController::class, 'storeBrandPerangkat'])->name('brandperangkat.store');
Route::get('/brand-perangkat/{kode_brand}/edit', [DataController::class, 'editBrandPerangkat'])->name('brandperangkat.edit');
Route::put('/brand-perangkat/{kode_brand}', [DataController::class, 'updateBrandPerangkat'])->name('brandperangkat.update');
Route::delete('/brand-perangkat/{kode_brand}', [DataController::class, 'destroyBrandPerangkat'])->name('brandperangkat.destroy');

Route::get('/jenis-perangkat/create', [DataController::class, 'createJenisPerangkat'])->name('jenisperangkat.create');
Route::post('/jenis-perangkat/store', [DataController::class, 'storeJenisPerangkat'])->name('jenisperangkat.store');
Route::get('/jenis-perangkat/{kode_perangkat}/edit', [DataController::class, 'editJenisPerangkat'])->name('jenisperangkat.edit');
Route::put('/jenis-perangkat/{kode_perangkat}', [DataController::class, 'updateJenisPerangkat'])->name('jenisperangkat.update');
Route::delete('/jenis-perangkat/{kode_perangkat}', [DataController::class, 'destroyJenisPerangkat'])->name('jenisperangkat.destroy');

Route::get('/menu/data/datafasilitas', [DataController::class, 'indexFasilitas'])->name('datafasilitas.index');
Route::get('/brand-fasilitas/create', [DataController::class, 'createBrandFasilitas'])->name('brandfasilitas.create');
Route::post('/brand-fasilitas/store', [DataController::class, 'storeBrandFasilitas'])->name('brandfasilitas.store');
Route::get('/brand-fasilitas/{kode_brand}/edit', [DataController::class, 'editBrandFasilitas'])->name('brandfasilitas.edit');
Route::put('/brand-fasilitas/{kode_brand}', [DataController::class, 'updateBrandFasilitas'])->name('brandfasilitas.update');
Route::delete('/brand-fasilitas/{kode_brand}', [DataController::class, 'destroyBrandFasilitas'])->name('brandfasilitas.destroy');

Route::get('/jenis-fasilitas/create', [DataController::class, 'createJenisFasilitas'])->name('jenisfasilitas.create');
Route::post('/jenis-fasilitas/store', [DataController::class, 'storeJenisFasilitas'])->name('jenisfasilitas.store');
Route::get('/jenis-fasilitas/{kode_fasilitas}/edit', [DataController::class, 'editJenisFasilitas'])->name('jenisfasilitas.edit');
Route::put('/jenis-fasilitas/{kode_fasilitas}', [DataController::class, 'updateJenisFasilitas'])->name('jenisfasilitas.update');
Route::delete('/jenis-fasilitas/{kode_fasilitas}', [DataController::class, 'destroyJenisFasilitas'])->name('jenisfasilitas.destroy');

Route::get('/menu/data/dataalatukur', [DataController::class, 'indexAlatukur'])->name('dataalatukur.index');
Route::get('/brand-alatukur/create', [DataController::class, 'createBrandAlatukur'])->name('brandalatukur.create');
Route::post('/brand-alatukur/store', [DataController::class, 'storeBrandAlatukur'])->name('brandalatukur.store');
Route::get('/brand-alatukur/{kode_brand}/edit', [DataController::class, 'editBrandAlatukur'])->name('brandalatukur.edit');
Route::put('/brand-alatukur/{kode_brand}', [DataController::class, 'updateBrandAlatukur'])->name('brandalatukur.update');
Route::delete('/brand-alatukur/{kode_brand}', [DataController::class, 'destroyBrandAlatukur'])->name('brandalatukur.destroy');

Route::get('/jenis-alatukur/create', [DataController::class, 'createJenisAlatukur'])->name('jenisalatukur.create');
Route::post('/jenis-alatukur/store', [DataController::class, 'storeJenisAlatukur'])->name('jenisalatukur.store');
Route::get('/jenis-alatukur/{kode_alatukur}/edit', [DataController::class, 'editJenisAlatukur'])->name('jenisalatukur.edit');
Route::put('/jenis-alatukur/{kode_alatukur}', [DataController::class, 'updateJenisAlatukur'])->name('jenisalatukur.update');
Route::delete('/jenis-alatukur/{kode_alatukur}', [DataController::class, 'destroyJenisAlatukur'])->name('jenisalatukur.destroy');

Route::get('/menu/data/dataregion', [DataController::class, 'indexRegion'])->name('dataregion.index');
Route::get('/region/create', [DataController::class, 'createRegion'])->name('region.create');
Route::post('/region/store', [DataController::class, 'storeRegion'])->name('region.store');
Route::get('/region/{id_region}/edit', [DataController::class, 'editRegion'])->name('region.edit');
Route::put('/region/{id_region}', [DataController::class, 'updateRegion'])->name('region.update');
Route::delete('/region/{id_region}', [DataController::class, 'destroyRegion'])->name('region.destroy');

Route::get('/site/create', [DataController::class, 'createSite'])->name('site.create');
Route::post('/site/store', [DataController::class, 'storeSite'])->name('site.store');
Route::get('/site/{id_site}/edit', [DataController::class, 'editSite'])->name('site.edit');
Route::put('/site/{id_site}', [DataController::class, 'updateSite'])->name('site.update');
Route::delete('/site/{id_site}', [DataController::class, 'destroySite'])->name('site.destroy');

Route::get('/aset/perangkat', [PerangkatController::class, 'indexPerangkat'])->name('perangkat.index');
Route::get('/perangkat/create', [PerangkatController::class, 'create'])->name('perangkat.create');
Route::post('/perangkat/store', [PerangkatController::class, 'store'])->name('perangkat.store');
Route::get('/perangkat/{id_perangkat}/edit', [PerangkatController::class, 'edit'])->name('perangkat.edit');
Route::put('/perangkat/{id_perangkat}', [PerangkatController::class, 'update'])->name('perangkat.update');
Route::delete('/perangkat/{id_perangkat}', [PerangkatController::class, 'destroy'])->name('perangkat.destroy');

use App\Http\Controllers\PerangkatImportController;

Route::post('/import-perangkat', [PerangkatImportController::class, 'import'])->name('import.perangkat');
use App\Exports\PerangkatExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('export/perangkat', function () {
    return Excel::download(new PerangkatExport, 'data_perangkat.xlsx');
});

Route::get('/menu/data/histori', [HistoriController::class, 'indexHistori'])->name('histori.index');
Route::get('/menu/rack', [RackController::class, 'indexRack'])->name('rack.index');
