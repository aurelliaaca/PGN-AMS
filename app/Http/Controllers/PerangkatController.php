<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\BrandPerangkat;
use App\Models\JenisPerangkat;
use App\Models\ListPerangkat;
use App\Models\Region;
use App\Models\Site;
use App\Models\HistoriPerangkat;
use App\Models\Rack;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PerangkatController extends Controller
{
    public function indexPerangkat(Request $request)
{
    $regions = Region::select('kode_region', 'nama_region')->orderBy('nama_region')->get();
    $sites = Site::select('kode_region', 'nama_site', 'kode_site')->orderBy('nama_site')->get();
    $types = JenisPerangkat::select('kode_perangkat', 'nama_perangkat')->orderBy('nama_perangkat')->get();
    $brands = BrandPerangkat::select('kode_brand', 'nama_brand')->orderBy('nama_brand')->get();
    $users = User::select('id','name')->orderBy('name')->get();
    $racks = Rack::select('kode_region', 'kode_site', 'no_rack')
    ->distinct()
    ->get();


    $query = ListPerangkat::with(['region', 'site', 'jenisperangkat', 'brandperangkat']);

    $dataperangkat = $query->get();

    return view('aset.perangkat', compact(
        'regions',
        'sites',
        'types',
        'brands',
        'dataperangkat',
        'users',
        'racks'
    ));
}

    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'kode_region' => 'required',
            'kode_site' => 'required',
            'no_rack' => 'nullable',
            'kode_perangkat' => 'required',
            'kode_brand' => 'nullable',
            'type' => 'nullable',
            'uawal' => 'nullable|numeric|min:1',
            'uakhir' => 'nullable|numeric|min:1',
            'milik' => 'required',
        ]);
    
        // Custom Validasi tambahan
        if ($request->filled('no_rack')) {
            if (!$request->filled('uawal') || !$request->filled('uakhir')) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal dan UAkhir wajib diisi jika No Rack diisi.',
                    'uakhir' => 'UAwal dan UAkhir wajib diisi jika No Rack diisi.'
                ])->withInput();
            }
    
            if ($request->uawal > $request->uakhir) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal tidak boleh lebih besar dari UAkhir.',
                ])->withInput();
            }
    
            if ($request->uawal < 1 || $request->uakhir < 1) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal dan UAkhir tidak boleh kurang dari 1.',
                    'uakhir' => 'UAwal dan UAkhir tidak boleh kurang dari 1.'
                ])->withInput();
            }
    
            // Periksa apakah ada rentang 'u' yang tumpang tindih
            for ($u = $request->uawal; $u <= $request->uakhir; $u++) {
                $existingRack = Rack::where('kode_region', $request->kode_region)
                    ->where('kode_site', $request->kode_site)
                    ->where('no_rack', $request->no_rack)
                    ->where('u', $u)
                    ->where(function ($query) {
                        // Cek apakah sudah ada perangkat atau fasilitas pada rentang 'u' yang sama
                        $query->whereNotNull('id_perangkat')
                              ->orWhereNotNull('id_fasilitas');
                    })
                    ->exists();
    
                if ($existingRack) {
                    return redirect()->route('perangkat.index')
                        ->with('error', "Rentang U yang dimasukkan bertabrakan dengan data lain pada rack yang sama.");
                }
            }
        }
    
        // Logic untuk menambahkan perangkat baru ke ListPerangkat
        $jumlahPerangkat = ListPerangkat::where('kode_site', $request->kode_site)->max('perangkat_ke');
        $perangkatKe = $jumlahPerangkat + 1;
    
        $perangkatBaru = ListPerangkat::create([
            'kode_region' => $request->kode_region,
            'kode_site' => $request->kode_site,
            'no_rack' => $request->no_rack,
            'kode_perangkat' => $request->kode_perangkat,
            'perangkat_ke' => $perangkatKe,
            'kode_brand' => $request->kode_brand,
            'type' => $request->type,
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
            'milik' => $request->milik,
        ]);
    
        HistoriPerangkat::create([
            'id_perangkat' => $perangkatBaru->id_perangkat,
            'kode_region' => $request->kode_region,
            'kode_site' => $request->kode_site,
            'no_rack' => $request->no_rack,
            'kode_perangkat' => $request->kode_perangkat,
            'perangkat_ke' => $perangkatKe,
            'kode_brand' => $request->kode_brand,
            'type' => $request->type,
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
            'milik' => $request->milik,
            'histori' => 'Ditambahkan',
        ]);
    
        // Masukkan atau update data Rack untuk setiap nilai 'u'
        if ($request->no_rack) {
            for ($u = $request->uawal; $u <= $request->uakhir; $u++) {
                Rack::updateOrInsert(
                    [
                        'kode_region' => $request->kode_region,
                        'kode_site' => $request->kode_site,
                        'no_rack' => $request->no_rack,
                        'u' => $u,
                    ],
                    [
                        'id_perangkat' => $perangkatBaru->id_perangkat,
                        'milik' => $request->milik,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }        
    
        return redirect()->route('perangkat.index')
            ->with('success', 'Perangkat berhasil ditambahkan.')
            ->with('warning', 'Periksa kembali data yang dimasukkan sebelum melanjutkan.')
            ->with('error', 'Terjadi kesalahan saat menambahkan perangkat. Silakan coba lagi.');
    }
    


    public function update(Request $request, $id)
    {
        // Validasi dasar
        $request->validate([
            'kode_region' => 'required',
            'kode_site' => 'required',
            'no_rack' => 'nullable',
            'kode_perangkat' => 'required',
            'kode_brand' => 'nullable',
            'type' => 'nullable',
            'uawal' => 'nullable|numeric|min:1',
            'uakhir' => 'nullable|numeric|min:1',
            'milik' => 'required',
        ]);

        // Custom Validasi tambahan
        if ($request->filled('no_rack')) {
            if (!$request->filled('uawal') || !$request->filled('uakhir')) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal dan UAkhir wajib diisi jika No Rack diisi.',
                    'uakhir' => 'UAwal dan UAkhir wajib diisi jika No Rack diisi.'
                ])->withInput();
            }

            if ($request->uawal > $request->uakhir) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal tidak boleh lebih besar dari UAkhir.',
                ])->withInput();
            }

            if ($request->uawal < 1 || $request->uakhir < 1) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal dan UAkhir tidak boleh kurang dari 1.',
                    'uakhir' => 'UAwal dan UAkhir tidak boleh kurang dari 1.'
                ])->withInput();
            }
        }

        // Temukan perangkat berdasarkan ID dan lakukan update
        $perangkat = ListPerangkat::findOrFail($id);
        $perangkat->update([
            'kode_region' => $request->kode_region,
            'kode_site' => $request->kode_site,
            'no_rack' => $request->no_rack,
            'kode_perangkat' => $request->kode_perangkat,
            'kode_brand' => $request->kode_brand,
            'type' => $request->type,
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
            'milik' => $request->milik,
        ]);
        // Redirect kembali dengan pesan sukses
        return redirect()->route('perangkat.index')
            ->with('success', 'Perangkat berhasil diupdate.')
            ->with('warning', 'Periksa kembali data yang dimasukkan sebelum melanjutkan.')
            ->with('error', 'Terjadi kesalahan saat mengupdate perangkat. Silakan coba lagi.');
    }

    public function destroy($id)
    {
        $perangkat = ListPerangkat::findOrFail($id);
        $perangkat->delete();

        return redirect()->route('perangkat.index')->with('success', 'Perangkat berhasil dihapus.')
            ->with('error', 'Terjadi kesalahan saat menghapus perangkat. Silakan coba lagi.');
    }
}
