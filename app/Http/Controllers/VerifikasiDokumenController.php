<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerifikasiDokumen;
use App\Models\VerifikasiNda;
use App\Models\VerifikasiDcaf;
use App\Models\Nda;
use App\Models\Region;
use Carbon\Carbon;
use App\Notifications\DokumenVerifikasiNotification;
use App\Services\DocumentSignatureService;
use PDF; // Pastikan Anda mengimpor PDF
use Illuminate\Support\Facades\Storage;


class VerifikasiDokumenController extends Controller
{
    protected $documentSignatureService;

    public function __construct(DocumentSignatureService $documentSignatureService)
    {
        $this->documentSignatureService = $documentSignatureService;
    }

    public function indexNda()
    {
        $ndas = VerifikasiNda::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingNdas = VerifikasiNda::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $historyNdas = VerifikasiNda::whereIn('status', ['diterima', 'ditolak'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $activeNdas = VerifikasiNda::where('status', 'diterima')
            ->where('masa_berlaku', '>', Carbon::now())
            ->orderBy('masa_berlaku', 'desc')
            ->get();

        $expiredNdas = VerifikasiNda::where('status', 'diterima')
            ->where('masa_berlaku', '<=', Carbon::now())
            ->orderBy('masa_berlaku', 'desc')
            ->get();


        return view('VMS.admin.verifikasi_nda', compact('ndas', 'pendingNdas', 'historyNdas', 'activeNdas', 'expiredNdas'));
    }

    public function indexDcaf()
    {
        $dcafs = VerifikasiDcaf::with(['user', 'nda'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingDcafs = VerifikasiDcaf::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $historyDcafs = VerifikasiDcaf::whereIn('status', ['diterima', 'ditolak'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $activeDcafs = VerifikasiDcaf::where('status', 'diterima')
            ->where('masa_berlaku', '>', Carbon::now())
            ->orderBy('masa_berlaku', 'desc')
            ->get();

        $expiredDcafs = VerifikasiDcaf::where('status', 'diterima')
            ->where('masa_berlaku', '<=', Carbon::now())
            ->orderBy('masa_berlaku', 'desc')
            ->get();

        return view('VMS.admin.verifikasi_dcaf', compact('dcafs', 'pendingDcafs', 'historyDcafs', 'activeDcafs', 'expiredDcafs'));
    }

    public function update(Request $request, $id)
    {
        $dokumen = VerifikasiDokumen::findOrFail($id);

        if ($request->action == 'terima') {
            $dokumen->status = 'diterima';
        } elseif ($request->action == 'tolak') {
            $dokumen->status = 'ditolak';
        }

        $dokumen->save();

        return redirect()->back()->with('swal', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Status dokumen diperbarui.'
        ]);
    }

    public function userDcafIndex()
    {
        // Ambil data user yang sedang login
        $user = auth()->user();

        // Ambil data NDA yang aktif
        $activeNdas = VerifikasiNda::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->where('masa_berlaku', '>', Carbon::now())
            ->orderBy('masa_berlaku', 'desc')
            ->get();

        // Ambil data DCAF yang diajukan
        $dcafs = VerifikasiDcaf::with(['user', 'nda'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('VMS.user.pendaftarankunjungan', compact('dcafs', 'activeNdas'));
    }

    // Untuk User mengunggah dokumen
    public function upload(Request $request)
    {
        try {
            // Validasi inputan user
            $request->validate([
                'verifikasi_nda_id' => 'required|exists:verifikasi_nda,id',
                'dcaf_file' => 'required|mimes:pdf,doc,docx|max:10240',
                'catatan' => 'nullable|string',
            ]);

            // Ambil dokumen yang diunggah
            $dcafFile = $request->file('dcaf_file');

            // Generate nama file yang unik
            $dcafFileName = time() . '_dcaf_' . $dcafFile->getClientOriginalName();

            // Simpan dokumen ke storage
            $dcafPath = $dcafFile->storeAs('dokumen_verifikasi', $dcafFileName, 'public');

            // Simpan data verifikasi ke tabel
            $verifikasi = new VerifikasiDcaf();
            $verifikasi->user_id = auth()->user()->id;
            $verifikasi->verifikasi_nda_id = $request->verifikasi_nda_id;
            $verifikasi->file_path = $dcafPath;
            $verifikasi->status = 'pending';
            $verifikasi->catatan = $request->catatan;
            $verifikasi->save();

            return redirect()->route('verifikasi.user.dcaf')->with('success', 'Dokumen berhasil diupload, menunggu verifikasi');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupload dokumen: ' . $e->getMessage());
        }
    }

    public function approveNda($id)
    {
        try {
            $nda = VerifikasiNda::findOrFail($id);
            $nda->status = 'diterima';
            $nda->masa_berlaku = Carbon::now()->addMonths(3);
            $nda->save();

            return redirect()->route('verifikasi.superadmin.nda')->with('success', 'NDA berhasil diverifikasi dan diterima.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function rejectNda($id)
    {
        try {
            $nda = VerifikasiNda::findOrFail($id);
            $nda->status = 'ditolak';
            $nda->masa_berlaku = null;
            $nda->save();

            return redirect()->route('verifikasi.superadmin.nda')->with('success', 'NDA berhasil diverifikasi dan ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function approveDcaf($id)
    {
        try {
            $dcaf = VerifikasiDcaf::findOrFail($id);
            $dcaf->status = 'diterima';
            $dcaf->masa_berlaku = Carbon::now()->addDays(7);
            $dcaf->save();

            return redirect()->route('verifikasi.superadmin.dcaf')->with('success', 'DCAF berhasil diverifikasi dan diterima.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function rejectDcaf($id)
    {
        try {
            $dcaf = VerifikasiDcaf::findOrFail($id);
            $dcaf->status = 'ditolak';
            $dcaf->masa_berlaku = null;
            $dcaf->save();

            return redirect()->route('verifikasi.superadmin.dcaf')->with('success', 'DCAF berhasil diverifikasi dan ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function sign(Request $request, $id)
    {
        try {
            $dokumen = VerifikasiDokumen::findOrFail($id);

            // Validasi input
            $request->validate([
                'signature' => 'required|string',
                'status' => 'required|in:diterima,ditolak'
            ]);

            // Simpan tanda tangan
            $dokumen->signature = $request->signature;
            $dokumen->signed_by = auth()->user()->name;
            $dokumen->signed_at = now();
            $dokumen->status = $request->status;

            if ($request->status == 'diterima') {
                $dokumen->masa_berlaku = now()->addMonths(3);

                // Tambahkan tanda tangan ke dokumen
                $originalPath = storage_path('app/public/' . $dokumen->file_path);
                $outputPath = storage_path('app/public/signed_' . $dokumen->file_path);

                $this->documentSignatureService->addSignatureToDocument($originalPath, $outputPath);

                // Update path file
                $dokumen->file_path = 'signed_' . $dokumen->file_path;
            } else {
                $dokumen->masa_berlaku = null;
            }

            $dokumen->save();

            // Kirim notifikasi ke user
            $dokumen->user->notify(new DokumenVerifikasiNotification($dokumen));

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diverifikasi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $activeNdas = VerifikasiNda::where('user_id', auth()->id())
            ->where('status', 'diterima')
            ->where('masa_berlaku', '>', Carbon::now())
            ->orderBy('masa_berlaku', 'desc')
            ->get();

        return view('VMS.user.pendaftarankunjungan', compact('activeNdas'));
    }


    public function userNdaIndex()
    {
        $VerNdas = VerifikasiNda::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        $NDA = Nda::all();
        $regions = Region::all();
        return view('VMS.user.pendaftarannda', compact('VerNdas', 'NDA', 'regions'));
    }

    public function storeVerifNda(Request $request)
    {
        try {
            $request->validate([
                'file_path' => 'required|mimes:pdf,doc,docx|max:10240',
                'catatan' => 'nullable|string'
            ]);

            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dokumen_verifikasi', $fileName, 'public');

            VerifikasiNda::create([
                'user_id' => auth()->id(),
                'file_path' => $path,
                'status' => 'pending',
                'catatan' => $request->catatan
            ]);

            return redirect()->route('verifikasi.user.nda')->with('success', 'NDA berhasil diajukan, menunggu verifikasi');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeNda(Request $request)
    {
        $request->validate([
            'nda_name' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'perusahaan' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'bagian' => 'nullable|string|max:255',
        ]);

        $tanggal = now(); // Tanggal sekarang
        $tanggal_berlaku = now()->addMonths(3); // Tanggal berlaku 3 bulan dari sekarang

        $nda = new Nda();
        $nda->name = $request->nda_name;
        $nda->no_ktp = $request->no_ktp;
        $nda->alamat = $request->alamat;
        $nda->perusahaan = $request->perusahaan;
        $region = \App\Models\Region::where('kode_region', $request->kode_region)->first();
        $nda->region = $region ? $region->nama_region : null;
                $nda->bagian = $request->bagian;
        $nda->tanggal = $tanggal;
        $nda->tanggal_berlaku = $tanggal_berlaku;
        $nda->signature = $request->signature;
        $nda->save();

        // Tentukan tipe NDA berdasarkan ada tidaknya data perusahaan
        $type = $nda->perusahaan ? 'eksternal' : 'internal';
        
        // Generate PDF sesuai tipe
        $pdf = PDF::loadView('exports.nda' . $type . 'pdf', compact(var_name: 'nda'));
        $pdf->save(public_path('pdf/nda_' . $type . '_' . $nda->id . '.pdf'));

        return redirect()->route('verifikasi.user.nda')->with('success', 'NDA berhasil ditambahkan.');
    }

    public function downloadNda($id)
    {
        $nda = Nda::findOrFail($id);
        
        // Tentukan tipe NDA berdasarkan ada tidaknya data perusahaan
        $type = $nda->perusahaan ? 'eksternal' : 'internal';
        
        // Generate PDF sesuai tipe
        $pdf = PDF::loadView('exports.nda' . $type . 'pdf', compact(var_name: 'nda'));
        return $pdf->download('nda_' . $type . '_' . $nda->id . '.pdf');
    }
}
