@extends('layouts.app')

@section('title', 'Pendaftaran DCAF')
@section('page_title', 'Pendaftaran DCAF')

@section('content')
    <form method="POST" action="{{ route('dcaf.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="main">
            <div class="section" style="margin-top: 20px;">
                <header class="header-profile">
                    <h2>{{ __('Informasi NDA yang Berlaku') }}</h2>
                    <p class="subtext">
                        {{ __('Jika Anda belum memiliki NDA yang aktif, pengajuan DCAF tidak dapat dilakukan. Silakan buat terlebih dahulu melalui menu NDA.') }}
                    </p>
                </header>

                <div class="form-group">
                    <select name="nda_id" id="nda_id" class="form-control select2" required>
                        <option value="">Pilih NDA</option>
                        @foreach ($activeNdas as $nda)
                            <option value="{{ $nda->id }}">
                                NDA {{ $nda->user->name }} - Berlaku s/d
                                {{ \Carbon\Carbon::parse($nda->masaberlaku)->translatedFormat('d M Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="section" style="margin-top: 20px;">
                <header class="header-profile">
                    <h2>{{ __('Informasi Pemohon') }}</h2>
                    <p class="subtext">{{ __('Mohon lengkapi data sesuai identitas Anda.') }}</p>
                </header>

                <div class="form-group" style="margin-top: 20px;">
                    <div class="split">
                        <div>
                            <label for="name">Nama Pemohon</label>
                            <input id="name" name="name" type="text" class="form-control"
                                value="{{ old('name', auth()->user()->name) }}">
                        </div>
                        <div>
                            <label for="mobile_number">No HP Pemohon</label>
                            <input id="mobile_number" name="mobile_number" type="text" class="form-control"
                                value="{{ old('mobile_number', auth()->user()->mobile_number) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group" style="width: 49.5%;">
                    <label for="signature">Tanda Tangan</label>
                    <div class="mb-3">
                        <label for="upload-signature" class="form-label"></label>
                        <input type="file" id="upload-signature" accept="image/*" class="form-control">
                    </div>
                    <canvas id="signature-pad"
                        style="border: 1px solid #000; width: 100%; height: 150px; cursor: crosshair;"></canvas>
                    <button type="button" id="clear-signature" class="btn btn-delete mb-3"
                        style="padding: 10px; font-size: 14px; margin-bottom: 20px; margin-top: 10px;">Reset</button>
                    <input type="hidden" name="signature" id="signature" value="{{ $dcaf->signature ?? '' }}">
                </div>

                <div class="form-group">
                    <label>Detail Rekanan</label>
                    <div class="section-container">
                        <div id="rekanan-container">
                            <div class="split">
                                <input type="text" class="form-control" name="nama_rekanan[]" placeholder="Nama" required>
                                <input type="text" class="form-control" name="perusahaan_rekanan[]"
                                    placeholder="Nama Perusahaan" required>
                            </div>
                            <div class="split">
                                <input type="text" class="form-control" style="margin-bottom: 0;" name="ktp_rekanan[]"
                                    placeholder="No KTP" required>
                                <input type="text" class="form-control" style="margin-bottom: 0;" name="telp_rekanan[]"
                                    placeholder="No Telepon" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-primary" style="margin-top: 20px; margin-bottom: 20px;"
                        onclick="addRekanan()">+ Tambah Rekanan</button>
                </div>

                <div class="form-group">
                    <label>Divisi</label>
                    <input id="bagian" name="bagian" type="text" class="form-control"
                        value="{{ old('bagian', auth()->user()->bagian) }}">
                </div>

                <div class="form-group">
                    <div class="split">
                        <div>
                            <label>Tanggal Mulai</label>
                            <input id="tanggal_mulai" type="date" class="form-control" name="tanggal_mulai" required>
                        </div>
                        <div>
                            <label>Tanggal Selesai</label>
                            <input id="tanggal_selesai" type="date" class="form-control" name="tanggal_selesai" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="split">
                        <div>
                            <label>Waktu Mulai</label>
                            <input id="waktu_mulai" type="time" class="form-control" name="waktu_mulai" required>
                        </div>
                        <div>
                            <label>Waktu Selesai</label>
                            <input id="waktu_selesai" type="time" class="form-control" name="waktu_selesai" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="split">
                        <div>
                            <label>Lokasi Pengerjaan</label>
                            <input id="lokasi" type="text" class="form-control" name="lokasi" required>
                        </div>
                        <div>
                            <label>Nomor Rack</label>
                            <select name="no_rack" class="form-control" required>
                                <option value="" disabled selected>Pilih No Rack</option>
                                <option value="-">-</option>
                                @foreach($racks as $rack)
                                    <option value="{{ $rack->no_rack }}">
                                        Rack {{ $rack->no_rack }}, {{ $rack->site->nama_site }} {{ $rack->region->nama_region }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="split">
                        <div>
                            <label for="jenis_pekerjaan">Jenis Pekerjaan</label>
                            <select name="jenis_pekerjaan" id="jenis_pekerjaan" class="form-control" required
                                onchange="toggleDeskripsiPekerjaan()">
                                <option value="">Pilih Jenis Pekerjaan</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="checking">Checking</option>
                                <option value="installation">Installation</option>
                                <option value="dismantle">Dismantle</option>
                                <option value="others">Others</option>
                            </select>
                        </div>

                        <div id="deskripsi_pekerjaan_container">
                            <label for="deskripsi_pekerjaan">Deskripsi Pekerjaan</label>
                            <input id="deskripsi_pekerjaan" type="text" class="form-control" name="deskripsi_pekerjaan">
                        </div>
                    </div>
                </div>
            </div>

            <div class="section" style="margin-bottom: 0px;">
                <header class="header-profile">
                    <h2>{{ __('Informasi Pendataan Barang') }}</h2>
                    <p class="subtext">
                        {{ __('Lengkapi data berikut untuk keperluan pencatatan barang masuk, keluar, dan perlengkapan yang dibawa.') }}
                    </p>
                </header>

                <div class="form-group" style="margin-top: 20px;">
                    <label>Detail Perlengkapan Yang Dibawa</label>
                    <small style="color: #666; display: block; margin-bottom: 10px;">*) diisi peralatan kerja yang akan
                        dibawa masuk & keluar seperti: Laptop, Alat Ukur, Toolkit, dll</small>
                    <div class="section-container">
                        <div id="perlengkapan-container">
                            <div class="split">
                                <input type="text" class="form-control" name="nama_perlengkapan[]"
                                    placeholder="Nama Perlengkapan" required>
                                <input type="number" class="form-control" name="jumlah_perlengkapan[]" placeholder="Jumlah"
                                    required>
                            </div>
                            <input type="text" class="form-control" style="margin-bottom: 0;"
                                name="keterangan_perlengkapan[]" placeholder="Keterangan" required>
                        </div>
                    </div>
                    <button type="button" class="btn-primary" style="margin-top: 20px; margin-bottom: 20px;"
                        onclick="addPerlengkapan()">+ Tambah Perlengkapan</button>
                </div>

                <div class="form-group">
                    <label>Detail Barang Masuk</label>
                    <small style="color: #666; display: block; margin-bottom: 10px;">*) diisi nama Barang yang akan
                        dipasang/diinstal di Data Center.</small>
                    <div class="section-container">
                        <div id="barang-masuk-container">
                            <div class="split">
                                <input type="text" class="form-control" name="nama_barang_masuk[]" placeholder="Nama"
                                    required>
                                <input type="number" class="form-control" name="jumlah_barang_masuk[]" placeholder="Jumlah"
                                    required>
                            </div>
                            <div class="split">
                                <input type="number" class="form-control" style="margin-bottom: 0;"
                                    name="berat_barang_masuk[]" placeholder="Berat (kg)" required>
                                <input type="text" class="form-control" style="margin-bottom: 0;"
                                    name="keterangan_barang_masuk[]" placeholder="Keterangan" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-primary" style="margin-top: 20px; margin-bottom: 20px;"
                        onclick="addBarangMasuk()">+ Tambah Barang Masuk</button>
                </div>

                <div class="form-group">
                    <label>Detail Barang Keluar</label>
                    <small style="color: #666; display: block; margin-bottom: 10px;">*) diisi nama Barang yang akan
                        dipasang/diinstal di Data Center.</small>
                    <div class="section-container">
                        <div id="barang-keluar-container">
                            <div class="split">
                                <input type="text" class="form-control" name="nama_barang_keluar[]" placeholder="Nama"
                                    required>
                                <input type="number" class="form-control" name="jumlah_barang_keluar[]" placeholder="Jumlah"
                                    required>
                            </div>
                            <div class="split">
                                <input type="number" class="form-control" style="margin-bottom: 0;"
                                    name="berat_barang_keluar[]" placeholder="Berat (kg)" required>
                                <input type="text" class="form-control" style="margin-bottom: 0;"
                                    name="keterangan_barang_keluar[]" placeholder="Keterangan" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-primary" style="margin-top: 20px; margin-bottom: 20px;"
                        onclick="addBarangKeluar()">+ Tambah Barang Keluar</button>
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn-primary" style="padding: 10px 20px;">Simpan</button>
            </div>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <script>
        function toggleDeskripsiPekerjaan() {
            const jenisPekerjaan = document.getElementById('jenis_pekerjaan').value;
            const deskripsiContainer = document.getElementById('deskripsi_pekerjaan_container');

            if (jenisPekerjaan === 'others') {
                deskripsiContainer.style.display = 'block';
                document.getElementById('deskripsi_pekerjaan').setAttribute('required', 'required');
            } else {
                deskripsiContainer.style.display = 'none';
                document.getElementById('deskripsi_pekerjaan').removeAttribute('required');
                document.getElementById('deskripsi_pekerjaan').value = '';
            }
        }

        function addRekanan() {
            const rekananContainer = document.getElementById('rekanan-container');
            const newRekanan = document.createElement('div');
            newRekanan.classList.add('rekanan-tambahan');
            newRekanan.innerHTML = `
                                            <div style="position: relative; margin-top: 20px;">
                                                <div class="split">
                                                    <input type="text" class="form-control" name="nama_rekanan[]" placeholder="Nama" required>
                                                    <input type="text" class="form-control" name="perusahaan_rekanan[]" placeholder="Nama Perusahaan" required>
                                                </div>
                                                <div class="split">
                                                    <input type="text" class="form-control" name="ktp_rekanan[]" placeholder="No KTP" required>
                                                    <input type="text" class="form-control" name="telp_rekanan[]" placeholder="No Telepon" required>
                                                </div>
                                                <button type="button" onclick="this.closest('.rekanan-tambahan').remove()" class="btn-delete">Hapus</button>
                                            </div>
                                        `;
            rekananContainer.appendChild(newRekanan);
        }

        function addPerlengkapan() {
            const container = document.getElementById('perlengkapan-container');
            const newPerlengkapan = document.createElement('div');
            newPerlengkapan.classList.add('perlengkapan-tambahan');
            newPerlengkapan.innerHTML = `
                                            <div style="position: relative; margin-top: 20px;">
                                                <div class="split">
                                                    <input type="text" class="form-control" name="nama_perlengkapan[]" placeholder="Nama Perlengkapan" required>
                                                    <input type="text" class="form-control" name="jumlah_perlengkapan[]" placeholder="Jumlah" required>
                                                </div>
                                                <input type="text" class="form-control" name="keterangan_perlengkapan[]" placeholder="Keterangan" required>
                                                <button type="button" onclick="this.closest('.perlengkapan-tambahan').remove()" class="btn-delete">Hapus</button>
                                            </div>
                                        `;
            container.appendChild(newPerlengkapan);
        }

        function addBarangMasuk() {
            const container = document.getElementById('barang-masuk-container');
            const newBarangMasuk = document.createElement('div');
            newBarangMasuk.classList.add('barang-masuk-tambahan');
            newBarangMasuk.innerHTML = `
                                            <div style="position: relative; margin-top: 20px;">
                                                <div class="split">
                                                    <input type="text" class="form-control" name="nama_barang_masuk[]" placeholder="Nama" required>
                                                    <input type="text" class="form-control" name="jumlah_barang_masuk[]" placeholder="Jumlah" required>
                                                </div>
                                                <div class="split">
                                                    <input type="text" class="form-control" name="berat_barang_masuk[]" placeholder="Berat (kg)" required>
                                                    <input type="text" class="form-control" name="keterangan_barang_masuk[]" placeholder="Keterangan" required>
                                                </div>
                                                <button type="button" onclick="this.closest('.barang-masuk-tambahan').remove()" class="btn-delete">Hapus</button>
                                            </div>
                                        `;
            container.appendChild(newBarangMasuk);
        }

        function addBarangKeluar() {
            const container = document.getElementById('barang-keluar-container');
            const newBarangKeluar = document.createElement('div');
            newBarangKeluar.classList.add('barang-keluar-tambahan');
            newBarangKeluar.innerHTML = `
                                            <div style="position: relative; margin-top: 20px;">
                                                <div class="split">
                                                    <input type="text" class="form-control" name="nama_barang_keluar[]" placeholder="Nama" required>
                                                    <input type="text" class="form-control" name="jumlah_barang_keluar[]" placeholder="Jumlah" required>
                                                </div>
                                                <div class="split">
                                                    <input type="text" class="form-control" name="berat_barang_keluar[]" placeholder="Berat (kg)" required>
                                                    <input type="text" class="form-control" name="keterangan_barang_keluar[]" placeholder="Keterangan" required>
                                                </div>
                                                <button type="button" onclick="this.closest('.barang-keluar-tambahan').remove()" class="btn-delete">Hapus</button>
                                            </div>
                                        `;
            container.appendChild(newBarangKeluar);
        }

        const canvas = document.getElementById('signature-pad');
        const context = canvas.getContext('2d');
        const inputHidden = document.getElementById('signature');
        const uploadInput = document.getElementById('upload-signature');
        const clearButton = document.getElementById('clear-signature');
        let isImageUploaded = false;
        context.strokeStyle = '#000';
        context.lineWidth = 2;
        context.lineCap = 'round';

        if (inputHidden.value) {
            const img = new Image();
            img.onload = function () {
                context.drawImage(img, 0, 0, canvas.width, canvas.height);
                isImageUploaded = true;
                canvas.style.cursor = 'default';
            };
            img.src = inputHidden.value;
        }

        clearButton.addEventListener('click', () => {
            context.clearRect(0, 0, canvas.width, canvas.height);
            inputHidden.value = '';
            isImageUploaded = false;
            canvas.style.cursor = 'crosshair';
        });

        uploadInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (event) {
                const img = new Image();
                img.onload = function () {
                    canvas.width = img.width;
                    canvas.height = img.height;
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    context.drawImage(img, 0, 0);
                    inputHidden.value = canvas.toDataURL('image/png');
                    isImageUploaded = true;
                    canvas.style.cursor = 'default';
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });

        function updateSignatureInput() {
            if (!isImageUploaded) {
                inputHidden.value = canvas.toDataURL('image/png');
            }
        }

        let isDrawing = false;
        function startDrawing(e) {
            if (isImageUploaded) return;
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            context.beginPath();
            context.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        }

        function draw(e) {
            if (!isDrawing || isImageUploaded) return;
            const rect = canvas.getBoundingClientRect();
            context.lineTo(e.clientX - rect.left, e.clientY - rect.top);
            context.stroke();
        }

        function stopDrawing() {
            if (isImageUploaded) return;
            isDrawing = false;
            updateSignatureInput();
        }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);

        function resizeCanvas() {
            const dataURL = inputHidden.value;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            context.scale(ratio, ratio);
            context.strokeStyle = '#000';
            context.lineWidth = 2;
            context.lineCap = 'round';
            context.clearRect(0, 0, canvas.width, canvas.height);
            if (dataURL) {
                const img = new Image();
                img.onload = function () {
                    context.drawImage(img, 0, 0, canvas.width, canvas.height);
                };
                img.src = dataURL;
            }
        }

        window.addEventListener('resize', () => {
            resizeCanvas();
        });

        resizeCanvas();
    </script>
@endsection