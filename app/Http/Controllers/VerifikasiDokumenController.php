<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerifikasiDokumen;
use Carbon\Carbon;
use App\Notifications\DokumenVerifikasiNotification;
use App\Services\DocumentSignatureService;
use Illuminate\Support\Facades\Storage;


class VerifikasiDokumenController extends Controller
{
    protected $documentSignatureService;

    public function __construct(DocumentSignatureService $documentSignatureService)
    {
        $this->documentSignatureService = $documentSignatureService;
    }

    public function index()
    {
        $dokumen = VerifikasiDokumen::with('user')->get();
        return view('VMS.admin.verifikasi', compact('dokumen'));
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

    public function userIndex()
    {
        // Ambil data user yang sedang login
        $user = auth()->user();

        // Ambil data verifikasi dokumen user
        $dokumen = VerifikasiDokumen::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('VMS.user.upload', compact('dokumen'));
    }

    // Untuk User mengunggah dokumen
    public function upload(Request $request)
    {
        try {
            // Validasi inputan user
            $request->validate([
                'nama_dokumen' => 'required|string|max:255',
                'file' => 'required|mimes:pdf,doc,docx|max:10240', // Maksimum 10MB
                'catatan' => 'nullable|string',
            ]);

            // Ambil dokumen yang diunggah
            $file = $request->file('file');
            
            // Generate nama file yang unik
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Simpan dokumen ke storage
            $path = $file->storeAs('dokumen_verifikasi', $fileName, 'public');

            // Simpan data verifikasi ke tabel
            $verifikasi = new VerifikasiDokumen();
            $verifikasi->user_id = auth()->user()->id;
            $verifikasi->nama_dokumen = $request->nama_dokumen;
            $verifikasi->file_path = $path;
            $verifikasi->status = 'pending'; // Status awal adalah pending
            $verifikasi->catatan = $request->catatan;
            $verifikasi->save();

            return redirect()->route('verifikasi.user.index')->with('success', 'Dokumen berhasil diupload, menunggu verifikasi');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupload dokumen: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $dokumen = VerifikasiDokumen::findOrFail($id);
            
            // Tambahkan tanda tangan ke dokumen
            $originalPath = storage_path('app/public/' . $dokumen->file_path);
            $outputPath = storage_path('app/public/signed_' . $dokumen->file_path);
            
            // Pastikan file asli ada
            if (!file_exists($originalPath)) {
                throw new \Exception('File dokumen tidak ditemukan');
            }
            
            // Tambahkan tanda tangan
            $this->documentSignatureService->addSignatureToDocument($originalPath, $outputPath);
            
            // Update data dokumen
            $dokumen->status = 'diterima';
            $dokumen->masa_berlaku = Carbon::now()->addMonths(3);
            $dokumen->signed_by = auth()->user()->name;
            $dokumen->signed_at = now();
            $dokumen->file_path = 'signed_' . $dokumen->file_path;
            $dokumen->save();

            // Kirim notifikasi ke user
            $dokumen->user->notify(new DokumenVerifikasiNotification($dokumen));

            return redirect()->route('verifikasi.superadmin.index')->with('success', 'Dokumen berhasil diverifikasi dan diterima.');
        } catch (\Exception $e) {
            \Log::error('Error in approve: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memverifikasi dokumen: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            $dokumen = VerifikasiDokumen::findOrFail($id);
            $dokumen->status = 'ditolak';
            $dokumen->masa_berlaku = null; // Hapus masa berlaku jika dokumen ditolak
            $dokumen->save();

            // Kirim notifikasi ke user
            $dokumen->user->notify(new DokumenVerifikasiNotification($dokumen));

            return redirect()->route('verifikasi.superadmin.index')->with('swal', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Dokumen berhasil diverifikasi dan ditolak.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('swal', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat memverifikasi dokumen: ' . $e->getMessage()
            ]);
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
}
