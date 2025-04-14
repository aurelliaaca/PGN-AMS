<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rack;


class RackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexRack()
{
    // Ambil kombinasi unik dari kode_region, kode_site, dan no_rack
    $racksUnik = Rack::select('kode_region', 'kode_site', 'no_rack')
                    ->distinct()
                    ->get();

    // Ambil semua data untuk ditampilkan di dalam tabel toggle
    $semuaRack = Rack::select('kode_region', 'kode_site', 'no_rack', 'u', 'id_perangkat')
                    ->get();

    return view('menu.rack', compact('racksUnik', 'semuaRack'));
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
