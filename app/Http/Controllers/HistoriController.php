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

        return view('menu.histori.histori');
    }

    public function showHistoriPerangkat()
    {
        $user = auth()->user();
        $role = $user->role;  

        $query = HistoriPerangkat::with('region', 'site', 'jenisperangkat', 'brandperangkat')
            ->orderBy('tanggal_perubahan', 'desc');

        if ($role == 4 || $role == 5) {
            $query->where('milik', $user->id);
        } elseif ($role == 3) {
            $query->where('kode_region', $user->region);
        }

        $historiperangkat = $query->get();

        return view('menu.histori.historiperangkat', compact('historiperangkat'));
    }
    public function showHistoriFasilitas()
    {
        $user = auth()->user();
        $role = $user->role;  

        $query = HistoriFasilitas::with('region', 'site', 'jenisfasilitas', 'brandfasilitas')
            ->orderBy('tanggal_perubahan', 'desc');

        if ($role == 4 || $role == 5) {
            $query->where('milik', $user->id);
        } elseif ($role == 3) {
            $query->where('kode_region', $user->region);
        }

        $historifasilitas = $query->get();

        return view('menu.histori.historifasilitas', compact('historifasilitas'));
    }

    public function showHistoriAlatukur()
    {
        $user = auth()->user();
        $role = $user->role;  

        $query = HistoriAlatukur::with('region', 'jenisalatukur', 'brandalatukur')
            ->orderBy('tanggal_perubahan', 'desc');

        if ($role == 4 || $role == 5) {
            $query->where('milik', $user->id);
        } elseif ($role == 3) {
            $query->where('kode_region', $user->region);
        }

        $historialatukur = $query->get();

        return view('menu.histori.historialatukur', compact('historialatukur'));
    }

    // Display data for jaringan (network history)
    public function showHistoriJaringan()
    {
        $user = auth()->user();
        $role = $user->role;  

        $query = HistoriJaringan::with('region', 'tipejaringan')
            ->orderBy('tanggal_perubahan', 'desc');

        if ($role == 4 || $role == 5) {
            $query->where('milik', $user->id);
        } elseif ($role == 3) {
            $query->where('kode_region', $user->region);
        }

        $historijaringan = $query->get();

        return view('menu.histori.historijaringan', compact('historijaringan'));
    }

}
