@extends('layouts.app')

@section('title', 'NDA')
@section('page_title', 'NDA')

@section('content')
    <div class="main">
        <div class="container">
            <div id="modalTambahNdaEksternal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalTambahNdaEksternal')">&times;</span>
                    <h5 id="judulModal">Pengajuan NDA</h5>

                    <form action="{{ route('nda.store') }}" method="POST">
                        @csrf
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <div style="width: 48%;">
                                <label>No. KTP</label>
                                <input type="text" name="no_ktp" class="form-control" required>

                                <label>Nama</label>
                                <input type="text" name="name" class="form-control" value="{{ $nda->users->name ?? '' }}">

                                <label>Alamat</label>
                                <input type="text" name="alamat" class="form-control" required>                    
                            </div>

                            <div style="width: 48%;">
                                <div class="form-group">
                                    <label for="signature-eksternal">Tanda Tangan</label>
                                    <canvas id="signature-pad-eksternal"
                                        style="border: 1px solid #000; width: 100%; height: 150px; cursor: crosshair;"></canvas>
                                    <button type="button" id="clear-signature-eksternal" class="btn btn-delete mb-3">Reset</button>
                                    <input type="hidden" name="signature" id="signature-eksternal">
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 20px; text-align: right;">
                            <button type="submit" class="btn btn-primary">Ajukan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modalTambahNdaInternal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalTambahNdaInternal')">&times;</span>
                    <h5 id="judulModal">Pengajuan NDA</h5>

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
                                    <label for="signature-internal">Tanda Tangan</label>
                                    <canvas id="signature-pad-internal"
                                        style="border: 1px solid #000; width: 100%; height: 150px; cursor: crosshair;"></canvas>
                                    <button type="button" id="clear-signature-internal" class="btn btn-delete mb-3">Reset</button>
                                    <input type="hidden" name="signature" id="signature-internal">
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 20px; text-align: right;">
                            <button type="submit" class="btn btn-primary">Ajukan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Riwayat NDA -->
            <div class="tables-container dua">
                <div class="table-column">
                    <div class="title" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="button-wrapper">
                            @if(auth()->user()->role == 3)
                                <button class="btn btn-primary mb-3" onclick="openModal('modalTambahNdaInternal')">Pengajuan NDA</button>
                            @elseif(auth()->user()->role == 4)
                                <button class="btn btn-primary mb-3" onclick="openModal('modalTambahNdaEksternal')">Pengajuan NDA</button>
                            @endif
                        </div>
                        <h3>Buat Pengajuan NDA</h3>
                    </div>
                    <div class="table-responsive">
                        <table id="buatTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($ndas->isEmpty())
                                    <tr>
                                        <td colspan="7" class="no-data">Tidak ada data NDA</td>
                                    </tr>
                                @else
                                    @foreach($ndas as $index => $nda)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($nda->tanggal)->format('d-m-Y') }}</td>
                                            <td>
                                                <span style="display: inline-flex; align-items: center;">
                                                    <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                                        background-color: {{ $nda->status == 'menunggu persetujuan' ? '#ffc107' :
                                                        ($nda->status == 'diterima' ? '#28a745' : '#dc3545') }};">
                                                    </span>
                                                    {{ ucfirst($nda->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table-column">
                    <div class="title" style="display: flex; justify-content: space-between; align-items: center;"></br>
                        <h3>Riwayat Pengajuan NDA</h3>
                    </div></br>
                    <div class="table-responsive">
                        <table id="ajukanTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Disetujui</th>
                                    <th>Masa Berlaku</th>
                                    <th>Catatan</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeNdas->where('status', 'diterima') as $index => $nda)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $nda->updated_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $nda->masa_berlaku ? $nda->masa_berlaku->format('d/m/Y H:i') : '-' }}</td>
                                        <td>{{ $nda->catatan ?? '-' }}</td>
                                        <td>
                                            @if($nda->file_path)
                                                <a href="{{ asset('pdf/' . $nda->file_path) }}" target="_blank"
                                                    class="btn btn-sm btn-info">Lihat File</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada riwayat NDA yang disetujui</td>
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

        // Setup untuk tanda tangan eksternal
        var canvasEksternal = document.getElementById('signature-pad-eksternal');
        var signatureInputEksternal = document.getElementById('signature-eksternal');
        var clearButtonEksternal = document.getElementById('clear-signature-eksternal');
        var ctxEksternal = canvasEksternal.getContext('2d');
        var drawingEksternal = false;

        // Setup untuk tanda tangan internal
        var canvasInternal = document.getElementById('signature-pad-internal');
        var signatureInputInternal = document.getElementById('signature-internal');
        var clearButtonInternal = document.getElementById('clear-signature-internal');
        var ctxInternal = canvasInternal.getContext('2d');
        var drawingInternal = false;

        // Fungsi untuk setup canvas
        function setupCanvas(canvas, ctx, drawing, signatureInput, clearButton) {
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
                signatureInput.value = canvas.toDataURL('image/png');
            });

            canvas.addEventListener('mouseleave', function () {
                drawing = false;
            });

            clearButton.addEventListener('click', function () {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                signatureInput.value = '';
                drawing = false;
            });
        }

        // Setup kedua canvas
        setupCanvas(canvasEksternal, ctxEksternal, drawingEksternal, signatureInputEksternal, clearButtonEksternal);
        setupCanvas(canvasInternal, ctxInternal, drawingInternal, signatureInputInternal, clearButtonInternal);

        // Validasi sebelum submit untuk kedua form
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function (e) {
                var signatureInput = this.querySelector('input[name="signature"]');
                if (!signatureInput.value) {
                    e.preventDefault();
                    alert('Tanda tangan wajib diisi!');
                }
            });
        });

        // Tambahkan event listener untuk memastikan tanda tangan tersimpan
        canvasEksternal.addEventListener('mouseup', function() {
            signatureInputEksternal.value = canvasEksternal.toDataURL('image/png');
        });

        canvasInternal.addEventListener('mouseup', function() {
            signatureInputInternal.value = canvasInternal.toDataURL('image/png');
        });

        // Tambahkan event listener untuk touch events
        function setupTouchEvents(canvas, ctx, drawing, signatureInput) {
            canvas.addEventListener('touchstart', function(e) {
                e.preventDefault();
                drawing = true;
                var touch = e.touches[0];
                var rect = canvas.getBoundingClientRect();
                var x = touch.clientX - rect.left;
                var y = touch.clientY - rect.top;
                ctx.beginPath();
                ctx.moveTo(x, y);
            });

            canvas.addEventListener('touchmove', function(e) {
                e.preventDefault();
                if (drawing) {
                    var touch = e.touches[0];
                    var rect = canvas.getBoundingClientRect();
                    var x = touch.clientX - rect.left;
                    var y = touch.clientY - rect.top;
                    ctx.lineTo(x, y);
                    ctx.stroke();
                }
            });

            canvas.addEventListener('touchend', function(e) {
                e.preventDefault();
                drawing = false;
                signatureInput.value = canvas.toDataURL('image/png');
            });
        }

        // Setup touch events untuk kedua canvas
        setupTouchEvents(canvasEksternal, ctxEksternal, drawingEksternal, signatureInputEksternal);
        setupTouchEvents(canvasInternal, ctxInternal, drawingInternal, signatureInputInternal);

        // Debug untuk memeriksa nilai signature
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                var signatureInput = this.querySelector('input[name="signature"]');
                console.log('Signature value:', signatureInput.value);
            });
        });
    </script>

@endsection