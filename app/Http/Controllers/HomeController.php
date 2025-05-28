<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\ListPerangkat;
use App\Models\listFasilitas;
use App\Models\ListAlatukur;
use App\Models\ListJaringan;
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
        $user = Auth::user();
        $role = $user->role;
        $userId = $user->id;
        $userRegion = $user->region;

        if ($role == 1) {
            $jumlahPerangkat = ListPerangkat::count();
            $jumlahFasilitas = ListFasilitas::count();
            $jumlahAlatUkur = ListAlatukur::count();
            $jumlahJaringan = ListJaringan::count();
        } elseif ($role == 2) {
            $jumlahPerangkat = ListPerangkat::count();
            $jumlahFasilitas = ListFasilitas::count();
            $jumlahAlatUkur = ListAlatukur::count();
            $jumlahJaringan = ListJaringan::count();
        } elseif ($role == 3) {
            $jumlahPerangkat = ListPerangkat::where('kode_region', $userRegion)->count();
            $jumlahFasilitas = ListFasilitas::where('kode_region', $userRegion)->count();
            $jumlahAlatUkur = ListAlatukur::where('kode_region', $userRegion)->count();
            $jumlahJaringan = ListJaringan::where('kode_region', $userRegion)->count();
        } elseif (in_array($role, [3, 4])) {
            $jumlahPerangkat = ListPerangkat::where('milik', $userId)->count();
            $jumlahFasilitas = ListFasilitas::where('milik', $userId)->count();
            $jumlahAlatUkur = ListAlatukur::where('milik', $userId)->count();
            $jumlahJaringan = ListJaringan::where('milik', $userId)->count();
        } else {
            $jumlahPerangkat = 0;
            $jumlahFasilitas = 0;
            $jumlahAlatUkur = 0;
            $jumlahJaringan = 0;
        }

        $jumlahRegion = Region::count();

        $jumlahJenisSite = Site::select('jenis_site', DB::raw('count(*) as total'))
            ->groupBy('jenis_site')
            ->pluck('total', 'jenis_site');

        return view('home', compact(
            'jumlahPerangkat',
            'jumlahFasilitas',
            'jumlahAlatUkur',
            'jumlahJaringan',
            'jumlahRegion',
            'jumlahJenisSite'
        ));
    }
}