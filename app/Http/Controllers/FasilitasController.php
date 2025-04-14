<?php

namespace App\Http\Controllers;
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
        // Ambil data unik buat dropdown filter
        $regions = Region::select('kode_region', 'nama_region')->orderBy('nama_region')->get();
        $sites = Site::select('kode_region', 'nama_site', 'kode_site')->orderBy('nama_site')->get();
        $types = JenisFasilitas::select('kode_fasilitas', 'nama_fasilitas')->orderBy('nama_fasilitas')->get();
        $brands = BrandFasilitas::select('kode_brand', 'nama_brand')->orderBy('nama_brand')->get();

        // Ini bagian filter-nya
        $query = ListFasilitas::with(['region', 'site', 'jenisfasilitas', 'brandfasilitas']); // eager loading relasi

        // Cek filter berdasarkan multiple selection
        if ($request->region) {
            $query->whereIn('kode_region', $request->region); // Menggunakan whereIn untuk filter berdasarkan array
        }

        if ($request->site) {
            $query->whereIn('kode_site', $request->site); // Menggunakan whereIn untuk filter berdasarkan array
        }

        if ($request->kode_fasilitas) {
            $query->whereIn('kode_fasilitas', $request->kode_fasilitas); // Menggunakan whereIn untuk filter berdasarkan array
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
                    ->orWhere('kode_fasilitas', 'like', "%$search%")
                    ->orWhere('fasilitas_ke', 'like', "%$search%")
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
                    ->orWhereHas('jenisfasilitas', function ($q2) use ($search) {
                        $q2->where('nama_fasilitas', 'like', "%$search%");
                    })
                    ->orWhereHas('brandfasilitas', function ($q2) use ($search) {
                        $q2->where('nama_brand', 'like', "%$search%");
                    });
            });
        }

        // Ambil hasil query-nya
        $datafasilitas = $query->get();

        $datafasilitas = $datafasilitas->sortBy('kode_site');
        $datafasilitas = $datafasilitas->sortBy('no_rack');
        $datafasilitas = $datafasilitas->sortBy('kode_region');

        // Kirim ke view
        return view('aset.fasilitas', compact(
            'regions',
            'sites',
            'types',
            'brands',
            'datafasilitas'
        ));

    }

    public function store(Request $request)
{
    // Validasi dasar
    $request->validate([
        'kode_region' => 'required',
        'kode_site' => 'required',
        'no_rack' => 'nullable',
        'kode_fasilitas' => 'required',
        'kode_brand' => 'nullable',
        'type' => 'nullable',
        'serialnumber' => 'nullable',
        'jml_fasilitas' => 'nullable|numeric',
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

        // Periksa apakah ada fasilitas lain yang menggunakan kode_region, kode_site, no_rack yang sama
        $existingRack = Rack::where('kode_region', $request->kode_region)
            ->where('kode_site', $request->kode_site)
            ->where('no_rack', $request->no_rack)
            ->where(function ($query) use ($request) {
                // Cek apakah rentang uawal - uakhir tumpang tindih dengan kolom 'u' yang ada
                $query->whereBetween('u', [$request->uawal, $request->uakhir])
                      ->orWhere(function ($query) use ($request) {
                          // Jika rentang fasilitas baru melibatkan nilai 'u' yang ada
                          $query->where('u', '>=', $request->uawal)
                                ->where('u', '<=', $request->uakhir);
                      });
            })
            ->exists(); // Memastikan apakah ada data yang tumpang tindih

        if ($existingRack) {
            return redirect()->route('fasilitas.index')
                ->with('error', 'Rentang U yang dimasukkan bertabrakan dengan data lain pada rack yang sama.');
        }
    }

    // Logic untuk menambahkan fasilitas baru ke ListFasilitas
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
        'uawal' => $request->uawal,
        'uakhir' => $request->uakhir,
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
        'uawal' => $request->uawal,
        'uakhir' => $request->uakhir,
        'histori' => 'Ditambahkan',
    ]);

    return redirect()->route('fasilitas.index')
        ->with('success', 'Fasilitas berhasil ditambahkan.')
        ->with('warning', 'Periksa kembali data yang dimasukkan sebelum melanjutkan.')
        ->with('error', 'Terjadi kesalahan saat menambahkan fasilitas. Silakan coba lagi.');
}


    public function update(Request $request, $id)
    {
        // Validasi dasar
        $request->validate([
            'kode_region' => 'required',
            'kode_site' => 'required',
            'no_rack' => 'nullable',
            'kode_fasilitas' => 'required',
            'kode_brand' => 'nullable',
            'type' => 'nullable',
            'serialnumber' => 'nullable',
            'jml_fasilitas' => 'nullable|numeric',
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

        // Temukan fasilitas berdasarkan ID dan lakukan update
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
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
        ]);
        // Redirect kembali dengan pesan sukses
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
