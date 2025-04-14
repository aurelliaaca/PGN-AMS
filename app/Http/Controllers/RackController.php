<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rack;
use App\Models\Region;
use App\Models\Site;


class RackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexRack()
    {
        $regions = Region::select('kode_region', 'nama_region')->orderBy('nama_region')->get();
        $sites = Site::select('kode_region', 'nama_site', 'kode_site')->orderBy('nama_site')->get();
        // Ambil kombinasi unik dari kode_region, kode_site, dan no_rack
        $racksUnik = Rack::select('kode_region', 'kode_site', 'no_rack')
            ->distinct()
            ->get();

        // Ambil semua data untuk ditampilkan di dalam tabel toggle
        $semuaRack = Rack::select('kode_region', 'kode_site', 'no_rack', 'u', 'id_perangkat', 'id_fasilitas')
            ->get();

        return view('menu.rack', compact('racksUnik', 'semuaRack', 'regions', 'sites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeRack(Request $request)
    {
        $validated = $request->validate([
            'kode_region' => 'required|string',
            'kode_site' => 'required|string',
            'no_rack' => 'required|string',
            'total_u' => 'required|integer|min:1',
        ]);

        for ($i = 1; $i <= $validated['total_u']; $i++) {
            Rack::create([
                'kode_region' => $validated['kode_region'],
                'kode_site' => $validated['kode_site'],
                'no_rack' => $validated['no_rack'],
                'u' => $i,
                'id_fasilitas' => null,
                'id_perangkat' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Rack berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_region, $kode_site, $no_rack)
{
    // Cek apakah ada rack yang memiliki id_perangkat atau id_fasilitas yang tidak null
    $hasAssets = Rack::where('kode_region', $kode_region)
        ->where('kode_site', $kode_site)
        ->where('no_rack', $no_rack)
        ->where(function($query) {
            $query->whereNotNull('id_perangkat')
                  ->orWhereNotNull('id_fasilitas');
        })
        ->exists();

    if ($hasAssets) {
        // Jika ada, tampilkan pesan error
        return redirect()->back()->with('error', 'Tidak dapat menghapus rack karena masih ada perangkat atau fasilitas yang terkait.');
    }

    // Jika tidak ada, lanjutkan untuk menghapus
    $deleted = Rack::where('kode_region', $kode_region)
        ->where('kode_site', $kode_site)
        ->where('no_rack', $no_rack)
        ->delete();

    return redirect()->back()->with('success', "Rack berhasil dihapus ($deleted baris)");
}

public function destroyData(Request $request)
{
    $region = $request->kode_region;
    $site = $request->kode_site;
    $rack = $request->no_rack;
    $u = $request->u;

    // Ambil 1 baris data Rack berdasarkan region, site, no rack, dan U
    $dataRack = Rack::where('kode_region', $region)
        ->where('kode_site', $site)
        ->where('no_rack', $rack)
        ->where('u', $u)
        ->firstOrFail();

    // Hapus dari listperangkat jika ada
    if ($dataRack->id_perangkat) {
        \App\Models\ListPerangkat::where('id_perangkat', $dataRack->id_perangkat)->delete();

        // Kosongkan id_perangkat di semua baris yang pakai id ini
        Rack::where('id_perangkat', $dataRack->id_perangkat)
            ->update(['id_perangkat' => null]);
    }

    // Hapus dari listfasilitas jika ada
    if ($dataRack->id_fasilitas) {
        \App\Models\ListFasilitas::where('id_fasilitas', $dataRack->id_fasilitas)->delete();

        // Kosongkan id_fasilitas di semua baris yang pakai id ini
        Rack::where('id_fasilitas', $dataRack->id_fasilitas)
            ->update(['id_fasilitas' => null]);
    }

    return redirect()->back()->with('success', 'Perangkat/Fasilitas berhasil dihapus dari rack.');
}


}
