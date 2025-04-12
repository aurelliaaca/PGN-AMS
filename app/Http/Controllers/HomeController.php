<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\ListPerangkat;
use App\Models\listFasilitas;
use App\Models\ListAlatukur;
// use App\Models\Listjaringan;
use App\Models\Region;
use App\Models\Site;



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
        $jumlahPerangkat = ListPerangkat::count();
        $jumlahFasilitas = ListFasilitas::count();
        $jumlahAlatUkur = ListAlatukur::count();
        // $jumlahJaringan = ListJaringan::count();
        $jumlahRegion = Region::count();
        $jumlahJenisSite = Site::select('jenis_site', DB::raw('count(*) as total'))
            ->groupBy('jenis_site')
            ->pluck('total', 'jenis_site');
        return view('home', compact('jumlahPerangkat', 'jumlahFasilitas', 'jumlahAlatUkur', 'jumlahRegion', 'jumlahJenisSite'));
    }
}
