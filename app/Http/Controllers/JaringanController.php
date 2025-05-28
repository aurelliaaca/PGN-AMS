<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\ListJaringan;
use App\Models\TipeJaringan;
use App\Models\HistoriJaringan;
use App\Models\Region;
use Illuminate\Http\Request;

class JaringanController extends Controller
{

    public function indexJaringan(Request $request)
    {
        $regions = Region::select('kode_region', 'nama_region')->orderBy('nama_region')->get();
        $types = TipeJaringan::select('kode_tipejaringan', 'nama_tipejaringan')->orderBy('nama_tipejaringan')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        $user = auth()->user();
        $role = $user->role;

        $query = ListJaringan::with(['region', 'tipejaringan']);

        if ($role == 4 || $role == 5) {
            $query->where('milik', $user->id);
        } elseif ($role == 3) {
            $query->where('kode_region', $user->region);
        }

        if ($request->filled('kode_region')) {
            $query->whereIn('kode_region', $request->kode_region);
        }

        if ($request->filled('kode_tipejaringan')) {
            $query->whereIn('kode_tipejaringan', $request->kode_tipejaringan);
        }

        $datajaringan = $query->get();

        return view('aset.jaringan', compact(
            'regions',
            'types',
            'datajaringan',
            'users'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_region' => 'required',
            'kode_tipejaringan' => 'required',
            'segmen'=> 'required',
            'jartatup_jartaplok' => 'required',
            'mainlink_backuplink'=> 'nullable',
            'panjang'=> 'required',
            'panjang_drawing' => 'nullable',
            'jumlah_core' => 'required',
            'jenis_kabel'=> 'required',
            'tipe_kabel'=> 'required',
            'status'=> 'nulable',
            'keterangan'=> 'nullable',
            'kode_site_insan' => 'nullable',
            'dci_eqx' => 'nullable',
            'travelling_time' => 'nullable',
            'verification_time' => 'nullable',
            'restoration_time' => 'nullable',
            'total_corrective_time'=> 'nullable',
            'milik' => 'required',
        ]);

        $jaringanBaru = ListJaringan::create([
            'kode_region'            => $request->kode_region,
            'kode_tipejaringan'          => $request->kode_tipejaringan,
            'segmen'                 => $request->segmen,
            'jartatup_jartaplok'     => $request->jartatup_jartaplok,
            'mainlink_backuplink'    => $request->mainlink_backuplink,
            'panjang'                => $request->panjang,
            'panjang_drawing'        => $request->panjang_drawing,
            'jumlah_core'            => $request->jumlah_core,
            'jenis_kabel'            => $request->jenis_kabel,
            'tipe_kabel'             => $request->tipe_kabel,
            'status'                 => $request->status,
            'keterangan'             => $request->keterangan,
            'kode_site_insan'        => $request->kode_site_insan,
            'dci_eqx'                => $request->dci_eqx,
            'travelling_time'        => $request->travelling_time,
            'verification_time'      => $request->verification_time,
            'restoration_time'       => $request->restoration_time,
            'total_corrective_time'  => $request->total_corrective_time,
            'milik'                  => $request->milik,
        ]);

        HistoriJaringan::create([
            'kode_region'            => $request->kode_region,
            'kode_tipejaringan'      => $request->kode_tipejaringan,
            'segmen'                 => $request->segmen,
            'jartatup_jartaplok'     => $request->jartatup_jartaplok,
            'mainlink_backuplink'    => $request->mainlink_backuplink,
            'panjang'                => $request->panjang,
            'panjang_drawing'        => $request->panjang_drawing,
            'jumlah_core'            => $request->jumlah_core,
            'jenis_kabel'            => $request->jenis_kabel,
            'tipe_kabel'             => $request->tipe_kabel,
            'status'                 => $request->status,
            'keterangan'             => $request->keterangan,
            'kode_site_insan'        => $request->kode_site_insan,
            'dci_eqx'                => $request->dci_eqx,
            'travelling_time'        => $request->travelling_time,
            'verification_time'      => $request->verification_time,
            'restoration_time'       => $request->restoration_time,
            'total_corrective_time'  => $request->total_corrective_time,
            'milik'                  => $request->milik,
            'histori' => 'Ditambahkan',
        ]);

        return redirect()->route('jaringan.index')
            ->with('success', 'Jaringan berhasil ditambahkan.')
            ->with('warning', 'Periksa kembali data yang dimasukkan sebelum melanjutkan.')
            ->with('error', 'Terjadi kesalahan saat menambahkan jaringan. Silakan coba lagi.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_region' => 'required',
            'kode_tipejaringan' => 'required',
            'segmen'=> 'required',
            'jartatup_jartaplok' => 'required',
            'mainlink_backuplink'=> 'nullable',
            'panjang'=> 'required',
            'panjang_drawing' => 'nullable',
            'jumlah_core' => 'required',
            'jenis_kabel'=> 'required',
            'tipe_kabel'=> 'required',
            'status'=> 'required',
            'keterangan'=> 'nullable',
            'kode_site_insan' => 'nullable',
            'dci_eqx' => 'nullable',
            'travelling_time' => 'nullable',
            'verification_time' => 'nullable',
            'restoration_time' => 'nullable',
            'total_corrective_time'=> 'nullable',
            'milik' => 'required',
        ]);

        $jaringan = ListJaringan::findOrFail($id);
        $jaringan->update([
            'kode_region'            => $request->kode_region,
            'kode_tipejaringan'      => $request->kode_tipejaringan,
            'segmen'                 => $request->segmen,
            'jartatup_jartaplok'     => $request->jartatup_jartaplok,
            'mainlink_backuplink'    => $request->mainlink_backuplink,
            'panjang'                => $request->panjang,
            'panjang_drawing'        => $request->panjang_drawing,
            'jumlah_core'            => $request->jumlah_core,
            'jenis_kabel'            => $request->jenis_kabel,
            'tipe_kabel'             => $request->tipe_kabel,
            'status'                 => $request->status,
            'keterangan'             => $request->keterangan,
            'kode_site_insan'        => $request->kode_site_insan,
            'dci_eqx'                => $request->dci_eqx,
            'travelling_time'        => $request->travelling_time,
            'verification_time'      => $request->verification_time,
            'restoration_time'       => $request->restoration_time,
            'total_corrective_time'  => $request->total_corrective_time,
            'milik'                  => $request->milik,
        ]);
        return redirect()->route('jaringan.index')
            ->with('success', 'Jaringan berhasil diupdate.')
            ->with('warning', 'Periksa kembali data yang dimasukkan sebelum melanjutkan.')
            ->with('error', 'Terjadi kesalahan saat mengupdate jaringan. Silakan coba lagi.');
    }

    public function destroy($id)
    {
        $jaringan = ListJaringan::findOrFail($id);
        $jaringan->delete();

        return redirect()->route('jaringan.index')->with('success', 'Jaringan berhasil dihapus.')
            ->with('error', 'Terjadi kesalahan saat menghapus jaringan. Silakan coba lagi.');
    }
}
