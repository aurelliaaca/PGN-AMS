<?php

namespace App\Http\Controllers;

use App\Models\TipeJaringan;
use Illuminate\Http\Request;

class TipeJaringanController extends Controller
{
    public function index()
    {
        $tipeJaringan = TipeJaringan::all();
        return view('menu.data.datajaringan', compact('tipeJaringan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_tipejaringan' => 'required|unique:tipejaringan',
            'nama_tipejaringan' => 'required'
        ]);

        TipeJaringan::create($request->all());
        return redirect()->route('tipejaringan.index')->with('success', 'Tipe Jaringan berhasil ditambahkan');
    }

    public function update(Request $request, $kode)
    {
        $request->validate([
            'kode_tipejaringan' => 'required|unique:tipejaringan,kode_tipejaringan,' . $kode . ',kode_tipejaringan',
            'nama_tipejaringan' => 'required'
        ]);

        $tipeJaringan = TipeJaringan::findOrFail($kode);
        $tipeJaringan->update($request->all());
        return redirect()->route('tipejaringan.index')->with('success', 'Tipe Jaringan berhasil diperbarui');
    }

    public function destroy($kode)
    {
        $tipeJaringan = TipeJaringan::findOrFail($kode);
        $tipeJaringan->delete();
        return redirect()->route('tipejaringan.index')->with('success', 'Tipe Jaringan berhasil dihapus');
    }
} 