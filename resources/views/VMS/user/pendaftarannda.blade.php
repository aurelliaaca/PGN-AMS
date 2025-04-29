@extends('layouts.app')
@section('title', 'Portal VMS')
@section('page_title', 'Portal VMS')
@section('content')
    <div class="main">
        <div class="container">
            <!-- Modal Upload NDA -->
            <div class="modal" id="modalAjukanNDA">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalAjukanNDA')">&times;</span>
                    <h5>Ajukan Verifikasi NDA</h5>
                    <form action="{{ route('verifikasi.nda.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>File NDA (PDF atau DOCX)</label>
                            <input type="file" name="file_path" id="file_path" class="form-control" accept=".pdf,.doc,.docx"
                                required>
                            <small class="text-muted">Maksimum ukuran file: 10MB</small>
                        </div>
                        <div class="mb-3">
                            <label>Catatan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modalTambahNdaEksternal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalTambahNdaEksternal')">&times;</span>
                    <h5 id="judulModal">Tambah NDA</h5>

                    <form action="{{ route('nda.store') }}" method="POST">
                        @csrf
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <div style="width: 48%;">
                                <label>Nama</label>
                                <input type="text" name="nda_name" class="form-control" required>

                                <label>No. KTP</label>
                                <input type="text" name="no_ktp" class="form-control" required>

                                <label>Alamat</label>
                                <input type="text" name="alamat" class="form-control" required>

                                <div id="perusahaanField">
                                    <label>Perusahaan</label>
                                    <input type="text" name="perusahaan" class="form-control">
                                </div>
                            </div>

                            <div style="width: 48%;">
                                <div id="regionField">
                                    <label>Kode Region</label>
                                    <select id="regionSelectTambah" name="kode_region" class="form-control">
                                        <option value="">Pilih Region</option>
                                        @foreach($regions as $region)
                                            <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="bagianField">
                                    <label>Bagian</label>
                                    <input type="text" name="bagian" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="signature">Tanda Tangan</label>
                                    <canvas id="signature-pad"
                                        style="border: 1px solid #000; width: 100%; height: 150px; cursor: crosshair;"></canvas>
                                    <button type="button" id="clear-signature" class="btn btn-delete mb-3">Reset</button>
                                    <input type="hidden" name="signature" id="signature">
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 20px; text-align: right;">
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modalTambahNdaInternal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalTambahNdaInternal')">&times;</span>
                    <h5 id="judulModal">Tambah NDA</h5>

                    <form action="{{ route('nda.store') }}" method="POST">
                        @csrf
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <div style="width: 48%;">
                                <label>Nama</label>
                                <input type="text" name="nda_name" class="form-control" required>

                                <label>No. KTP</label>
                                <input type="text" name="no_ktp" class="form-control" required>

                                <label>Alamat</label>
                                <input type="text" name="alamat" class="form-control" required>
                            </div>

                            <div style="width: 48%;">
                                <div class="form-group">
                                    <label for="signature">Tanda Tangan</label>
                                    <canvas id="signature-pad"
                                        style="border: 1px solid #000; width: 100%; height: 150px; cursor: crosshair;"></canvas>
                                    <button type="button" id="clear-signature" class="btn btn-delete mb-3">Reset</button>
                                    <input type="hidden" name="signature" id="signature">
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 20px; text-align: right;">
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Riwayat NDA -->
            <div class="tables-container dua">
                <div class="table-column">
                    <div class="title" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="button-wrapper">
                            <button class="btn btn-primary mb-3" onclick="openModal('modalTambahNdaInternal')">Tambah
                                NDA Internal</button>
                            <button class="btn btn-primary mb-3" onclick="openModal('modalTambahNdaEksternal')">Tambah
                                NDA Eksternal</button>
                        </div>
                        <h3>Buat NDA</h3>
                    </div>
                    <div class="table-responsive">
                        <table id="buatTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>No. KTP</th>
                                    <th>Alamat</th>
                                    <th>Tanggal Sekarang</th>
                                    <th>Berlaku Sampai Dengan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($NDA->isEmpty())
                                    <tr>
                                        <td colspan="7" class="no-data">Tidak ada data NDA.</td>
                                    </tr>
                                @else
                                    @foreach($NDA as $index => $nda)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $nda->name }}</td>
                                            <td>{{ $nda->no_ktp }}</td>
                                            <td>{{ $nda->alamat }}</td>
                                            <td>{{ \Carbon\Carbon::parse($nda->tanggal)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($nda->tanggal_berlaku)->format('d-m-Y') }}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('nda.download', $nda->id) }}" class="view-btn">Lihat</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table-column">
                    <div class="title" style="display: flex; justify-content: space-between; align-items: center;">
                        <button class="btn btn-primary mb-3" onclick="openModal('modalAjukanNDA')">Ajukan Verifikasi
                            NDA</button>
                        <h3>Riwayat Pengajuan NDA</h3>
                    </div>
                    <div class="table-responsive">
                        <table id="ajukanTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Upload</th>
                                    <th>Status</th>
                                    <th>Masa Berlaku</th>
                                    <th>Catatan</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($VerNdas as $index => $nda)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $nda->created_at->format('d/m/Y H:i') }}</td>
                                                            <td>
                                                                <span style="display: inline-flex; align-items: center;">
                                                                    <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            background-color: 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                {{ $nda->status == 'pending' ? '#ffc107' :
                                    ($nda->status == 'diterima' ? '#28a745' : '#dc3545') }};">
                                                                    </span>
                                                                    {{ ucfirst($nda->status) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $nda->masa_berlaku ? $nda->masa_berlaku->format('d/m/Y H:i') : '-' }}</td>
                                                            <td>{{ $nda->catatan ?? '-' }}</td>
                                                            <td>
                                                                <a href="{{ asset('storage/' . $nda->file_path) }}" target="_blank"
                                                                    class="btn btn-sm btn-info">Lihat File</a>
                                                            </td>
                                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada riwayat NDA</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = "block";
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        // Close modal when clicking outside of it
        window.onclick = function (event) {
            if (event.target.className === 'modal') {
                event.target.style.display = "none";
            }
        }

        var canvas = document.getElementById('signature-pad');
        var signatureInput = document.getElementById('signature');
        var clearButton = document.getElementById('clear-signature');
        var ctx = canvas.getContext('2d');
        var drawing = false;

        canvas.addEventListener('mousedown', function (e) {
            drawing = true;
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
        });

        canvas.addEventListener('mousemove', function (e) {
            if (drawing) {
                ctx.lineTo(e.offsetX, e.offsetY);
                ctx.stroke();
            }
        });

        canvas.addEventListener('mouseup', function () {
            drawing = false;
            updateSignatureInput();
        });

        clearButton.addEventListener('click', function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            updateSignatureInput();
        });

        function updateSignatureInput() {
            signatureInput.value = canvas.toDataURL('image/png');
        }
    </script>
@endsection