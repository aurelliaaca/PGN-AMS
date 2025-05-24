<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\VerifikasiNda;
use App\Models\VerifikasiDcaf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DCAF;
use App\Models\RekananVms;
use App\Models\PerlengkapanVms;
use App\Models\BarangMasukVms;
use App\Models\BarangKeluarVms;
use Illuminate\Support\Facades\Auth;
use PDF;

class DCAFController extends Controller
{
    public function indexDcafSuperadmin()
    {
        $user = auth()->user();

        $users = User::where('bagian', 'Pengawas')->get();

        $ndas = VerifikasiNda::with(['user'])
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();

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
            ->where('masaberlaku', '>=', Carbon::now())
            ->orderBy('masaberlaku', 'desc')
            ->get();

        $expiredDcafs = VerifikasiDcaf::where('status', 'diterima')
            ->where('masaberlaku', '<', Carbon::now())
            ->orderBy('masaberlaku', 'desc')
            ->get();

        $activeNdas = VerifikasiNda::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->where('masaberlaku', '>', Carbon::now())
            ->orderBy('masaberlaku', 'desc')
            ->get();

        return view('VMS.admin.verifikasi_dcaf', compact('dcafs', 'pendingDcafs', 'historyDcafs', 'activeDcafs', 'expiredDcafs', 'activeNdas', 'users', 'ndas'));
    }

    public function indexDcafUser()
    {
        $user = auth()->user();

        $dcafs = VerifikasiDcaf::all();
        $activeNdas = VerifikasiNda::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->where('masaberlaku', '>=', Carbon::now())
            ->orderBy('masaberlaku', 'desc')
            ->get();

        \Log::info('User ID: ' . $user->id);
        \Log::info('Active NDAs:', $activeNdas->toArray());

        $dcafs = VerifikasiDcaf::with(['user', 'nda'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $rekanan = RekananVms::all();

        return view('VMS.user.pendaftarankunjungan', compact('dcafs', 'activeNdas', 'rekanan'));
    }

    public function pendaftaranDCAF()
    {
        $activeNdas = VerifikasiNda::where('status', 'diterima')
            ->where('masaberlaku', '>', now())
            ->get();

        $ndas = VerifikasiNda::with(['user'])
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();

        $rekanan = RekananVms::all();

        return view('VMS.user.pendaftarandcaf', compact('activeNdas', 'ndas', 'rekanan'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nda_id' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
            'no_rack' => 'required|string|max:255',
            'bagian' => 'required',
            'jenis_pekerjaan' => 'required|string|in:maintenance,checking,installation,dismantle,others',
            'jenis_pekerjaan_lainnya' => 'nullable|string|max:255|required_if:jenis_pekerjaan,others',
            'deskripsi_pekerjaan' => 'required|string',
            'signature' => 'required|string',
            'nama_rekanan' => 'nullable|array',
            'nama_rekanan.*' => 'required|string|max:255',
            'perusahaan_rekanan' => 'nullable|array',
            'perusahaan_rekanan.*' => 'required|string|max:255',
            'ktp_rekanan' => 'nullable|array',
            'ktp_rekanan.*' => 'required|string|max:16',
            'telp_rekanan' => 'nullable|array',
            'telp_rekanan.*' => 'required|string|max:15',
            'nama_perlengkapan' => 'nullable|array',
            'nama_perlengkapan.*' => 'required|string|max:255',
            'jumlah_perlengkapan' => 'nullable|array',
            'jumlah_perlengkapan.*' => 'required|integer|min:1',
            'keterangan_perlengkapan' => 'nullable|array',
            'keterangan_perlengkapan.*' => 'required|string|max:255',
            'nama_barang_masuk' => 'nullable|array',
            'nama_barang_masuk.*' => 'required|string|max:255',
            'jumlah_barang_masuk' => 'nullable|array',
            'jumlah_barang_masuk.*' => 'required|integer|min:1',
            'berat_barang_masuk' => 'nullable|array',
            'berat_barang_masuk.*' => 'required|numeric|min:0',
            'keterangan_barang_masuk' => 'nullable|array',
            'keterangan_barang_masuk.*' => 'required|string|max:255',
            'nama_barang_keluar' => 'nullable|array',
            'nama_barang_keluar.*' => 'required|string|max:255',
            'jumlah_barang_keluar' => 'nullable|array',
            'jumlah_barang_keluar.*' => 'required|integer|min:1',
            'berat_barang_keluar' => 'nullable|array',
            'berat_barang_keluar.*' => 'required|numeric|min:0',
            'keterangan_barang_keluar' => 'nullable|array',
            'keterangan_barang_keluar.*' => 'required|string|max:255',
        ]);

        $dcaf = VerifikasiDcaf::create([
            'user_id' => Auth::id(),
            'nda_id' => $validated['nda_id'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'lokasi' => $validated['lokasi'],
            'no_rack' => $validated['no_rack'],
            'jenis_pekerjaan' => $validated['jenis_pekerjaan'] === 'others' ? $validated['jenis_pekerjaan_lainnya'] : $validated['jenis_pekerjaan'],
            'deskripsi_pekerjaan' => $validated['deskripsi_pekerjaan'],
            'signature' => $validated['signature'],
            'status' => 'pending',
            'created_at' => Carbon::now('Asia/Jakarta'),
        ]);

        $user->update([
            'mobile_number' => $request->mobile_number,
            'perusahaan' => $request->perusahaan,
            'bagian' => $request->bagian,
        ]);

        if (!empty($validated['nama_rekanan'])) {
            foreach ($validated['nama_rekanan'] as $index => $nama) {
                RekananVms::create([
                    'dcaf_id' => $dcaf->id,
                    'nama' => $nama,
                    'perusahaan' => $validated['perusahaan_rekanan'][$index],
                    'ktp' => $validated['ktp_rekanan'][$index],
                    'telp' => $validated['telp_rekanan'][$index],
                ]);
            }
        }

        if (!empty($validated['nama_perlengkapan'])) {
            foreach ($validated['nama_perlengkapan'] as $index => $nama) {
                PerlengkapanVms::create([
                    'dcaf_id' => $dcaf->id,
                    'nama' => $nama,
                    'jumlah' => $validated['jumlah_perlengkapan'][$index],
                    'keterangan' => $validated['keterangan_perlengkapan'][$index],
                ]);
            }
        }

        if (!empty($validated['nama_barang_masuk'])) {
            foreach ($validated['nama_barang_masuk'] as $index => $nama) {
                BarangMasukVms::create([
                    'dcaf_id' => $dcaf->id,
                    'nama' => $nama,
                    'jumlah' => $validated['jumlah_barang_masuk'][$index],
                    'berat' => $validated['berat_barang_masuk'][$index],
                    'keterangan' => $validated['keterangan_barang_masuk'][$index],
                ]);
            }
        }

        if (!empty($validated['nama_barang_keluar'])) {
            foreach ($validated['nama_barang_keluar'] as $index => $nama) {
                BarangKeluarVms::create([
                    'dcaf_id' => $dcaf->id,
                    'nama' => $nama,
                    'jumlah' => $validated['jumlah_barang_keluar'][$index],
                    'berat' => $validated['berat_barang_keluar'][$index],
                    'keterangan' => $validated['keterangan_barang_keluar'][$index],
                ]);
            }
        }

        $pdfFileName = 'DCAF_' . $dcaf->id . '.pdf';
        $publicPath = 'pdf/' . $pdfFileName;

        $pdf = Pdf::loadView('exports.dcaf', compact('dcaf'));

        $pdf->save(public_path($publicPath));

        $dcaf->file_path = $publicPath;
        $dcaf->save();
        return redirect()->route('verifikasi.user.dcaf')->with('success', 'Data DCAF berhasil disimpan.');
    }

    public function update(Request $request, VerifikasiDcaf $dcaf)
    {
        try {
            $status = $request->input('status');

            if (!in_array($status, ['diterima', 'ditolak'])) {
                return redirect()->back()->with('warning', 'Status tidak valid.');
            }

            $dcaf->status = $status;

            $updatedAt = \Carbon\Carbon::now('Asia/Jakarta');
            $dcaf->updated_at = $updatedAt;

            if ($status === 'diterima') {
                $dcaf->signed_by = auth()->user()->id;
                $dcaf->masaberlaku = $updatedAt->copy()->addWeeks(1)->setTime(23, 59, 59);
                $dcaf->update([
                    'pengawas' => $request->pengawas,
                ]);

                $dcaf->save();

                if ($dcaf->file_path && file_exists(public_path($dcaf->file_path))) {
                    $publicPath = $dcaf->file_path;
                } else {
                    $pdfFileName = 'DCAF_' . $dcaf->id . '.pdf';
                    $publicPath = 'pdf/' . $pdfFileName;
                }

                $pdf = Pdf::loadView('exports.dcaf', compact('dcaf'));
                $pdf->save(public_path($publicPath));

                if (!$dcaf->file_path) {
                    $dcaf->file_path = $publicPath;
                    $dcaf->save();
                }
            } else {
                $dcaf->signed_by = null;
                $dcaf->masaberlaku = null;
                $dcaf->save();
            }

            if ($status === 'diterima') {
                return redirect()->back()->with('success', 'DCAF berhasil disetujui dan PDF ter-update.');
            } else {
                return redirect()->back()->with('warning', 'DCAF telah ditolak.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
