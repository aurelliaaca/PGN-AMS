<?php

namespace App\Http\Controllers;
use App\Models\HistoriPerangkat;
use App\Models\HistoriFasilitas;
use App\Models\HistoriAlatukur;
use App\Models\HistoriJaringan;

use Illuminate\Http\Request;

class HistoriController extends Controller
{
    public function indexHistori()
    {
        $historiperangkat = HistoriPerangkat::with(['listperangkat'])->get();

        return view('menu.histori', compact('historiperangkat'));
    }

    // HistoriController.php
     // Display data for perangkat (device history)
     public function showHistoriPerangkat()
     {
        $historiperangkat = HistoriPerangkat::with('region', 'site', 'jenisperangkat', 'brandperangkat')->get();
        return view('menu.histori.historiperangkat', compact('historiperangkat'));
     }
 
     // Display data for fasilitas (facility history)
     public function showHistoriFasilitas()
     {
         $historifasilitas = HistoriFasilitas::all(); // Fetch all facility history
         return view('menu.histori.historifasilitas', compact('historifasilitas'));
     }
 
     // Display data for alat ukur (measurement tools history)
     public function showHistoriAlatukur()
     {
         $historialatukur = HistoriAlatukur::all(); // Fetch all measurement tools history
         return view('menu.histori.historialatukur', compact('historialatukur'));
     }
 
     // Display data for jaringan (network history)
     public function showHistoriJaringan()
     {
         $historijaringan = HistoriJaringan::all(); // Fetch all network history
         return view('menu.histori.historijaringan', compact('historijaringan'));
     }

}
