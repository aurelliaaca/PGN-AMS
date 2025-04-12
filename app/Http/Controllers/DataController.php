<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrandPerangkat;
use App\Models\JenisPerangkat;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('menu.data');
    }

    public function indexPerangkat()
    {
        $brandperangkat = BrandPerangkat::all();
        $jenisperangkat = JenisPerangkat::all();
        return view('menu.perangkat.dataperangkat', compact('brandperangkat', 'jenisperangkat'));
    }
    public function createBrandPerangkat()
    {
        return view('brandperangkat.create');
    }
    
    public function storeBrandPerangkat(Request $request)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brand_perangkat,kode_brand',
        ]);
    
        BrandPerangkat::create($request->all());
    
        return redirect()->route('brandperangkat.index')->with('success', 'Brand berhasil ditambahkan.');
    }
    
    public function editBrandPerangkat(BrandPerangkat $brandPerangkat)
    {
        return view('brandperangkat.edit', compact('brandPerangkat'));
    }
    
    public function updateBrandPerangkat(Request $request, BrandPerangkat $brandPerangkat)
    {
        $request->validate([
            'nama_brand' => 'required',
            'kode_brand' => 'required|unique:brand_perangkat,kode_brand,' . $brandPerangkat->id,
        ]);
    
        $brandPerangkat->update($request->all());
    
        return redirect()->route('brandperangkat.index')->with('success', 'Brand berhasil diupdate.');
    }
    
    public function destroyBrandPerangkat(BrandPerangkat $brandPerangkat)
    {
        $brandPerangkat->delete();
    
        return redirect()->route('dataperangkat.index')->with('success', 'Brand berhasil dihapus.');
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
