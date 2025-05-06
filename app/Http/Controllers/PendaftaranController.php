<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Models\PendaftaranVms;
use App\Models\RekananVms;

class PendaftaranController extends Controller
{

    public function pendaftaranDCAF()
    {
        // Mengembalikan view pendaftarandcaf
        return view('VMS.user.pendaftarandcaf');
    }
    public function store(Request $request)
    {
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
            'keterangan',
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

        // Load template Word
        $templatePath = resource_path('templates/DATA CENTER AUTHORIZATION FORM[1] (1).docx');
        $template = new TemplateProcessor($templatePath);

        // Isi data umum
        $template->setValue('nama_pemohon', $pendaftaran->nama_pemohon);
        $template->setValue('no_hp_pemohon', $pendaftaran->no_hp_pemohon);
        $template->setValue('pengawas', $pendaftaran->pengawas);
        $template->setValue('no_hp_pengawas', $pendaftaran->no_hp_pengawas);
        $template->setValue('divisi', $pendaftaran->divisi);
        $template->setValue('tanggal_mulai', $pendaftaran->tanggal_mulai);
        $template->setValue('tanggal_selesai', $pendaftaran->tanggal_selesai);
        $template->setValue('waktu_mulai', $pendaftaran->waktu_mulai);
        $template->setValue('waktu_selesai', $pendaftaran->waktu_selesai);
        $template->setValue('lokasi', $pendaftaran->lokasi);
        $template->setValue('no_rack', $pendaftaran->no_rack);
        $template->setValue('jenis_pekerjaan', $pendaftaran->jenis_pekerjaan);
        $template->setValue('deskripsi', $pendaftaran->deskripsi);
        $template->setValue('tanggal_pengajuan', now()->format('Y-m-d'));
        $template->setValue('nama_pengawas', $pendaftaran->pengawas);

        // Handle ttd_nama (tanda tangan gambar)
        // Handle ttd_nama (tanda tangan gambar)
        if ($request->hasFile('ttd_pemohon')) {
            $signatureFile = $request->file('ttd_pemohon');
            $signaturePath = $signatureFile->store('signatures', 'public');

            // Ambil ukuran asli gambar
            list($width, $height) = getimagesize(storage_path('app/public/' . $signaturePath));

            // Target maksimal ukuran tanda tangan di Word
            $maxWidth = 150; // px
            $maxHeight = 80; // px

            // Hitung rasio scale
            $scale = min($maxWidth / $width, $maxHeight / $height, 1);

            $newWidth = intval($width * $scale);
            $newHeight = intval($height * $scale);

            // Set gambar ke template
            $template->setImageValue('ttd_pemohon', [
                'path' => storage_path('app/public/' . $signaturePath),
                'width' => $newWidth,
                'height' => $newHeight,
                'ratio' => true,
            ]);

            // Tambahkan log
            \Log::info('Tanda tangan berhasil diupload', [
                'original_name' => $signatureFile->getClientOriginalName(),
                'stored_path' => $signaturePath,
                'width' => $width,
                'height' => $height,
            ]);

        } else {
            // Kalau tidak upload tanda tangan, isi teks kosong
            \Log::warning('Tidak ada file tanda tangan diupload.');
            $template->setValue('ttd_pemohon', 'Belum Ada Tanda Tangan');
        }


        // Isi data rekanan
        $maxRekanan = 7;
        for ($i = 0; $i < $maxRekanan; $i++) {
            $num = $i + 1;
            $r = $pendaftaran->rekanans[$i] ?? null;

            $template->setValue("nama_rekanan#{$num}", $r->nama ?? '-');
            $template->setValue("perusahaan_rekanan#{$num}", $r->perusahaan ?? '-');
            $template->setValue("ktp_rekanan#{$num}", $r->no_ktp ?? '-');
            $template->setValue("telp_rekanan#{$num}", $r->no_telepon ?? '-');
        }

        // Handle barang/perlengkapan/barang masuk/keluar
        $fillRepeatingBlock = function ($template, $prefix, $data, $max) {
            for ($i = 0; $i < $max; $i++) {
                $num = $i + 1;
                $item = $data[$i] ?? ['nama' => '-', 'jumlah' => '-', 'berat' => '-', 'keterangan' => '-'];
                $template->setValue("{$prefix}_nama#{$num}", $item['nama'] ?? '-');
                if (isset($item['jumlah'])) {
                    $template->setValue("{$prefix}_jumlah#{$num}", $item['jumlah']);
                }
                if (isset($item['berat'])) {
                    $template->setValue("{$prefix}_berat#{$num}", $item['berat']);
                }
                $template->setValue("{$prefix}_keterangan#{$num}", $item['keterangan'] ?? '-');
            }
        };

        // Perlengkapan
        $perlengkapan = collect($request->input('nama_perlengkapan', []))->map(function ($nama, $i) use ($request) {
            return [
                'nama' => $nama,
                'jumlah' => $request->input('jumlah_perlengkapan')[$i] ?? '-',
                'keterangan' => $request->input('keterangan_perlengkapan')[$i] ?? '-',
            ];
        })->toArray();

        // Barang Masuk
        $barangMasuk = collect($request->input('nama_barang_masuk', []))->map(function ($nama, $i) use ($request) {
            return [
                'nama' => $nama,
                'berat' => $request->input('berat_barang_masuk')[$i] ?? '-',
                'jumlah' => $request->input('jumlah_barang_masuk')[$i] ?? '-',
                'keterangan' => $request->input('keterangan_barang_masuk')[$i] ?? '-',
            ];
        })->toArray();

        // Barang Keluar
        $barangKeluar = collect($request->input('nama_barang_keluar', []))->map(function ($nama, $i) use ($request) {
            return [
                'nama' => $nama,
                'berat' => $request->input('berat_barang_keluar')[$i] ?? '-',
                'jumlah' => $request->input('jumlah_barang_keluar')[$i] ?? '-',
                'keterangan' => $request->input('keterangan_barang_keluar')[$i] ?? '-',
            ];
        })->toArray();

        // Isi ke template
        $fillRepeatingBlock($template, 'perlengkapan', $perlengkapan, 8);
        $fillRepeatingBlock($template, 'barangmasuk', $barangMasuk, 10);
        $fillRepeatingBlock($template, 'barangkat', $barangKeluar, 10);

        // Simpan file hasil generate
        $filename = "DATA_CENTER_FORM-{$pendaftaran->id}.docx";
        $filePath = storage_path("app/generated/{$filename}");
        $template->saveAs($filePath);

        // Response
        return response()->json([
            'success' => true,
            'file_url' => route('pendaftaran.download', ['id' => $filename]),
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