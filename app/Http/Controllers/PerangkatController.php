<?php

namespace App\Http\Controllers;

use App\Models\BrandPerangkat;
use App\Models\JenisPerangkat;
use App\Models\ListPerangkat;
use App\Models\Region;
use App\Models\Site;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexPerangkat()
    {
        $listperangkat = ListPerangkat::with(['region', 'site', 'brandperangkat', 'jenisperangkat'])->get();
        $regions = Region::all();
        $sites = Site::all();
        $types = JenisPerangkat::all();
        $brands = BrandPerangkat::all();
        return view('aset.perangkat', compact('listperangkat', 'regions','sites', 'types', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
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
    }

    // Hitung total perangkat pada site tersebut
    $jumlahPerangkat = ListPerangkat::where('kode_site', $request->kode_site)->count();
    $perangkatKe = $jumlahPerangkat + 1;

    // Simpan data perangkat ke dalam database
    ListPerangkat::create([
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

    return redirect()->route('perangkat.index')->with('success', 'Perangkat berhasil ditambahkan.');
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
    return redirect()->route('perangkat.index')->with('success', 'Perangkat berhasil diupdate.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $perangkat = ListPerangkat::findOrFail($id);
        $perangkat->delete();

        return redirect()->route('perangkat.index')->with('success', 'Perangkat berhasil dihapus.');
    }
}
