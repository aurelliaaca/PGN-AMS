<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\BrandAlatukur;
use App\Models\JenisAlatukur;
use App\Models\ListAlatukur;
use App\Models\Region;
use App\Models\HistoriAlatukur;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AlatukurController extends Controller
{
    public function indexAlatukur(Request $request)
    {
        $regions = Region::select('kode_region', 'nama_region')->orderBy('nama_region')->get();
        $types = JenisAlatukur::select('kode_alatukur', 'nama_alatukur')->orderBy('nama_alatukur')->get();
        $brands = BrandAlatukur::select('kode_brand', 'nama_brand')->orderBy('nama_brand')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        $user = auth()->user();
        $role = $user->role;

        $query = ListAlatukur::with(['region', 'jenisalatukur', 'brandalatukur']); // Hapus 'site'

        if ($role == 4 || $role == 5) {
            $query->where('milik', $user->id);
        } elseif ($role == 3) {
            $query->where('kode_region', $user->region);
        }

        if ($request->filled('kode_region')) {
            $query->whereIn('kode_region', $request->kode_region);
        }

        if ($request->filled('kode_alatukur')) {
            $query->whereIn('kode_alatukur', $request->kode_alatukur);
        }

        $dataalatukur = $query->get();

        return view('aset.alatukur', compact(
            'regions',
            'types',
            'brands',
            'dataalatukur',
            'users'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_region' => 'required',
            'kode_alatukur' => 'required',
            'kode_brand' => 'nullable',
            'type' => 'nullable',
            'serialnumber' => 'nullable',
            'alatukur_ke' => 'nullable',
            'kondisi' => 'nullable',
            'keterangan' => 'nullable',
            'milik' => 'required',
        ]);

        $jumlahAlatukur = ListAlatukur::where('kode_region', $request->kode_region)->max('alatukur_ke');
        $alatukurKe = $jumlahAlatukur + 1;

        $alatukurBaru = ListAlatukur::create([
            'kode_region' => $request->kode_region,
            'kode_alatukur' => $request->kode_alatukur,
            'alatukur_ke' => $alatukurKe,
            'kode_brand' => $request->kode_brand,
            'type' => $request->type,
            'serialnumber' => $request->serialnumber,
            'kondisi' => $request->kondisi,
            'keterangan' => $request->keterangan,
            'milik' => $request->milik,
        ]);

        HistoriAlatukur::create([
            'kode_region' => $request->kode_region,
            'kode_alatukur' => $request->kode_alatukur,
            'alatukur_ke' => $alatukurKe,
            'kode_brand' => $request->kode_brand,
            'type' => $request->type,
            'serialnumber' => $request->serialnumber,
            'kondisi' => $request->kondisi,
            'keterangan' => $request->keterangan,
            'milik' => $request->milik,
            'histori' => 'Ditambahkan',
        ]);

        return redirect()->route('alatukur.index')
            ->with('success', 'Alatukur berhasil ditambahkan.')
            ->with('warning', 'Periksa kembali data yang dimasukkan sebelum melanjutkan.')
            ->with('error', 'Terjadi kesalahan saat menambahkan alatukur. Silakan coba lagi.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_region' => 'required',
            'kode_alatukur' => 'required',
            'kode_brand' => 'nullable',
            'type' => 'nullable',
            'serialnumber' => 'nullable',
            'kondisi' => 'nullable',
            'keterangan' => 'nullable',
            'milik' => 'required',
        ]);

        $alatukur = ListAlatukur::findOrFail($id);
        $alatukur->update([
            'kode_region' => $request->kode_region,
            'kode_alatukur' => $request->kode_alatukur,
            'kode_brand' => $request->kode_brand,
            'type' => $request->type,
            'serialnumber' => $request->serialnumber,
            'kondisi' => $request->kondisi,
            'keterangan' => $request->keterangan,
            'milik' => $request->milik,
        ]);
        return redirect()->route('alatukur.index')
            ->with('success', 'Alatukur berhasil diupdate.')
            ->with('warning', 'Periksa kembali data yang dimasukkan sebelum melanjutkan.')
            ->with('error', 'Terjadi kesalahan saat mengupdate alatukur. Silakan coba lagi.');
    }

    public function destroy($id)
    {
        $alatukur = ListAlatukur::findOrFail($id);
        $alatukur->delete();

        return redirect()->route('alatukur.index')->with('success', 'Alatukur berhasil dihapus.')
            ->with('error', 'Terjadi kesalahan saat menghapus alatukur. Silakan coba lagi.');
    }
}
