<?php

namespace App\Http\Controllers;
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
        // Ambil data unik buat dropdown filter
        $regions = Region::select('kode_region', 'nama_region')->orderBy('nama_region')->get();
        $sites = Site::select('kode_region', 'nama_site', 'kode_site')->orderBy('nama_site')->get();
        $types = JenisPerangkat::select('kode_perangkat', 'nama_perangkat')->orderBy('nama_perangkat')->get();
        $brands = BrandPerangkat::select('kode_brand', 'nama_brand')->orderBy('nama_brand')->get();

        // Ini bagian filter-nya
        $query = ListPerangkat::with(['region', 'site', 'jenisperangkat', 'brandperangkat']); // eager loading relasi

        // Cek filter berdasarkan multiple selection
        if ($request->region) {
            $query->whereIn('kode_region', $request->region); // Menggunakan whereIn untuk filter berdasarkan array
        }

        if ($request->site) {
            $query->whereIn('kode_site', $request->site); // Menggunakan whereIn untuk filter berdasarkan array
        }

        if ($request->kode_perangkat) {
            $query->whereIn('kode_perangkat', $request->kode_perangkat); // Menggunakan whereIn untuk filter berdasarkan array
        }

        if ($request->brand) {
            $query->whereIn('kode_brand', $request->brand); // Menggunakan whereIn untuk filter berdasarkan array
        }

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_region', 'like', "%$search%")
                    ->orWhere('kode_site', 'like', "%$search%")
                    ->orWhere('no_rack', 'like', "%$search%")
                    ->orWhere('kode_perangkat', 'like', "%$search%")
                    ->orWhere('perangkat_ke', 'like', "%$search%")
                    ->orWhere('kode_brand', 'like', "%$search%")
                    ->orWhere('type', 'like', "%$search%")
                    ->orWhere('uawal', 'like', "%$search%")
                    ->orWhere('uakhir', 'like', "%$search%")
                    ->orWhereHas('region', function ($q2) use ($search) {
                        $q2->where('nama_region', 'like', "%$search%");
                    })
                    ->orWhereHas('site', function ($q3) use ($search) {
                        $q3->where('nama_site', 'like', "%$search%");
                    })
                    ->orWhereHas('jenisperangkat', function ($q2) use ($search) {
                        $q2->where('nama_perangkat', 'like', "%$search%");
                    })
                    ->orWhereHas('brandperangkat', function ($q2) use ($search) {
                        $q2->where('nama_brand', 'like', "%$search%");
                    });
            });
        }

        // Ambil hasil query-nya
        $dataperangkat = $query->get();

        $dataperangkat = $dataperangkat->sortBy('kode_site');
        $dataperangkat = $dataperangkat->sortBy('no_rack');
        $dataperangkat = $dataperangkat->sortBy('kode_region');

        // Kirim ke view
        return view('aset.perangkat', compact(
            'regions',
            'sites',
            'types',
            'brands',
            'dataperangkat'
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

        // Periksa apakah ada perangkat lain yang menggunakan kode_region, kode_site, no_rack yang sama
        $existingRack = Rack::where('kode_region', $request->kode_region)
            ->where('kode_site', $request->kode_site)
            ->where('no_rack', $request->no_rack)
            ->where(function ($query) use ($request) {
                // Cek apakah rentang uawal - uakhir tumpang tindih dengan kolom 'u' yang ada
                $query->whereBetween('u', [$request->uawal, $request->uakhir])
                      ->orWhere(function ($query) use ($request) {
                          // Jika rentang perangkat baru melibatkan nilai 'u' yang ada
                          $query->where('u', '>=', $request->uawal)
                                ->where('u', '<=', $request->uakhir);
                      });
            })
            ->exists(); // Memastikan apakah ada data yang tumpang tindih

        if ($existingRack) {
            return redirect()->route('perangkat.index')
                ->with('error', 'Rentang U yang dimasukkan bertabrakan dengan data lain pada rack yang sama.');
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
        'histori' => 'Ditambahkan',
    ]);

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
