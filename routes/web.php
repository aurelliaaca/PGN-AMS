<?php

use App\Http\Controllers\NDAController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\PerangkatImportController;
use App\Exports\PerangkatExport;

use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\FasilitasImportController;
use App\Exports\FasilitasExport;

use App\Http\Controllers\AlatukurController;
use App\Http\Controllers\AlatukurImportController;
use App\Exports\AlatukurExport;

use App\Http\Controllers\JaringanController;
use App\Http\Controllers\JaringanImportController;
use App\Exports\JaringanExport;

use App\Http\Controllers\DataController;
use App\Http\Controllers\HistoriController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\VerifikasiDokumenController;


use App\Http\Controllers\PendaftaranController;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\SemantikController;




Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes(['verify' => true]);
Route::middleware('auth')->group(function () {

    // ------------------------------------------------------------------------ DATA ------------------------------------------------------------------------
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

    Route::get('/menu/data/datauser', [DataController::class, 'indexUser'])->name('datauser.index');
    Route::get('/user/create', [DataController::class, 'createUser'])->name('user.create');
    Route::post('/user/store', [DataController::class, 'storeUser'])->name('user.store');
    Route::get('/user/{id_user}/edit', [DataController::class, 'editUser'])->name('user.edit');
    Route::put('/user/{id_user}', [DataController::class, 'updateUser'])->name('user.update');
    Route::delete('/user/{id_user}', [DataController::class, 'destroyUser'])->name('user.destroy');

    Route::get('/site/create', [DataController::class, 'createSite'])->name('site.create');
    Route::post('/site/store', [DataController::class, 'storeSite'])->name('site.store');
    Route::get('/site/{id_site}/edit', [DataController::class, 'editSite'])->name('site.edit');
    Route::put('/site/{id_site}', [DataController::class, 'updateSite'])->name('site.update');
    Route::delete('/site/{id_site}', [DataController::class, 'destroySite'])->name('site.destroy');

    // ------------------------------------------------------------------------ RACK ------------------------------------------------------------------------

    Route::get('/menu/rack', [RackController::class, 'indexRack'])->name('rack.index');
    Route::get('/menu/rack/data', [RackController::class, 'getRackData'])->name('rack.data');
    Route::post('/rack/store', [RackController::class, 'storeRack'])->name('rack.store');
    Route::delete('/rack/{kode_region}/{kode_site}/{no_rack}', [RackController::class, 'destroy'])->name('rack.destroy');
    Route::delete('/rack/{kode_region}/{kode_site}/{no_rack}/{u}', [RackController::class, 'destroyData'])->name('datarack.destroy');

    Route::get('/rack/data', [RackController::class, 'getRacks'])->name('rack.getData');

    Route::post('/rack', [RackController::class, 'storeRack'])->name('rack.store');
    Route::delete('/rack/{kode_region}/{kode_site}/{no_rack}', [RackController::class, 'destroy'])->name('rack.destroy');
    Route::delete('/datarack/{kode_region}/{kode_site}/{no_rack}/{u}', [RackController::class, 'destroyData'])->name('datarack.destroy');

    Route::post('/load-racks', [App\Http\Controllers\RackController::class, 'loadRacks'])->name('load.racks');
    Route::get('/get-racks-by-region/{kode_region}', [HomeController::class, 'getRacksByRegion']);
    Route::get('/get-filtered-racks', [RackController::class, 'getFilteredRacks']);
    Route::get('/get-sites', [RackController::class, 'getSites'])->name(name: 'get.sites');
    Route::post('/rack/store', [RackController::class, 'store'])->name('rack.store');
    Route::post('/rack/destroydata', [RackController::class, 'destroyData'])->name('rack.destroydata');
    Route::post('/rack/destroy', [RackController::class, 'destroy'])->name('rack.destroy');


    // ------------------------------------------------------------------------ HISTORI ------------------------------------------------------------------------

    Route::get('/menu/histori', [HistoriController::class, 'indexHistori'])->name('histori.index');
    Route::get('/historiperangkat', [HistoriController::class, 'showHistoriPerangkat'])->name('histori.perangkat');
    Route::get('/historifasilitas', [HistoriController::class, 'showHistoriFasilitas'])->name('histori.fasilitas');
    Route::get('/historialatur', [HistoriController::class, 'showHistoriAlatukur'])->name('histori.alatukur');
    Route::get('/historijaringan', [HistoriController::class, 'showHistoriJaringan'])->name('histori.jaringan');

    // ------------------------------------------------------------------------ ASET ------------------------------------------------------------------------

    Route::get('/aset/perangkat', [PerangkatController::class, 'indexPerangkat'])->name('perangkat.index');
    Route::get('/perangkat/create', [PerangkatController::class, 'create'])->name('perangkat.create');
    Route::post('/perangkat/store', [PerangkatController::class, 'store'])->name('perangkat.store');
    Route::get('/perangkat/{id_perangkat}/edit', [PerangkatController::class, 'edit'])->name('perangkat.edit');
    Route::put('/perangkat/{id_perangkat}', [PerangkatController::class, 'update'])->name('perangkat.update');
    Route::delete('/perangkat/{id_perangkat}', [PerangkatController::class, 'destroy'])->name('perangkat.destroy');
    Route::post('/import-perangkat', [PerangkatImportController::class, 'import'])->name('import.perangkat');
    Route::post('export/perangkat', function (Request $request) {
        $regions = $request->input('regions'); // array atau null
        $format = $request->input('format');   // excel atau pdf
        if ($format === 'excel') {
            return Excel::download(new PerangkatExport($regions), 'dataperangkat.xlsx');
        } elseif ($format === 'pdf') {
            $data = (new PerangkatExport($regions))->collection();

            $pdf = Pdf::loadView('exports.exportpdf', ['data' => $data]);
            return $pdf->download('dataperangkat.pdf');
        } else {
            return back()->with('error', 'Format file tidak dikenali.');
        }
    });



    Route::get('/aset/fasilitas', [FasilitasController::class, 'indexFasilitas'])->name('fasilitas.index');
    Route::get('/fasilitas/create', [FasilitasController::class, 'create'])->name('fasilitas.create');
    Route::post('/fasilitas/store', [FasilitasController::class, 'store'])->name('fasilitas.store');
    Route::get('/fasilitas/{id_fasilitas}/edit', [FasilitasController::class, 'edit'])->name('fasilitas.edit');
    Route::put('/fasilitas/{id_fasilitas}', [FasilitasController::class, 'update'])->name('fasilitas.update');
    Route::delete('/fasilitas/{id_fasilitas}', [FasilitasController::class, 'destroy'])->name('fasilitas.destroy');
    Route::post('/import-fasilitas', [FasilitasImportController::class, 'import'])->name('import.fasilitas');
    Route::post('export/fasilitas', function (Request $request) {
        $regions = $request->input('regions'); // array atau null
        $format = $request->input('format');   // excel atau pdf
        if ($format === 'excel') {
            return Excel::download(new FasilitasExport($regions), 'datafasilitas.xlsx');
        } elseif ($format === 'pdf') {
            $data = (new FasilitasExport($regions))->collection();

            $pdf = Pdf::loadView('exports.exportpdf', ['data' => $data]);
            return $pdf->download('datafasilitas.pdf');
        } else {
            return back()->with('error', 'Format file tidak dikenali.');
        }
    });

    Route::get('/aset/alatukur', [AlatukurController::class, 'indexAlatukur'])->name('alatukur.index');
    Route::get('/alatukur/create', [AlatukurController::class, 'create'])->name('alatukur.create');
    Route::post('/alatukur/store', [AlatukurController::class, 'store'])->name('alatukur.store');
    Route::get('/alatukur/{id_alatukur}/edit', [AlatukurController::class, 'edit'])->name('alatukur.edit');
    Route::put('/alatukur/{id_alatukur}', [AlatukurController::class, 'update'])->name('alatukur.update');
    Route::delete('/alatukur/{id_alatukur}', [AlatukurController::class, 'destroy'])->name('alatukur.destroy');
    Route::post('/import-alatukur', [AlatukurImportController::class, 'import'])->name('import.alatukur');
    Route::post('export/alatukur', function (Request $request) {
        $regions = $request->input('regions');
        $format = $request->input('format');  
        if ($format === 'excel') {
            return Excel::download(new AlatukurExport($regions), 'dataalatukur.xlsx');
        } elseif ($format === 'pdf') {
            $data = (new AlatukurExport($regions))->collection();

            $pdf = Pdf::loadView('exports.exportpdf', ['data' => $data]);
            return $pdf->download('dataalatukur.pdf');
        } else {
            return back()->with('error', 'Format file tidak dikenali.');
        }
    });

    Route::get('/aset/jaringan', [JaringanController::class, 'indexJaringan'])->name('jaringan.index');
    Route::get('/jaringan/create', [JaringanController::class, 'create'])->name('jaringan.create');
    Route::post('/jaringan/store', [JaringanController::class, 'store'])->name('jaringan.store');
    Route::get('/jaringan/{id_jaringan}/edit', [JaringanController::class, 'edit'])->name('jaringan.edit');
    Route::put('/jaringan/{id_jaringan}', [JaringanController::class, 'update'])->name('jaringan.update');
    Route::delete('/jaringan/{id_jaringan}', [JaringanController::class, 'destroy'])->name('jaringan.destroy');
    Route::post('/import-jaringan', [JaringanImportController::class, 'import'])->name('import.jaringan');
    Route::post('export/jaringan', function (Request $request) {
        $regions = $request->input('regions'); // array atau null
        $format = $request->input('format');   // excel atau pdf
        if ($format === 'excel') {
            return Excel::download(new JaringanExport($regions), 'datajaringan.xlsx');
        } elseif ($format === 'pdf') {
            $data = (new JaringanExport($regions))->collection();

            $pdf = Pdf::loadView('exports.exportpdf', ['data' => $data]);
            return $pdf->download('datajaringan.pdf');
        } else {
            return back()->with('error', 'Format file tidak dikenali.');
        }
    });

    // Route::get('/jaringan', [JaringanController::class, 'jaringan'])->name('jaringan');
    // Route::post('/store-jaringan', [JaringanController::class, 'store'])->name('jaringan.store');
    // Route::get('/jaringan/tipes/{tipe}', [JaringanController::class, 'getTipeJaringan']);
    // Route::get('/jaringan/filter', [JaringanController::class, 'getJaringanByRegionAndTipe']);
    // Route::delete('/delete-jaringan/{id_jaringan}', [JaringanController::class, 'deleteJaringan'])->name('jaringan.delete');
    // Route::get('/edit-jaringan/{id_jaringan}', [JaringanController::class, 'editJaringan'])->name('jaringan.edit');
    // Route::post('/update-jaringan/{id_jaringan}', [JaringanController::class, 'updateJaringan'])->name('jaringan.update');
    // Route::get('/jaringan/{id_jaringan}/detail', [JaringanController::class, 'getDetail'])->name('jaringan.detail');
    // Route::get('/get-last-kode-site-insan', [JaringanController::class, 'getLastKodeSiteInsan']);
    // Route::post('/jaringan/import', [JaringanController::class, 'import'])->name('jaringan.import');
    // Route::post('/jaringan/export', [JaringanController::class, 'export'])->name('jaringan.export');
    // Route::get('/jaringan/{id}/lihat-detail', [JaringanController::class, 'lihatDetail'])->name('jaringan.lihatDetail');


    // Superadmin - melihat dan memverifikasi dokumen
    Route::get('/verifikasi', [VerifikasiDokumenController::class, 'index'])->name('verifikasi.superadmin.index');
    Route::post('/verifikasi/approve/{id}', [VerifikasiDokumenController::class, 'approve'])->name('verifikasi.approve');
    Route::post('/verifikasi/reject/{id}', [VerifikasiDokumenController::class, 'reject'])->name('verifikasi.reject');
    Route::post('/verifikasi/sign/{id}', [VerifikasiDokumenController::class, 'sign'])->name('verifikasi.sign');

    // User - upload dokumen dan lihat status
    Route::get('/verifikasi/user', [VerifikasiDokumenController::class, 'userIndex'])->name('verifikasi.dcaf.index');
    Route::get('/verifikasi/user/dcaf', [VerifikasiDokumenController::class, 'userDcafIndex'])->name('verifikasi.user.dcaf');
    Route::post('/verifikasi/user/upload', [VerifikasiDokumenController::class, 'upload'])->name('dokumen.store');

    // Routes untuk verifikasi NDA 
    Route::get('/verifikasi/nda', [NDAController::class, 'indexNdaSuperadmin'])->name('verifikasi.superadmin.nda');
    Route::post('/verifikasi/nda', [VerifikasiDokumenController::class, 'storeVerifNda'])->name('verifikasi.nda.store');
    Route::get('/pendaftaran/nda', [NDAController::class, 'indexNdaUser'])->name('verifikasi.user.nda');
    Route::post('/pendaftaran/nda/store', [NDAController::class, 'store'])->name('nda.store');
    Route::get('pendaftaran/nda/download/{id}', [VerifikasiDokumenController::class, 'downloadNda'])->name('nda.download');
    Route::put('/nda/{nda}', [VerifikasiDokumenController::class, 'update'])->name('nda.update');
    Route::delete('/nda/{nda}', [VerifikasiDokumenController::class, 'destroy'])->name('nda.destroy');

    // Routes untuk verifikasi DCAF
    Route::get('/verifikasi/dcaf', [VerifikasiDokumenController::class, 'indexDcaf'])->name('verifikasi.superadmin.dcaf');
    Route::post('/verifikasi/dcaf', [VerifikasiDokumenController::class, 'storeDcaf'])->name('verifikasi.dcaf.store');

    // Routes untuk aksi verifikasi
    Route::post('/verifikasi/nda/approve/{id}', [VerifikasiDokumenController::class, 'approveNda'])->name('verifikasi.approve.nda');
    Route::post('/verifikasi/nda/reject/{id}', [VerifikasiDokumenController::class, 'rejectNda'])->name('verifikasi.reject.nda');
    Route::post('/verifikasi/dcaf/approve/{id}', [VerifikasiDokumenController::class, 'approveDcaf'])->name('verifikasi.approve.dcaf');
    Route::post('/verifikasi/dcaf/reject/{id}', [VerifikasiDokumenController::class, 'rejectDcaf'])->name('verifikasi.reject.dcaf');

    // Route untuk upload foto
    Route::post('/upload-photo', [SemantikController::class, 'uploadPhoto'])->name('upload.photo');

    // Route untuk menghapus foto
    Route::delete('/photos/{id}', [SemantikController::class, 'deletePhoto'])->name('photos.delete');

    // Semantik
    Route::get('/semantik', [SemantikController::class, 'semantik'])->name('semantik');


    // Route untuk menampilkan view pendaftarandcaf
    Route::get('/pendaftarandcaf', [PendaftaranController::class, 'pendaftaranDCAF'])->name('pendaftarandcaf');
    Route::post('/pendaftaran-vms', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
   Route::get('/pendaftaran/download/{filename}', [PendaftaranController::class, 'download'])->name('pendaftaran.download');

    Route::get('/pendaftaran/dcaf', [PendaftaranController::class, 'pendaftaranDCAF'])->name('pendaftaran.dcaf');
    Route::get('/pendaftaran/ajukan-dcs', [PendaftaranController::class, 'ajukanDCS'])->name('pendaftaran.ajukan-dcs');

});

