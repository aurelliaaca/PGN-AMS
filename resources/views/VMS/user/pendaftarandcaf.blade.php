@extends('layouts.app')

@section('content')

    <head>
        <link rel="stylesheet" href="{{ asset('css/general.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabelaset.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
        <link rel="stylesheet" href="{{ asset('css/filter.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
            }

            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
                max-width: 500px;
                border-radius: 8px;
            }

            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }

            .close:hover {
                color: black;
            }

            .modal-footer {
                margin-top: 20px;
                text-align: right;
            }

            .btn-primary {
                background-color: #5a54ea;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                cursor: pointer;
            }

            .btn-primary:hover {
                background-color: #4a44da;
            }

            .form-control {
                width: 100%;
                padding: 8px;
                margin: 8px 0;
                border: 1px solid #ccc;
                border-radius: 6px;
            }

            .mb-3 {
                margin-bottom: 1rem;
            }
        </style>
    </head>

    <div class="form-page-content">
        <h1 style="text-align: center; margin-bottom: 24px; font-size: 28px; font-weight: bold;">
            Kunjungan Data Center
        </h1>

        <form id="addPendaftaranForm" method="POST" action="{{ route('pendaftaran.store') }}" enctype="multipart/form-data">
            @csrf
            <div style="max-width: 900px; margin: 0 auto;">

                <!-- Baris 1 -->
                <div style="display: flex; gap: 20px; margin-bottom: 16px;">
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Nama Pemohon</label>
                        <input type="text" name="nama_pemohon" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>No HP Pemohon</label>
                        <input type="text" name="no_hp_pemohon" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                </div>

                <!-- Baris 2 -->
                <div style="display: flex; gap: 20px; margin-bottom: 16px;">
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Pengawas Lapangan</label>
                        <input type="text" name="pengawas" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>No HP Pengawas</label>
                        <input type="text" name="no_hp_pengawas" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                </div>

                <!-- Rekanan -->
                <div style="margin-bottom: 16px;">
                    <label>Rekanan</label>
                    <div id="rekanan-container">
                        <div style="display: flex; gap: 20px; margin-bottom: 8px;">
                            <input type="text" name="nama_rekanan[]" placeholder="Nama" required
                                style="flex: 1; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                            <input type="text" name="perusahaan_rekanan[]" placeholder="Nama Perusahaan" required
                                style="flex: 1; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                        </div>
                        <div style="display: flex; gap: 20px;">
                            <input type="text" name="ktp_rekanan[]" placeholder="No KTP" required
                                style="flex: 1; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                            <input type="text" name="telp_rekanan[]" placeholder="No Telepon" required
                                style="flex: 1; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                        </div>
                    </div>
                    <button type="button" onclick="addRekanan()"
                        style="margin-top: 12px; background-color: #5a54ea; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer;">
                        + Tambah Rekanan
                    </button>
                </div>

                <!-- Divisi -->
                <div style="display: flex; flex-direction: column; margin-bottom: 16px;">
                    <label>Divisi</label>
                    <input type="text" name="divisi" required
                        style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                </div>

                <!-- Tanggal -->
                <div style="display: flex; gap: 20px; margin-bottom: 16px;">
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                </div>

                <!-- Waktu -->
                <div style="display: flex; gap: 20px; margin-bottom: 16px;">
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                </div>

                <!-- Lokasi & Rack -->
                <div style="display: flex; gap: 20px; margin-bottom: 16px;">
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Lokasi Pengerjaan</label>
                        <input type="text" name="lokasi" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Nomor Rack</label>
                        <input type="text" name="no_rack" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                </div>

                <!-- Jenis Pekerjaan -->
                <div style="display: flex; gap: 20px; margin-bottom: 16px;">
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Jenis Pekerjaan</label>
                        <select name="jenis_pekerjaan" id="jenis_pekerjaan" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                            <option value="">Pilih Jenis Pekerjaan</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="checking">Checking</option>
                            <option value="installation">Installation</option>
                            <option value="dismantle">Dismantle</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Deskripsi Pekerjaan</label>
                        <textarea name="deskripsi" rows="4" required
                            style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;"></textarea>
                    </div>
                </div>

                <!-- Keterangan Others -->
                <div id="keterangan_others" style="display: none; flex-direction: column; margin-bottom: 16px;">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan"
                        style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                </div>

                <!-- Detail Perlengkapan -->
                <div style="margin-bottom: 16px;">
                    <h3>Detail Perlengkapan Yang Dibawa</h3>
                    <div id="perlengkapan-container">
                        <div class="perlengkapan-block" style="margin-bottom: 16px;">
                            <div style="display: flex; gap: 20px; margin-bottom: 8px;">
                                <div style="flex: 1;">
                                    <label>Nama Perlengkapan / Equipments Name</label>
                                    <input type="text" name="nama_perlengkapan[]" required
                                        style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                </div>
                            </div>
                            <div style="display: flex; gap: 20px;">
                                <div style="flex: 1;">
                                    <label>Jumlah / Amount</label>
                                    <input type="number" name="jumlah_perlengkapan[]" required
                                        style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                </div>
                                <div style="flex: 1;">
                                    <label>Keterangan</label>
                                    <input type="text" name="keterangan_perlengkapan[]" required
                                        style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addPerlengkapan()"
                        style="margin-top: 12px; background-color: #5a54ea; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer;">
                        + Tambah Perlengkapan
                    </button>
                    <small style="color: #666; display: block; margin-top: 8px;">*)diisi peralatan kerja yang akan dibawa masuk & keluar seperti : Laptop,Alat Ukur,Toolkit,..dll</small>
                </div>

                <!-- Detail Barang Masuk -->
                <div style="margin-bottom: 16px;">
                    <h3>Detail Barang Masuk</h3>
                    <div id="barang-masuk-container">
                        <div class="barang-masuk-block" style="margin-bottom: 16px;">
                            <div style="display: flex; gap: 20px; margin-bottom: 8px;">
                                <div style="flex: 1;">
                                    <label>Nama / Name</label>
                                    <input type="text" name="nama_barang_masuk[]" required
                                        style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                </div>
                            </div>
                            <div style="display: flex; gap: 20px;">
                                <div style="flex: 1;">
                                    <label>Berat / Weight (kg)</label>
                                    <input type="number" name="berat_barang_masuk[]" required
                                        style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                </div>
                                <div style="flex: 1;">
                                    <label>Jumlah / Amount</label>
                                    <input type="number" name="jumlah_barang_masuk[]" required
                                        style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                </div>
                                <div style="flex: 1;">
                                    <label>Keterangan</label>
                                    <input type="text" name="keterangan_barang_masuk[]" required
                                        style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addBarangMasuk()"
                        style="margin-top: 12px; background-color: #5a54ea; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer;">
                        + Tambah Barang Masuk
                    </button>
                    <small style="color: #666; display: block; margin-top: 8px;">*)diisi nama Barang yang akan dipasang/diinstal di Data Center.</small>
                </div>

                <!-- Detail Barang Keluar -->
                <div style="margin-bottom: 16px;">
                    <h3>Detail Barang Keluar</h3>
                    <div id="barang-keluar-container">
                        <div class="barang-keluar-block" style="margin-bottom: 16px;">
                            <div style="display: flex; gap: 20px; margin-bottom: 8px;">
                                <div style="flex: 1;">
                                    <label>Nama</label>
                                    <input type="text" name="nama_barang_keluar[]" required
                                        style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                </div>
                            </div>
                            <div style="display: flex; gap: 20px;">
                                <div style="flex: 1;">
                                    <label>Berat (kg)</label>
                                    <input type="number" name="berat_barang_keluar[]" required
                                        style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                </div>
                                <div style="flex: 1;">
                                    <label>Jumlah</label>
                                    <input type="number" name="jumlah_barang_keluar[]" required
                                        style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                </div>
                                <div style="flex: 1;">
                                    <label>Keterangan</label>
                                    <input type="text" name="keterangan_barang_keluar[]" required
                                        style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addBarangKeluar()"
                        style="margin-top: 12px; background-color: #5a54ea; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer;">
                        + Tambah Barang Keluar
                    </button>
                    <small style="color: #666; display: block; margin-top: 8px;">*)diisi nama Barang yang akan di‐dismantle/dicabut dari Data Center.</small>
                </div>

                <!-- Form Persetujuan -->
                <div style="margin-top: 30px; margin-bottom: 30px;">
                    <h3>Form Persetujuan</h3>
                    
                    <div style="display: flex; gap: 50px;">
                        <!-- Kolom Pemohon -->
                        <div style="flex: 1;">
                            <h4>Pemohon</h4>
                            <div style="margin-bottom: 20px;">
                                <label>Tanggal:</label>
                                <input type="date" name="tanggal_pemohon" required
                                    style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc; margin-top: 5px;">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label>Nama:</label>
                                <input type="text" name="nama_pemohon" required
                                    style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc; margin-top: 5px;">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label>Tanda Tangan Pemohon:</label>
                                <div style="border: 2px dashed #ccc; padding: 20px; text-align: center; margin-top: 5px;">
                                    <input type="file" id="ttd_pemohon" name="ttd_pemohon" accept="image/*" style="display: none;">
                                    <img id="preview_ttd_pemohon" src="#" alt="Preview Tanda Tangan" style="max-width: 200px; max-height: 100px; display: none;">
                                    <button type="button" onclick="document.getElementById('ttd_pemohon').click()"
                                        style="background-color: #5a54ea; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer;">
                                        Unggah Tanda Tangan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Menyetujui -->
                        <div style="flex: 1;">
                            <h4>Menyetujui</h4>
                            <div style="margin-bottom: 20px;">
                                <label>Tanggal:</label>
                                <input type="date" name="tanggal_menyetujui" required
                                    style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc; margin-top: 5px;">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label>Nama Pengawas:</label>
                                <input type="text" name="nama_menyetujui" required
                                    style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc; margin-top: 5px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div style="margin-top: 20px;">
                    <button type="button" onclick="window.location.href='{{ route('pendaftaran.ajukan-dcs') }}'"
                        style="background-color: #5a54ea; color: white; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer;">
                        Next
                    </button>
                </div>

            </div>
        </form>
    </div>

    <!-- Modal Ajukan DCS -->
    <div class="modal" id="modalAjukanDCS">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalAjukanDCS')">&times;</span>
            <h5>Ajukan Visit DCS</h5>
            <form action="{{ route('dokumen.store') }}" method="POST" id="formAjukanDCS" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Catatan</label>
                    <input type="text" name="catatan" class="form-control" id="catatan" value="">
                </div>
                <div class="mb-3">
                    <label>Pilih NDA Aktif</label>
                    <select name="verifikasi_nda_id" id="verifikasi_nda_id" class="form-control" required>
                        <option value="">-- Pilih NDA --</option>
                        @forelse($activeNdas as $nda)
                            <option value="{{ $nda->id }}">NDA berlaku sampai {{ $nda->masa_berlaku->format('d F Y') }}</option>
                        @empty
                            <option value="" disabled>-- Tidak ada NDA aktif --</option>
                        @endforelse
                    </select>
                    @if($activeNdas->isEmpty())
                        <small class="text-danger">Anda tidak memiliki NDA yang aktif. Silahkan ajukan verifikasi NDA terlebih dahulu.</small>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="submitDCAF()" class="btn btn-primary" {{ $activeNdas->isEmpty() ? 'disabled' : '' }}>Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addRekanan() {
            const rekananContainer = document.getElementById('rekanan-container');
            const newRekanan = document.createElement('div');
            newRekanan.classList.add('rekanan-block');
            newRekanan.style.marginTop = '20px';

            newRekanan.innerHTML = `
                                                                    <div style="position: relative; margin-bottom: 10px;">
                                                                        <!-- Tombol hapus -->
                                                                        <button type="button" onclick="this.closest('.rekanan-block').remove()"
                                                                            style="position: absolute; top: 0px; right: -60px; background-color: red; color: white; border: none; padding: 5px 10px; border-radius: 6px; z-index: 1;">
                                                                            hapus
                                                                        </button>

                                                                        <!-- Baris 1: Nama & Nama Perusahaan -->
                                                                        <div style="display: flex; gap: 20px; margin-bottom: 10px;">
                                                                            <input type="text" name="nama_rekanan[]" placeholder="Nama" required
                                                                                style="flex: 1; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                                                            <input type="text" name="perusahaan_rekanan[]" placeholder="Nama Perusahaan" required
                                                                                style="flex: 1; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                                                        </div>

                                                                        <!-- Baris 2: No KTP & No Telepon -->
                                                                        <div style="display: flex; gap: 20px;">
                                                                            <input type="text" name="ktp_rekanan[]" placeholder="No KTP" required
                                                                                style="flex: 1; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                                                            <input type="text" name="telp_rekanan[]" placeholder="No Telepon" required
                                                                                style="flex: 1; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                                                        </div>
                                                                    </div>
                                                                `;

            rekananContainer.appendChild(newRekanan);
        }
    </script>

<script>
    // Fungsi untuk menambah perlengkapan
    function addPerlengkapan() {
        const container = document.getElementById('perlengkapan-container');
        const newPerlengkapan = document.createElement('div');
        newPerlengkapan.classList.add('perlengkapan-block');
        newPerlengkapan.style.marginTop = '20px';

        newPerlengkapan.innerHTML = `
            <div style="position: relative; margin-bottom: 10px;">
                <button type="button" onclick="this.closest('.perlengkapan-block').remove()"
                    style="position: absolute; top: 0px; right: -60px; background-color: red; color: white; border: none; padding: 5px 10px; border-radius: 6px; z-index: 1;">
                    hapus
                </button>

                <div style="display: flex; gap: 20px; margin-bottom: 8px;">
                    <div style="flex: 1;">
                        <label>Nama Perlengkapan</label>
                        <input type="text" name="nama_perlengkapan[]" required
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                </div>
                <div style="display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah_perlengkapan[]" required
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1;">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan_perlengkapan[]" required
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                </div>
            </div>
        `;

        // FIXED: sebelumnya salah pakai variabel yang belum didefinisikan
        container.appendChild(newPerlengkapan);
    }
</script>

<script>
    // Fungsi untuk menambah barang masuk
    function addBarangMasuk() {
        const container = document.getElementById('barang-masuk-container');
        const newBarangMasuk = document.createElement('div');
        newBarangMasuk.classList.add('barang-masuk-block');
        newBarangMasuk.style.marginTop = '20px';

        newBarangMasuk.innerHTML = `
            <div style="position: relative; margin-bottom: 10px;">
                <button type="button" onclick="this.closest('.barang-masuk-block').remove()"
                    style="position: absolute; top: 0px; right: -60px; background-color: red; color: white; border: none; padding: 5px 10px; border-radius: 6px; z-index: 1;">
                    hapus
                </button>

                <div style="display: flex; gap: 20px; margin-bottom: 8px;">
                    <div style="flex: 1;">
                        <label>Nama</label>
                        <input type="text" name="nama_barang_masuk[]" required
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                </div>
                <div style="display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <label>Berat (kg)</label>
                        <input type="number" name="berat_barang_masuk[]" required
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1;">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah_barang_masuk[]" required
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1;">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan_barang_masuk[]" required
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                </div>
            </div>
        `;

        container.appendChild(newBarangMasuk); // FIXED
    }

    // Fungsi untuk menambah barang keluar
    function addBarangKeluar() {
        const container = document.getElementById('barang-keluar-container');
        const newBarangKeluar = document.createElement('div');
        newBarangKeluar.classList.add('barang-keluar-block');
        newBarangKeluar.style.marginTop = '20px';

        newBarangKeluar.innerHTML = `
            <div style="position: relative; margin-bottom: 10px;">
                <button type="button" onclick="this.closest('.barang-keluar-block').remove()"
                    style="position: absolute; top: 0px; right: -60px; background-color: red; color: white; border: none; padding: 5px 10px; border-radius: 6px; z-index: 1;">
                    hapus
                </button>

                <div style="display: flex; gap: 20px; margin-bottom: 8px;">
                    <div style="flex: 1;">
                        <label>Nama</label>
                        <input type="text" name="nama_barang_keluar[]" required
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                </div>
                <div style="display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <label>Berat (kg)</label>
                        <input type="number" name="berat_barang_keluar[]" required
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1;">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah_barang_keluar[]" required
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                    <div style="flex: 1;">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan_barang_keluar[]" required
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>
                </div>
            </div>
        `;

        container.appendChild(newBarangKeluar); 
    }
</script>

    <script>
        function showModalAjukanDCS() {
            document.getElementById('modalAjukanDCS').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function submitDCAF() {
            const form = document.getElementById('addPendaftaranForm');
            const formData = new FormData(form);

            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Mohon tunggu, data sedang disimpan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal menyimpan data');
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berhasil disimpan.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = '/dashboard'; // atau halaman yang diinginkan
                });
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan data.'
                });
            });
        }
    </script>

    <script>
    // Preview tanda tangan pemohon
    document.getElementById('ttd_pemohon').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview_ttd_pemohon').src = e.target.result;
                document.getElementById('preview_ttd_pemohon').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Preview tanda tangan menyetujui
    document.getElementById('ttd_menyetujui').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview_ttd_menyetujui').src = e.target.result;
                document.getElementById('preview_ttd_menyetujui').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dapatkan elemen yang diperlukan
            const jenisPekerjaanSelect = document.getElementById('jenis_pekerjaan');
            const deskripsiTextarea = document.querySelector('textarea[name="deskripsi"]');
            
            // Function untuk mengatur deskripsi berdasarkan jenis pekerjaan
            function setDeskripsiBasedOnJenisPekerjaan() {
                const selectedValue = jenisPekerjaanSelect.value;
                const selectedText = jenisPekerjaanSelect.options[jenisPekerjaanSelect.selectedIndex]?.text || '';
                
                if (selectedValue === 'others') {
                    // Jika Others dipilih, biarkan pengguna mengisi deskripsi
                    deskripsiTextarea.value = '';
                    deskripsiTextarea.placeholder = 'Deskripsikan pekerjaan lain secara detail';
                    deskripsiTextarea.removeAttribute('readonly');
                    deskripsiTextarea.setAttribute('required', 'required');
                } else if (selectedValue) {
                    // Jika pilihan lain, isi otomatis dengan teks pilihan
                    deskripsiTextarea.value = selectedText;
                    deskripsiTextarea.setAttribute('readonly', 'readonly');
                } else {
                    // Jika belum ada pilihan
                    deskripsiTextarea.value = '';
                    deskripsiTextarea.removeAttribute('readonly');
                }
            }
            
            // Jalankan fungsi saat halaman dimuat
            setDeskripsiBasedOnJenisPekerjaan();
            
            // Tambahkan event listener untuk perubahan jenis pekerjaan
            jenisPekerjaanSelect.addEventListener('change', setDeskripsiBasedOnJenisPekerjaan);
        });
    </script>

@endsection