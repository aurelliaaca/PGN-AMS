<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrandPerangkat;
use App\Models\JenisPerangkat;
use App\Models\BrandFasilitas;
use App\Models\JenisFasilitas;
use App\Models\BrandAlatukur;
use App\Models\JenisAlatukur;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('menu.data.data');
    }

    // ------------------------------- PERANGKAT -------------------------------
    
    public function indexPerangkat()
    {
        $brandperangkat = BrandPerangkat::all();
        $jenisperangkat = JenisPerangkat::all();
        return view('menu.data.dataperangkat', compact('brandperangkat', 'jenisperangkat'));
    }
    public function createBrandPerangkat()
    {
        return view('brandperangkat.create');
    }

    public function storeBrandPerangkat(Request $request)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brandperangkat,kode_brand',
        ]);

        BrandPerangkat::create($request->all());

        return redirect()->route('dataperangkat.index')->with('success', 'Brand berhasil ditambahkan.');
    }

    public function editBrandPerangkat($kode_brand)
    {
        $brandPerangkat = BrandPerangkat::findOrFail($kode_brand);
        return view('brandperangkat.edit', compact('brandPerangkat'));
    }


    public function updateBrandPerangkat(Request $request, $kode_brand)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brandperangkat,kode_brand,' . $kode_brand . ',kode_brand',
        ]);

        $brandPerangkat = BrandPerangkat::findOrFail($kode_brand);
        $brandPerangkat->update($request->all());

        return redirect()->route('dataperangkat.index')->with('success', 'Brand berhasil diupdate.');
    }

    public function destroyBrandPerangkat($kode_brand)
    {
        $brandPerangkat = BrandPerangkat::findOrFail($kode_brand);
        $brandPerangkat->delete();

        return redirect()->route('dataperangkat.index')->with('success', 'Brand berhasil dihapus.');
    }

    public function createJenisPerangkat()
    {
        return view('jenisperangkat.create');
    }

    public function storeJenisPerangkat(Request $request)
    {
        $request->validate([
            'nama_perangkat' => 'required',
            'kode_perangkat' => 'required|unique:jenisperangkat,kode_perangkat',
        ]);

        JenisPerangkat::create($request->all());

        return redirect()->route('dataperangkat.index')->with('success', 'Jenis berhasil ditambahkan.');
    }

    public function editJenisPerangkat($kode_perangkat)
    {
        $jenisPerangkat = JenisPerangkat::findOrFail($kode_perangkat);
        return view('jenisperangkat.edit', compact('jenisPerangkat'));
    }


    public function updateJenisPerangkat(Request $request, $kode_perangkat)
    {
        $request->validate([
            'nama_perangkat' => 'required',
            'kode_perangkat' => 'required|unique:jenisperangkat,kode_perangkat,' . $kode_perangkat . ',kode_perangkat',
        ]);

        $jenisPerangkat = JenisPerangkat::findOrFail($kode_perangkat);
        $jenisPerangkat->update($request->all());

        return redirect()->route('dataperangkat.index')->with('success', 'Jenis berhasil diupdate.');
    }

    public function destroyJenisPerangkat($kode_perangkat)
    {
        $jenisPerangkat = JenisPerangkat::findOrFail($kode_perangkat);
        $jenisPerangkat->delete();

        return redirect()->route('dataperangkat.index')->with('success', 'Jenis berhasil dihapus.');
    }

    // ------------------------------- FASILITAS -------------------------------

    public function indexFasilitas()
    {
        $brandfasilitas = BrandFasilitas::all();
        $jenisfasilitas = JenisFasilitas::all();
        return view('menu.data.datafasilitas', compact('brandfasilitas', 'jenisfasilitas'));
    }
    public function createBrandFasilitas()
    {
        return view('brandfasilitas.create');
    }

    public function storeBrandFasilitas(Request $request)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brandfasilitas,kode_brand',
        ]);

        BrandFasilitas::create($request->all());

        return redirect()->route('datafasilitas.index')->with('success', 'Brand berhasil ditambahkan.');
    }

    public function editBrandFasilitas($kode_brand)
    {
        $brandFasilitas = BrandFasilitas::findOrFail($kode_brand);
        return view('brandfasilitas.edit', compact('brandFasilitas'));
    }


    public function updateBrandFasilitas(Request $request, $kode_brand)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brandfasilitas,kode_brand,' . $kode_brand . ',kode_brand',
        ]);

        $brandFasilitas = BrandFasilitas::findOrFail($kode_brand);
        $brandFasilitas->update($request->all());

        return redirect()->route('datafasilitas.index')->with('success', 'Brand berhasil diupdate.');
    }

    public function destroyBrandFasilitas($kode_brand)
    {
        $brandFasilitas = BrandFasilitas::findOrFail($kode_brand);
        $brandFasilitas->delete();

        return redirect()->route('datafasilitas.index')->with('success', 'Brand berhasil dihapus.');
    }

    public function createJenisFasilitas()
    {
        return view('jenisfasilitas.create');
    }

    public function storeJenisFasilitas(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required',
            'kode_fasilitas' => 'required|unique:jenisfasilitas,kode_fasilitas',
        ]);

        JenisFasilitas::create($request->all());

        return redirect()->route('datafasilitas.index')->with('success', 'Jenis berhasil ditambahkan.');
    }

    public function editJenisFasilitas($kode_fasilitas)
    {
        $jenisFasilitas = JenisFasilitas::findOrFail($kode_fasilitas);
        return view('jenisfasilitas.edit', compact('jenisFasilitas'));
    }


    public function updateJenisFasilitas(Request $request, $kode_fasilitas)
    {
        $request->validate([
            'nama_fasilitas' => 'required',
            'kode_fasilitas' => 'required|unique:jenisfasilitas,kode_fasilitas,' . $kode_fasilitas . ',kode_fasilitas',
        ]);

        $jenisFasilitas = JenisFasilitas::findOrFail($kode_fasilitas);
        $jenisFasilitas->update($request->all());

        return redirect()->route('datafasilitas.index')->with('success', 'Jenis berhasil diupdate.');
    }

    public function destroyJenisFasilitas($kode_fasilitas)
    {
        $jenisFasilitas = JenisFasilitas::findOrFail($kode_fasilitas);
        $jenisFasilitas->delete();

        return redirect()->route('datafasilitas.index')->with('success', 'Jenis berhasil dihapus.');
    }
    // ------------------------------- ALAT UKUR -------------------------------


public function indexAlatukur()
{
    $brandalatukur = BrandAlatukur::all();
    $jenisalatukur = JenisAlatukur::all();
    return view('menu.data.dataalatukur', compact('brandalatukur', 'jenisalatukur'));
}
public function createBrandAlatukur()
{
    return view('brandalatukur.create');
}

public function storeBrandAlatukur(Request $request)
{
    $request->validate([
        'nama_brand' => 'required',
        'kode_brand' => 'required|unique:brandalatukur,kode_brand',
    ]);

    BrandAlatukur::create($request->all());

    return redirect()->route('dataalatukur.index')->with('success', 'Brand berhasil ditambahkan.');
}

public function editBrandAlatukur($kode_brand)
{
    $brandAlatukur = BrandAlatukur::findOrFail($kode_brand);
    return view('brandalatukur.edit', compact('brandAlatukur'));
}


public function updateBrandAlatukur(Request $request, $kode_brand)
{
    $request->validate([
        'nama_brand' => 'required',
        'kode_brand' => 'required|unique:brandalatukur,kode_brand,' . $kode_brand . ',kode_brand',
    ]);

    $brandAlatukur = BrandAlatukur::findOrFail($kode_brand);
    $brandAlatukur->update($request->all());

    return redirect()->route('dataalatukur.index')->with('success', 'Brand berhasil diupdate.');
}

public function destroyBrandAlatukur($kode_brand)
{
    $brandAlatukur = BrandAlatukur::findOrFail($kode_brand);
    $brandAlatukur->delete();

    return redirect()->route('dataalatukur.index')->with('success', 'Brand berhasil dihapus.');
}

public function createJenisAlatukur()
{
    return view('jenisalatukur.create');
}

public function storeJenisAlatukur(Request $request)
{
    $request->validate([
        'nama_alatukur' => 'required',
        'kode_alatukur' => 'required|unique:jenisalatukur,kode_alatukur',
    ]);

    JenisAlatukur::create($request->all());

    return redirect()->route('dataalatukur.index')->with('success', 'Jenis berhasil ditambahkan.');
}

public function editJenisAlatukur($kode_alatukur)
{
    $jenisAlatukur = JenisAlatukur::findOrFail($kode_alatukur);
    return view('jenisalatukur.edit', compact('jenisAlatukur'));
}


public function updateJenisAlatukur(Request $request, $kode_alatukur)
{
    $request->validate([
        'nama_alatukur' => 'required',
        'kode_alatukur' => 'required|unique:jenisalatukur,kode_alatukur,' . $kode_alatukur . ',kode_alatukur',
    ]);

    $jenisAlatukur = JenisAlatukur::findOrFail($kode_alatukur);
    $jenisAlatukur->update($request->all());

    return redirect()->route('dataalatukur.index')->with('success', 'Jenis berhasil diupdate.');
}

public function destroyJenisAlatukur($kode_alatukur)
{
    $jenisAlatukur = JenisAlatukur::findOrFail($kode_alatukur);
    $jenisAlatukur->delete();

    return redirect()->route('dataalatukur.index')->with('success', 'Jenis berhasil dihapus.');
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
    public function store(Request $request)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
