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
        $users = User::select('id', 'name')->orderBy('name')->get();
        $racks = Rack::select('kode_region', 'kode_site', 'no_rack')->distinct()->get();

        $user = auth()->user();
        $role = $user->role;

        $query = ListPerangkat::with(['region', 'site', 'jenisperangkat', 'brandperangkat']);

        if ($role == 4 || $role == 5) {
            $query->where('milik', $user->id);
        } elseif ($role == 3) {
            $query->where('kode_region', $user->region);
        }

        if ($request->filled('kode_region')) {
            $query->whereIn('kode_region', $request->kode_region);
        }

        if ($request->filled('kode_site')) {
            $query->whereIn('kode_site', $request->kode_site);
        }

        if ($request->filled('kode_perangkat')) {
            $query->whereIn('kode_perangkat', $request->kode_perangkat);
        }

        $filteredSites = collect();
        if ($request->filled('kode_region')) {
            $filteredSites = Site::whereIn('kode_region', $request->kode_region)
                ->select('kode_region', 'nama_site', 'kode_site')
                ->orderBy('nama_site')
                ->get();
        } else {
            $filteredSites = $sites;
        }

        $dataperangkat = $query->get();

        return view('aset.perangkat', compact(
            'regions',
            'sites',
            'filteredSites',
            'types',
            'brands',
            'dataperangkat',
            'users',
            'racks'
        ));
    }

    public function store(Request $request)
    {
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

        if ($request->filled('no_rack')) {
            if (!$request->filled('uawal') || !$request->filled('uakhir')) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal wajib diisi jika No Rack diisi.',
                    'uakhir' => 'UAkhir wajib diisi jika No Rack diisi.'
                ])->withInput();
            }

            if ($request->uawal > $request->uakhir) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal tidak boleh lebih besar dari UAkhir.',
                ])->withInput();
            }

            if ($request->uawal < 1 || $request->uakhir < 1) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal idak boleh kurang dari 1.',
                    'uakhir' => 'UAkhir tidak boleh kurang dari 1.'
                ])->withInput();
            }

            for ($u = $request->uawal; $u <= $request->uakhir; $u++) {
                $existingRack = Rack::where('kode_region', $request->kode_region)
                    ->where('kode_site', $request->kode_site)
                    ->where('no_rack', $request->no_rack)
                    ->where('u', $u)
                    ->where(function ($query) {
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
            'tanggal_perubahan' => Carbon::now('Asia/Jakarta'),
        ]);

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

        if ($request->filled('no_rack')) {
            if (!$request->filled('uawal') || !$request->filled('uakhir')) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal wajib diisi jika No Rack diisi.',
                    'uakhir' => 'UAkhir wajib diisi jika No Rack diisi.'
                ])->withInput();
            }

            if ($request->uawal > $request->uakhir) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal tidak boleh lebih besar dari UAkhir.',
                ])->withInput();
            }

            if ($request->uawal < 1 || $request->uakhir < 1) {
                return redirect()->back()->withErrors([
                    'uawal' => 'UAwal idak boleh kurang dari 1.',
                    'uakhir' => 'UAkhir tidak boleh kurang dari 1.'
                ])->withInput();
            }
        }

        for ($u = $request->uawal; $u <= $request->uakhir; $u++) {
            $existingRack = Rack::where('kode_region', $request->kode_region)
                ->where('kode_site', $request->kode_site)
                ->where('no_rack', $request->no_rack)
                ->where('u', $u)
                ->where(function ($query) {
                    $query->whereNotNull('id_perangkat')
                        ->orWhereNotNull('id_fasilitas');
                })
                ->where(function ($query) use ($id) {
                    $query->where('id_perangkat', '!=', $id)
                        ->orWhereNull('id_perangkat');
                })
                ->exists();

            if ($existingRack) {
                return redirect()->route('perangkat.index')
                    ->with('error', "Rentang U yang dimasukkan bertabrakan dengan data lain pada rack yang sama.");
            }
        }

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

        Rack::where('id_perangkat', $perangkat->id_perangkat)
            ->update([
                'id_perangkat' => null,
                'milik' => null,
                'updated_at' => now(),
            ]);
            
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
                        'id_perangkat' => $perangkat->id_perangkat,
                        'milik' => $request->milik,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }

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
