<?php

namespace App\Http\Controllers;

use App\Models\ListJaringan;
use App\Models\Region;
use App\Models\Tipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JaringanImport;
use Barryvdh\DomPDF\Facade\Pdf;

class JaringanController extends Controller
{

    public function jaringan()
    {
        $jaringan = ListJaringan::all();

        $regions = Region::all();
        $tipeJaringan = Tipe::all();

        return view('aset.jaringan', compact('jaringan', 'regions', 'tipeJaringan'));
    }

    public function getLastKodeSiteInsan(Request $request)
    {
        $baseKode = $request->input('baseKode');

        // Ambil data dari database berdasarkan RO dan tipe_jaringan
        $lastJaringan = ListJaringan::where('kode_site_insan', 'like', $baseKode . '%')
            ->orderBy('kode_site_insan', 'desc')
            ->get(); // Ambil semua yang sesuai

        // Ambil angka terakhir
        $lastNumber = 1; // Default ke 1 jika tidak ada

        Log::info('Base Kode: ' . $baseKode);
        Log::info('Jumlah Jaringan Ditemukan: ' . $lastJaringan->count());

        if ($lastJaringan->isNotEmpty()) {
            foreach ($lastJaringan as $jaringan) {
                $lastKode = $jaringan->kode_site_insan;
                Log::info('Kode Jaringan: ' . $lastKode);
                // Ekstrak angka dari kode_site_insan
                preg_match('/(\d+)$/', $lastKode, $matches); // Ambil angka terakhir
                if (isset($matches[1])) {
                    $currentNumber = intval($matches[1]);
                    Log::info('Angka Ditemukan: ' . $currentNumber);
                    // Pastikan kita mengambil angka terbesar
                    if ($currentNumber > $lastNumber) {
                        $lastNumber = $currentNumber; // Ambil angka terbesar
                    }
                }
            }
            $lastNumber++; // Tambahkan 1 untuk mendapatkan angka berikutnya
        }

        return response()->json(['lastNumber' => $lastNumber]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);

        // Cek apakah file diterima
        if (!$request->hasFile('file')) {
            return response()->json(['success' => false, 'message' => 'Tidak ada file yang diunggah.']);
        }

        \Log::info('File yang diupload: ', [$request->file('file')->getClientOriginalName()]);

        try {
            Excel::import(new JaringanImport, $request->file('file'));
            return response()->json(['success' => true, 'message' => 'Data berhasil diimpor.']);
        } catch (\Exception $e) {
            \Log::error('Error importing file: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()]);
        }
    }
    
    public function export(Request $request)
    {
        // Meningkatkan batas memori dan waktu eksekusi
        ini_set('memory_limit', '1G'); // Atur batas memori jadi 1GB
        set_time_limit(300); // Atur batas waktu eksekusi jadi 5 menit
    
        try {
            $request->validate([
                'option' => 'required|in:all,unique',
                'region' => 'nullable|string'
            ]);
    
            $query = ListJaringan::with('region')->select([
                'id_jaringan', 'RO', 'tipe_jaringan', 'segmen', 'jartatup_jartaplok',
                'mainlink_backuplink', 'panjang', 'panjang_drawing', 'jumlah_core',
                'jenis_kabel', 'tipe_kabel', 'status', 'ket', 'ket2', 'kode_site_insan',
                'update', 'route', 'dci_eqx'
            ]);
    
            if ($request->option === 'unique') {
                $query->distinct();
            }
    
            if ($request->region) {
                $query->whereHas('region', function ($q) use ($request) {
                    $q->where('kode_region', $request->region);
                });
            }
    
            // Pakai streaming agar tidak overload memori
            $pdf = app('dompdf.wrapper');
            $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
            $pdf->getDomPDF()->set_option('isPhpEnabled', true);
    
            $pdf->loadHTML(view('aset.export-jaringan', ['jaringan' => $query->cursor()])->render())
                ->setPaper('a4', 'landscape');
    
            // Simpan ke file dengan stream
            $filePath = 'exports/data_jaringan_' . time() . '.pdf';
            file_put_contents(public_path($filePath), $pdf->output());
    
            return response()->json(['success' => true, 'file_url' => url($filePath)]);
        } catch (\Exception $e) {
            \Log::error('Kesalahan saat mengekspor data: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengekspor data.']);
        }
    }   
    
    public function lihatDetail($id_jaringan)
    {
        $jaringan = ListJaringan::with(['region', 'tipe'])->find($id_jaringan);

        if (!$jaringan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'region' => $jaringan->region ? $jaringan->region->nama_region : 'Region Tidak Ditemukan',
                'tipe_jaringan' => $jaringan->tipe ? $jaringan->tipe->nama_tipe : 'Tipe Tidak Ditemukan',
                'segmen' => $jaringan->segmen,
                'jartatup_jartaplok' => $jaringan->jartatup_jartaplok,
                'mainlink_backuplink' => $jaringan->mainlink_backuplink,
                'panjang' => $jaringan->panjang,
                'panjang_drawing' => $jaringan->panjang_drawing,
                'jumlah_core' => $jaringan->jumlah_core,
                'jenis_kabel' => $jaringan->jenis_kabel,
                'tipe_kabel' => $jaringan->tipe_kabel,
                'status' => $jaringan->status,
                'ket' => $jaringan->ket,
                'ket2' => $jaringan->ket2,
                'kode_site_insan' => $jaringan->kode_site_insan,
                'dci_eqx' => $jaringan->dci_eqx,
                'update' => $jaringan->update,
                'route' => $jaringan->route,
            ]
        ]);
    }
    
}
