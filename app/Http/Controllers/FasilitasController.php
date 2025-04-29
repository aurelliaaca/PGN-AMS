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
    $regions = Region::select('kode_region', 'nama_region')->orderBy('nama_region')->get();
    $sites = Site::select('kode_region', 'nama_site', 'kode_site')->orderBy('nama_site')->get();
    $types = JenisFasilitas::select('kode_fasilitas', 'nama_fasilitas')->orderBy('nama_fasilitas')->get();
    $brands = BrandFasilitas::select('kode_brand', 'nama_brand')->orderBy('nama_brand')->get();

    $query = ListFasilitas::with(['region', 'site', 'jenisfasilitas', 'brandfasilitas']);

    $datafasilitas = $query->get();

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
    
            // Periksa apakah ada rentang 'u' yang tumpang tindih
            for ($u = $request->uawal; $u <= $request->uakhir; $u++) {
                $existingRack = Rack::where('kode_region', $request->kode_region)
                    ->where('kode_site', $request->kode_site)
                    ->where('no_rack', $request->no_rack)
                    ->where('u', $u)
                    ->where(function ($query) {
                        // Cek apakah sudah ada fasilitas atau fasilitas pada rentang 'u' yang sama
                        $query->whereNotNull('id_fasilitas')
                              ->orWhereNotNull('id_fasilitas');
                    })
                    ->exists();
    
                if ($existingRack) {
                    return redirect()->route('fasilitas.index')
                        ->with('error', "Rentang U yang dimasukkan bertabrakan dengan data lain pada rack yang sama.");
                }
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
            'uawal' => $request->uawal,
            'uakhir' => $request->uakhir,
            'histori' => 'Ditambahkan',
        ]);
    
        // Masukkan atau update data Rack untuk setiap nilai 'u'
        for ($u = $request->uawal; $u <= $request->uakhir; $u++) {
            // Menggunakan updateOrInsert untuk mencocokkan data yang sudah ada
            Rack::updateOrInsert(
                [
                    'kode_region' => $request->kode_region,
                    'kode_site' => $request->kode_site,
                    'no_rack' => $request->no_rack,
                    'u' => $u, // Mencocokkan berdasarkan 'u'
                ],
                [
                    'id_fasilitas' => $fasilitasBaru->id_fasilitas, // Menyimpan fasilitas baru
                    'updated_at' => now(), // Memperbarui kolom 'updated_at'
                    'created_at' => now(), // Menyimpan waktu penciptaan
                ]
            );
        }
    
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
