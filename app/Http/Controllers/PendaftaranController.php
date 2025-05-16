<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PendaftaranVms;
use App\Models\RekananVms;
use App\Models\VerifikasiNda;

class PendaftaranController extends Controller
{

    public function pendaftaranDCAF()
    {
        // Ambil NDA aktif
        $activeNdas = VerifikasiNda::where('status', 'approved')
            ->where('masa_berlaku', '>', now())
            ->get();

        // Mengembalikan view pendaftarandcaf dengan data NDA aktif
        return view('VMS.user.pendaftarandcaf', compact('activeNdas'));
    }

    public function ajukanDCS()
    {
        // Ambil NDA aktif
        $activeNdas = VerifikasiNda::with(['nda'])
            ->where('status', 'diterima')
            ->where('masa_berlaku', '>', now())
            ->orderBy('masa_berlaku', 'desc')
            ->get();

        // Debug untuk memastikan data terambil
        \Log::info('Active NDAs:', $activeNdas->toArray());

        // Mengembalikan view ajukan-dcs dengan data NDA aktif
        return view('VMS.user.ajukan-dcs', compact('activeNdas'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'no_hp_pemohon' => 'required|string|max:15',
            'pengawas' => 'required|string|max:255',
            'no_hp_pengawas' => 'required|string|max:15',
            'divisi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'lokasi' => 'required|string|max:255',
            'no_rack' => 'required|string|max:50',
            'jenis_pekerjaan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'nama_rekanan.*' => 'required|string|max:255',
            'perusahaan_rekanan.*' => 'required|string|max:255',
            'ktp_rekanan.*' => 'required|string|max:20',
            'telp_rekanan.*' => 'required|string|max:15',
            'ttd_pemohon' => 'nullable|image|max:2048',
        ]);

        // Simpan tanda tangan jika ada
        $ttd_pemohon_path = null;
        if ($request->hasFile('ttd_pemohon')) {
            $file = $request->file('ttd_pemohon');
            $ttd_pemohon_path = 'data:image/' . $file->getClientOriginalExtension() . ';base64,' . base64_encode(file_get_contents($file));
        }

        // Simpan data utama ke database
        $pendaftaran = PendaftaranVms::create($request->only([
            'nama_pemohon',
            'no_hp_pemohon',
            'pengawas',
            'no_hp_pengawas',
            'divisi',
            'tanggal_mulai',
            'tanggal_selesai',
            'waktu_mulai',
            'waktu_selesai',
            'lokasi',
            'no_rack',
            'jenis_pekerjaan',
            'keterangan_others',
            'deskripsi',
            'status'
        ]));

        // Simpan data rekanan
        foreach ($request->nama_rekanan as $i => $nama) {
            RekananVms::create([
                'pendaftaran_vms_id' => $pendaftaran->id,
                'nama' => $nama,
                'perusahaan' => $request->perusahaan_rekanan[$i],
                'no_ktp' => $request->ktp_rekanan[$i],
                'no_telepon' => $request->telp_rekanan[$i],
            ]);
        }

        // Proses perlengkapan
        $perlengkapan = [];
        foreach ($request->input('nama_perlengkapan', []) as $i => $nama) {
            $perlengkapan[] = [
                'nama' => $nama,
                'jumlah' => $request->jumlah_perlengkapan[$i] ?? '',
                'keterangan' => $request->keterangan_perlengkapan[$i] ?? '',
            ];
        }

        // Proses barang masuk
        $barang_masuk = [];
        foreach ($request->input('nama_barang_masuk', []) as $i => $nama) {
            $barang_masuk[] = [
                'nama' => $nama,
                'berat' => $request->berat_barang_masuk[$i] ?? '',
                'jumlah' => $request->jumlah_barang_masuk[$i] ?? '',
                'keterangan' => $request->keterangan_barang_masuk[$i] ?? '',
            ];
        }

        // Proses barang keluar
        $barang_keluar = [];
        foreach ($request->input('nama_barang_keluar', []) as $i => $nama) {
            $barang_keluar[] = [
                'nama' => $nama,
                'berat' => $request->berat_barang_keluar[$i] ?? '',
                'jumlah' => $request->jumlah_barang_keluar[$i] ?? '',
                'keterangan' => $request->keterangan_barang_keluar[$i] ?? '',
            ];
        }

        // Data untuk PDF
        $data = [
            'nama_pemohon' => $pendaftaran->nama_pemohon,
            'no_hp_pemohon' => $pendaftaran->no_hp_pemohon,
            'pengawas' => $pendaftaran->pengawas,
            'no_hp_pengawas' => $pendaftaran->no_hp_pengawas,
            'divisi' => $pendaftaran->divisi,
            'tanggal_mulai' => $pendaftaran->tanggal_mulai,
            'tanggal_selesai' => $pendaftaran->tanggal_selesai,
            'waktu_mulai' => $pendaftaran->waktu_mulai,
            'waktu_selesai' => $pendaftaran->waktu_selesai,
            'lokasi' => $pendaftaran->lokasi,
            'no_rack' => $pendaftaran->no_rack,
            'jenis_pekerjaan' => $pendaftaran->jenis_pekerjaan,
            'deskripsi' => $pendaftaran->deskripsi,
            'rekanan' => $pendaftaran->rekanans,
            'perlengkapan' => $perlengkapan,
            'barang_masuk' => $barang_masuk,
            'barang_keluar' => $barang_keluar,
            'tanggal_pengajuan' => now()->format('Y-m-d'),
            'ttd_pemohon' => $ttd_pemohon_path,
        ];

        // Generate PDF menggunakan dompdf
        $pdf = Pdf::loadView('VMS.pdf.pendaftaran', $data);

        // Simpan file PDF
        $filename = "DATA_CENTER_FORM-{$pendaftaran->id}.pdf";
        $filePath = storage_path("app/generated/{$filename}");
        $pdf->save($filePath);

        // Response dengan URL file PDF
        return response()->json([
            'success' => true,
            'file_url' => route('pendaftaran.download', ['filename' => $filename]),
        ]);
    }

    public function download($filename)
    {
        $filePath = storage_path("app/generated/{$filename}");

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return redirect()->back()->with('error', 'File not found.');
    }
}