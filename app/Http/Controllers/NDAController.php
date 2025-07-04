<?php

namespace App\Http\Controllers;
use App\Models\VerifikasiNda;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NDAController extends Controller
{
    public function indexNdaSuperadmin()
    {
        $ndas = VerifikasiNda::with(['user'])
            ->orderBy('id', 'desc')
            ->get();

        $user = auth()->user();
        $role = $user->role;

        $queryPending = VerifikasiNda::with('user')->where('status', 'pending')->orderBy('created_at', 'desc');
        $queryActive = VerifikasiNda::with('user')->where('status', 'diterima')->where('masaberlaku', '>=', Carbon::now('Asia/Jakarta'))->orderBy('masaberlaku', 'desc');
        $queryExpired = VerifikasiNda::with('user')->where('status', 'diterima')->where('masaberlaku', '<', Carbon::now('Asia/Jakarta'))->orderBy('masaberlaku', 'desc');

        if ($role == 4 || $role == 5) {
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
        $user = auth()->user();
        $role = $user->role;

        if ($role == 4 || $role == 5) {
            $ndas = VerifikasiNda::with('user')
                ->where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $ndas = VerifikasiNda::with('user')
                ->orderBy('id', 'desc')
                ->get();
        }

        $queryPending = VerifikasiNda::with('user')->where('status', 'pending')->orderBy('created_at', 'desc');
        $queryRejected = VerifikasiNda::with('user')->where('status', 'ditolak')->orderBy('created_at', 'desc');
        $queryActive = VerifikasiNda::with('user')->where('status', 'diterima')->where('masaberlaku', '>', Carbon::now('Asia/Jakarta'))->orderBy('masaberlaku', 'desc');
        $queryExpired = VerifikasiNda::with('user')->where('status', 'diterima')->where('masaberlaku', '<=', Carbon::now('Asia/Jakarta'))->orderBy('masaberlaku', 'desc');

        if ($role == 4 || $role == 5) {
            $queryPending->where('user_id', $user->id);
            $queryActive->where('user_id', $user->id);
            $queryExpired->where('user_id', $user->id);
        }

        $pendingNdas = $queryPending->get();
        $rejectedNdas = $queryRejected->get();
        $activeNdas = $queryActive->get();
        $expiredNdas = $queryExpired->get();

        return view('VMS.user.pendaftarannda', compact('regions', 'ndas', 'pendingNdas', 'rejectedNdas', 'activeNdas', 'expiredNdas'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (empty($user->noktp) || empty($user->name) || empty($user->region)) {
            return redirect()->back()->with('error', 'Data KTP, Nama, atau Region kamu belum lengkap. Mohon lengkapi profil terlebih dahulu.');
        }

        $request->validate([
            'alamat' => 'required',
            'perusahaan' => 'nullable',
            'bagian' => 'nullable',
            'signature' => 'required',
        ], [
            'signature.required' => 'Tanda tangan wajib diisi!',
        ]);

        $user->update([
            'alamat' => $request->alamat,
            'perusahaan' => $request->perusahaan,
            'bagian' => $request->bagian,
        ]);

        $ndaBaru = VerifikasiNda::create([
            'user_id' => $user->id,
            'file_path' => '',
            'status' => 'pending',
            'signature' => $request->signature,
            'signed_by' => null,
            'catatan' => $request->catatan,
            'masaberlaku' => null,
            'created_at' => Carbon::now('Asia/Jakarta'),
            'updated_at' => null,
        ]);

        $type = ($user->role == 4) ? 'internal' : 'eksternal';

        $pdf = PDF::loadView('exports.nda' . $type . 'pdf', ['nda' => $ndaBaru, 'user' => $user]);

        $pdfFileName = 'nda' . $type . '_' . $ndaBaru->id . '.pdf';
        $publicPath = 'pdf/' . $pdfFileName;

        $pdf->save(public_path($publicPath));

        $ndaBaru->update(['file_path' => $publicPath]);

        return redirect()->route('verifikasi.user.nda')
            ->with('success', 'NDA berhasil ditambahkan dan PDF berhasil dibuat.');
    }
    public function update(Request $request, VerifikasiNda $nda)
    {
        try {
            $status = $request->input('status');

            if (!in_array($status, ['diterima', 'ditolak'])) {
                return redirect()->back()->with('warning', 'Status tidak valid.');
            }

            $nda->status = $status;

            $updatedAt = Carbon::now('Asia/Jakarta');
            $nda->updated_at = $updatedAt;

            if ($status === 'diterima') {
                if (!auth()->user()->signature) {
                    return redirect()->back()->with('warning', 'User belum memiliki tanda tangan. Harap isi di menu Profil.');
                }

                $nda->signed_by = auth()->user()->id;
                $nda->masaberlaku = $updatedAt->copy()->addMonths(3)->setTime(23, 59, 59);

                $nda->save();

                $type = ($nda->user->role == 4) ? 'internal' : 'eksternal';

                if ($nda->file_path && file_exists(public_path($nda->file_path))) {
                    $publicPath = $nda->file_path;
                } else {
                    $pdfFileName = 'nda' . $type . '_' . $nda->id . '.pdf';
                    $publicPath = 'pdf/' . $pdfFileName;
                }

                $pdf = PDF::loadView('exports.nda' . $type . 'pdf', ['nda' => $nda, 'user' => $nda->user]);
                $pdf->save(public_path($publicPath));

                if (!$nda->file_path) {
                    $nda->file_path = $publicPath;
                    $nda->save();
                }
            } else {
                $nda->signed_by = null;
                $nda->masaberlaku = null;
                $nda->save();
            }

            if ($status === 'diterima') {
                return redirect()->back()->with('success', 'NDA berhasil disetujui dan PDF ter-update.');
            } else {
                return redirect()->back()->with('warning', 'NDA telah ditolak.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
