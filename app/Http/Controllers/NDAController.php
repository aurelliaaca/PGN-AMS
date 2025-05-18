<?php

namespace App\Http\Controllers;
use App\Models\VerifikasiNda;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF; 
class NDAController extends Controller
{
    public function indexNdaSuperadmin()
    {
        $ndas = VerifikasiNda::with(['user'])
            ->get();

        $user = auth()->user();
        $role = $user->role;

        $queryPending = VerifikasiNda::with('user')->where('status', 'pending')->orderBy('created_at', 'desc');
        $queryActive = VerifikasiNda::with('user')->where('status', 'diterima')->where('masaberlaku', '>', Carbon::now('Asia/Jakarta'))->orderBy('masaberlaku', 'desc');
        $queryExpired = VerifikasiNda::with('user')->where('status', 'diterima')->where('masaberlaku', '<=', Carbon::now('Asia/Jakarta'))->orderBy('masaberlaku', 'desc');

        if ($role == 3 || $role == 4) {
            $queryPending->where('user_id', $user->id);
            $queryActive->where('user_id', $user->id);
            $queryExpired->where('user_id', $user->id);
        }

        $pendingNdas = $queryPending->get();
        $activeNdas = $queryActive->get();
        $expiredNdas = $queryExpired->get();

        return view('VMS.admin.verifikasi_nda', compact('ndas', 'pendingNdas', 'activeNdas', 'expiredNdas'));
    }

    public function indexNdaUser()
    {
        $regions = Region::select('kode_region', 'nama_region')->orderBy('nama_region')->get();
        $ndas = VerifikasiNda::with(['user'])
            ->get();

        $user = auth()->user();
        $role = $user->role;

        $queryPending = VerifikasiNda::with('user')->where('status', 'menunggu persetujuan')->orderBy('created_at', 'desc');
        $queryActive = VerifikasiNda::with('user')->where('status', 'diterima')->where('masaberlaku', '>', Carbon::now('Asia/Jakarta'))->orderBy('masaberlaku', 'desc');
        $queryExpired = VerifikasiNda::with('user')->where('status', 'diterima')->where('masaberlaku', '<=', Carbon::now('Asia/Jakarta'))->orderBy('masaberlaku', 'desc');

        if ($role == 3 || $role == 4) {
            $queryPending->where('user_id', $user->id);
            $queryActive->where('user_id', $user->id);
            $queryExpired->where('user_id', $user->id);
        }

        $pendingNdas = $queryPending->get();
        $activeNdas = $queryActive->get();
        $expiredNdas = $queryExpired->get();

        return view('VMS.user.pendaftarannda', compact('regions', 'ndas', 'pendingNdas', 'activeNdas', 'expiredNdas'));
    }

   public function store(Request $request)
{
    $request->validate([
        'signature' => 'required',
        'file_path' => 'nullable',
        'status' => 'nullable',
        'signed_by' => 'nullable',
        'catatan' => 'nullable',
        'masaberlaku' => 'nullable',
    ]);

    $user = auth()->user();

    // Buat dulu ndaBaru di database
    $ndaBaru = VerifikasiNda::create([
        'user_id' => $user->id,
        'file_path' => '', // akan diupdate setelah PDF dibuat
        'status' => 'pending',
        'signature' => $request->signature,
        'signed_by' => null,
        'catatan' => null,
        'masaberlaku' => null,
    ]);

    // Tentukan type berdasarkan field perusahaan
$type = ($ndaBaru->perusahaan === 'PGNCOM') ? 'internal' : 'eksternal';

// Buat PDF
    $pdf = PDF::loadView('exports.nda' . $type . 'pdf', ['nda' => $ndaBaru]);

// Tentukan nama file dan path penyimpanan
$pdfFileName = 'nda_' . $type . '_' . $ndaBaru->id . '.pdf';
$publicPath = 'pdf/' . $pdfFileName;

// Simpan PDF ke folder public/pdf
$pdf->save(public_path($publicPath));

// Update path ke database (cukup relatif dari folder public)
$ndaBaru->update([
    'file_path' => $publicPath
]);

    return redirect()->route('verifikasi.user.nda')
        ->with('success', 'NDA berhasil ditambahkan dan PDF berhasil dibuat.');
}


}
