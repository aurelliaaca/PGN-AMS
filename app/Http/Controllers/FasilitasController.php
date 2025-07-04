<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\BrandFasilitas;
use App\Models\JenisFasilitas;
use App\Models\ListFasilitas;
use App\Models\Region;
use App\Models\Site;
use App\Models\HistoriFasilitas;
use App\Models\Rack;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FasilitasController extends Controller
{
    public function indexFasilitas(Request $request)
    {
        $regions = Region::select('kode_region', 'nama_region')->orderBy('nama_region')->get();
        $sites = Site::select('kode_region', 'nama_site', 'kode_site')->orderBy('nama_site')->get();
        $types = JenisFasilitas::select('kode_fasilitas', 'nama_fasilitas')->orderBy('nama_fasilitas')->get();
        $brands = BrandFasilitas::select('kode_brand', 'nama_brand')->orderBy('nama_brand')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();
        $racks = Rack::select('kode_region', 'kode_site', 'no_rack')
            ->distinct()
            ->get();

        $user = auth()->user();
        $role = $user->role;

        $query = ListFasilitas::with(['region', 'site', 'jenisfasilitas', 'brandfasilitas']);

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

        if ($request->filled('kode_fasilitas')) {
            $query->whereIn('kode_fasilitas', $request->kode_fasilitas);
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


        $datafasilitas = $query->get();

        return view('aset.fasilitas', compact(
            'regions',
            'sites',
            'filteredSites',
            'types',
            'brands',
            'datafasilitas',
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
            'kode_fasilitas' => 'required',
            'kode_brand' => 'nullable',
            'type' => 'nullable',
            'serialnumber' => 'nullable',
            'jml_fasilitas' => 'nullable',
            'status' => 'nullable',
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
                    return redirect()->route('fasilitas.index')
                        ->with('error', "Rentang U yang dimasukkan bertabrakan dengan data lain pada rack yang sama.");
                }
            }

        }

        $jumlahFasilitas = ListFasilitas::where('kode_site', $request->kode_site)->max('fasilitas_ke');
        $fasilitasKe = $jumlahFasilitas + 1;

        $fasilitasBaru = ListFasilitas::create([
            'kode_region' => $request->kode_region,
            'kode_site' => $request->kode_site,
            'no_rack' => $request->no_rack,
            'kode_fasilitas' => $request->kode_fasilitas,
            'fasilitas_ke' => $fasilitasKe,
            'kode_brand' => $request->kode_brand,
            'type' => $request->type,
            'serialnumber' => $request->serialnumber,
            'jml_fasilitas' => $request->jml_fasilitas,
            'status' => $request->status,
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
            'milik' => $request->milik,
        ]);

        HistoriFasilitas::create([
            'id_fasilitas' => $fasilitasBaru->id_fasilitas,
            'kode_region' => $request->kode_region,
            'kode_site' => $request->kode_site,
            'no_rack' => $request->no_rack,
            'kode_fasilitas' => $request->kode_fasilitas,
            'fasilitas_ke' => $fasilitasKe,
            'kode_brand' => $request->kode_brand,
            'type' => $request->type,
            'serialnumber' => $request->serialnumber,
            'jml_fasilitas' => $request->jml_fasilitas,
            'status' => $request->status,
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
                        'id_fasilitas' => $fasilitasBaru->id_fasilitas,
                        'milik' => $request->milik,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.')
            ->with('warning', 'Periksa kembali data yang dimasukkan sebelum melanjutkan.')
            ->with('error', 'Terjadi kesalahan saat menambahkan fasilitas. Silakan coba lagi.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_region' => 'required',
            'kode_site' => 'required',
            'no_rack' => 'nullable',
            'kode_fasilitas' => 'required',
            'kode_brand' => 'nullable',
            'type' => 'nullable',
            'serialnumber' => 'nullable',
            'jml_fasilitas' => 'nullable',
            'status' => 'nullable',
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
                    $query->where('id_fasilitas', '!=', $id)
                        ->orWhereNull('id_fasilitas');
                })
                ->exists();

            if ($existingRack) {
                return redirect()->route('fasilitas.index')
                    ->with('error', "Rentang U yang dimasukkan bertabrakan dengan data lain pada rack yang sama.");
            }
        }

        $fasilitas = ListFasilitas::findOrFail($id);
        $fasilitas->update([
            'kode_region' => $request->kode_region,
            'kode_site' => $request->kode_site,
            'no_rack' => $request->no_rack,
            'kode_fasilitas' => $request->kode_fasilitas,
            'kode_brand' => $request->kode_brand,
            'type' => $request->type,
            'serialnumber' => $request->serialnumber,
            'jml_fasilitas' => $request->jml_fasilitas,
            'status' => $request->status,
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
            'milik' => $request->milik,
        ]);

        Rack::where('id_fasilitas', $fasilitas->id_fasilitas)
            ->update([
                'id_fasilitas' => null,
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
                        'id_fasilitas' => $fasilitas->id_fasilitas,
                        'milik' => $request->milik,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil diupdate.')
            ->with('warning', 'Periksa kembali data yang dimasukkan sebelum melanjutkan.')
            ->with('error', 'Terjadi kesalahan saat mengupdate fasilitas. Silakan coba lagi.');
    }

    public function destroy($id)
    {
        $fasilitas = ListFasilitas::findOrFail($id);
        $fasilitas->delete();

        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil dihapus.')
            ->with('error', 'Terjadi kesalahan saat menghapus fasilitas. Silakan coba lagi.');
    }
}