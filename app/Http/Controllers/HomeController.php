<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\ListPerangkat;
use App\Models\listFasilitas;
use App\Models\ListAlatukur;
// use App\Models\Listjaringan;
use App\Models\Region;
use App\Models\Site;
use Illuminate\Support\Facades\Auth;



use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     public function index()
     {
         // Mengambil nama user yang sedang login
         $userName = Auth::user()->name; // Ambil nama user yang sedang login
     
         // Mengambil data berdasarkan nama user (milik == name)
         $jumlahPerangkat = ListPerangkat::where('milik', $userName)->count();
         $jumlahFasilitas = ListFasilitas::where('milik', $userName)->count();
         $jumlahAlatUkur = ListAlatukur::where('milik', $userName)->count();
         $jumlahRegion = Region::count();
     
         // Menghitung jumlah jenis site dan totalnya
         $jumlahJenisSite = Site::select('jenis_site', DB::raw('count(*) as total'))
             ->groupBy('jenis_site')
             ->pluck('total', 'jenis_site');
     
         // Mengirim data ke view
         return view('home', compact(
             'jumlahPerangkat',
             'jumlahFasilitas',
             'jumlahAlatUkur',
             'jumlahRegion',
             'jumlahJenisSite'
         ));
     }     
}
