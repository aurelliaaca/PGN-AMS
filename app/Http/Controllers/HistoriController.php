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

        return view('menu.histori');
    }

     // Display data for perangkat (device history)
     public function showHistoriPerangkat()
{
    // Mendapatkan data pengguna yang sedang login
    $user = auth()->user();  
    $role = $user->role;  // Mengambil role pengguna

    // Query untuk HistoriPerangkat
    $query = HistoriPerangkat::with('region', 'site', 'jenisperangkat', 'brandperangkat')
        ->orderBy('tanggal_perubahan', 'desc');

    // Jika role 3 atau 4, filter berdasarkan milik (id pengguna)
    if ($role == 3 || $role == 4) {
        $query->where('milik', $user->id);
    }

    // Menjalankan query
    $historiperangkat = $query->get();

    // Mengirim data ke view
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
