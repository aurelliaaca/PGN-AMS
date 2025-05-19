@extends('layouts.app')

@section('title', 'NDA')
@section('page_title', 'NDA')

@section('content')
    <div class="main">
        <div class="container">
            <div id="modalTambahNdaEksternal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalTambahNdaEksternal')">×</span>
                    <h5 id="judulModal">Pengajuan NDA</h5>

                    <form action="{{ isset($nda) ? route('nda.update', $nda->id) : route('nda.store') }}" method="POST">
                        @csrf
                        @if(isset($nda))
                            @method('PUT')
                        @endif
                        @php
                            $user = Auth::user();
                        @endphp
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <div style="width: 48%;">
                                <label>No. KTP</label>
                                <input type="text" class="form-control" value="{{ $user->noktp ?? '-' }}" disabled>

                                <label>Region</label>
                                <input type="text" class="form-control" value="{{ $user->region ?? '-' }}" disabled>

                                <label>Alamat</label>
                                <input type="text" name="alamat" class="form-control" required
                                    value="{{ old('alamat', $user->alamat ?? '') }}">

                                <label>Perusahaan</label>
                                <input type="text" name="perusahaan" class="form-control" required
                                    value="{{ old('perusahaan', $user->perusahaan ?? '') }}">

                                <label>Bagian</label>
                                <input type="text" name="bagian" class="form-control" required
                                    value="{{ old('bagian', $user->bagian ?? '') }}">
                            </div>

                            <div style="width: 48%;">
                                <label>Nama</label>
                                <input type="text" class="form-control" value="{{ $user->name ?? '-' }}" disabled>

                                <div class="form-group">
                                    <label for="signature-eksternal">Tanda Tangan</label>

                                    <div class="mb-3">
                                        <label for="upload-signature-eksternal" class="form-label"></label>
                                        <input type="file" id="upload-signature-eksternal" accept="image/*"
                                            class="form-control">
                                    </div>

                                    <canvas id="signature-pad-eksternal"
                                        style="border: 1px solid #000; width: 100%; height: 150px; cursor: crosshair;"></canvas>

                                    <button type="button" id="clear-signature-eksternal" class="btn btn-delete mb-3"
                                        style="padding: 10px; font-size: 14px;">Reset</button>

                                    <input type="hidden" name="signature" id="signature-eksternal"
                                        value="{{ $nda->signature ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-2" style="font-size: 0.9rem; text-align: justify;">
                            Data No. KTP, Nama, dan Region tidak dapat diedit di sini. Jika tidak sesuai, silakan update di
                            menu Profil.
                        </p>
                        <div style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modalTambahNdaInternal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalTambahNdaInternal')">×</span>
                    <h5 id="judulModal">Pengajuan NDA</h5>

                    <form action="{{ isset($nda) ? route('nda.update', $nda->id) : route('nda.store') }}" method="POST">
                        @csrf
                        @if(isset($nda))
                            @method('PUT')
                        @endif
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <div style="width: 48%;">
                                <label>Nama</label>
                                <input type="text" class="form-control" value="{{ $user->name ?? '-' }}" disabled>

                                <label>No. KTP</label>
                                <input type="text" class="form-control" value="{{ $user->noktp ?? '-' }}" disabled>

                                <label>Alamat</label>
                                <input type="text" name="alamat" class="form-control" required
                                    value="{{ old('alamat', $user->alamat ?? '') }}">

                                <label>Catatan</label>
                                <input type="text" name="catatan" class="form-control" value="{{ $user->catatan ?? '' }}">
                            </div>

                            <div style="width: 48%;">
                                <div class="form-group">
                                    <label for="signature-internal">Tanda Tangan</label>

                                    <div class="mb-3">
                                        <label for="upload-signature-internal" class="form-label"></label>
                                        <input type="file" id="upload-signature-internal" accept="image/*"
                                            class="form-control">
                                    </div>

                                    <canvas id="signature-pad-internal"
                                        style="border: 1px solid #000; width: 100%; height: 150px; cursor: crosshair;"></canvas>

                                    <button type="button" id="clear-signature-internal" class="btn btn-delete mb-3"
                                        style="padding: 10px; font-size: 14px;">Reset</button>

                                    <input type="hidden" name="signature" id="signature-internal"
                                        value="{{ $nda->signature ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <p class="text-muted mt-2" style="font-size: 0.9rem; text-align: justify;">
                            Data No. KTP dan Nama tidak dapat diedit di sini. Jika tidak sesuai, silakan update di
                            menu Profil.
                        </p>
                        <div style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="tables-container dua" style="margin-top: 20px;">
                <div class="table-column">
                    <div class="title" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="button-wrapper">
                            @if(auth()->user()->role == 3)
                                <button class="btn btn-primary mb-3" onclick="openModal('modalTambahNdaInternal')">Ajukan
                                    NDA</button>
                            @elseif(auth()->user()->role == 4)
                                <button class="btn btn-primary mb-3" onclick="openModal('modalTambahNdaEksternal')">Ajukan
                                    NDA</button>
                            @endif
                        </div>
                        <h3>Pengajuan NDA</h3>
                    </div>
                    <div class="table-responsive" style="margin-top: 20px;">
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
                                                            <td>{{ \Carbon\Carbon::parse($nda->created_at)->translatedFormat('j F Y H:i') }}</td>
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
                        <h3>Riwayata NDA</h3>
                    </div></br>
                    <div class="table-responsive" style="margin-top: 20px;">
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
                                        <td>{{ \Carbon\Carbon::parse($nda->updated_at)->translatedFormat('j F Y H:i') }}</td>
                                        <td>{{ $nda->masaberlaku ? \Carbon\Carbon::parse($nda->masaberlaku)->translatedFormat('j F Y H:i') : '-' }}
                                        <td>{{ $nda->catatan ?? '-' }}</td>
                                        <td>
                                            @if($nda->file_path)
                                                <a href="{{ asset($nda->file_path) }}" target="_blank"
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

        window.onclick = function (event) {
            if (event.target.className === 'modal') {
                event.target.style.display = "none";
            }
        }

        const canvasEksternal = document.getElementById('signature-pad-eksternal');
        const contextEksternal = canvasEksternal.getContext('2d');
        const inputHiddenEksternal = document.getElementById('signature-eksternal');
        const uploadInputEksternal = document.getElementById('upload-signature-eksternal');
        const clearButtonEksternal = document.getElementById('clear-signature-eksternal');
        let isImageUploadedEksternal = false;

        const canvasInternal = document.getElementById('signature-pad-internal');
        const contextInternal = canvasInternal.getContext('2d');
        const inputHiddenInternal = document.getElementById('signature-internal');
        const uploadInputInternal = document.getElementById('upload-signature-internal');
        const clearButtonInternal = document.getElementById('clear-signature-internal');
        let isImageUploadedInternal = false;

        contextEksternal.strokeStyle = '#000';
        contextEksternal.lineWidth = 2;
        contextEksternal.lineCap = 'round';

        contextInternal.strokeStyle = '#000';
        contextInternal.lineWidth = 2;
        contextInternal.lineCap = 'round';

        if (inputHiddenEksternal.value) {
            const img = new Image();
            img.onload = function () {
                contextEksternal.drawImage(img, 0, 0, canvasEksternal.width, canvasEksternal.height);
                isImageUploadedEksternal = true;
                canvasEksternal.style.cursor = 'default';
            };
            img.src = inputHiddenEksternal.value;
        }

        if (inputHiddenInternal.value) {
            const img = new Image();
            img.onload = function () {
                contextInternal.drawImage(img, 0, 0, canvasInternal.width, canvasInternal.height);
                isImageUploadedInternal = true;
                canvasInternal.style.cursor = 'default';
            };
            img.src = inputHiddenInternal.value;
        }

        clearButtonEksternal.addEventListener('click', () => {
            contextEksternal.clearRect(0, 0, canvasEksternal.width, canvasEksternal.height);
            inputHiddenEksternal.value = '';
            isImageUploadedEksternal = false;
            canvasEksternal.style.cursor = 'crosshair';
        });

        clearButtonInternal.addEventListener('click', () => {
            contextInternal.clearRect(0, 0, canvasInternal.width, canvasInternal.height);
            inputHiddenInternal.value = '';
            isImageUploadedInternal = false;
            canvasInternal.style.cursor = 'crosshair';
        });

        uploadInputEksternal.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (event) {
                const img = new Image();
                img.onload = function () {
                    canvasEksternal.width = img.width;
                    canvasEksternal.height = img.height;
                    contextEksternal.clearRect(0, 0, canvasEksternal.width, canvasEksternal.height);
                    contextEksternal.drawImage(img, 0, 0);
                    inputHiddenEksternal.value = canvasEksternal.toDataURL('image/png');
                    isImageUploadedEksternal = true;
                    canvasEksternal.style.cursor = 'default';
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });

        uploadInputInternal.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (event) {
                const img = new Image();
                img.onload = function () {
                    canvasInternal.width = img.width;
                    canvasInternal.height = img.height;
                    contextInternal.clearRect(0, 0, canvasInternal.width, canvasInternal.height);
                    contextInternal.drawImage(img, 0, 0);
                    inputHiddenInternal.value = canvasInternal.toDataURL('image/png');
                    isImageUploadedInternal = true;
                    canvasInternal.style.cursor = 'default';
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });

        function updateSignatureInputEksternal() {
            if (!isImageUploadedEksternal) {
                inputHiddenEksternal.value = canvasEksternal.toDataURL('image/png');
            }
        }

        function updateSignatureInputInternal() {
            if (!isImageUploadedInternal) {
                inputHiddenInternal.value = canvasInternal.toDataURL('image/png');
            }
        }

        let isDrawingEksternal = false;
        function startDrawingEksternal(e) {
            if (isImageUploadedEksternal) return;
            isDrawingEksternal = true;
            const rect = canvasEksternal.getBoundingClientRect();
            contextEksternal.beginPath();
            contextEksternal.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        }

        function drawEksternal(e) {
            if (!isDrawingEksternal || isImageUploadedEksternal) return;
            const rect = canvasEksternal.getBoundingClientRect();
            contextEksternal.lineTo(e.clientX - rect.left, e.clientY - rect.top);
            contextEksternal.stroke();
        }

        function stopDrawingEksternal() {
            if (isImageUploadedEksternal) return;
            isDrawingEksternal = false;
            updateSignatureInputEksternal();
        }

        let isDrawingInternal = false;
        function startDrawingInternal(e) {
            if (isImageUploadedInternal) return;
            isDrawingInternal = true;
            const rect = canvasInternal.getBoundingClientRect();
            contextInternal.beginPath();
            contextInternal.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        }

        function drawInternal(e) {
            if (!isDrawingInternal || isImageUploadedInternal) return;
            const rect = canvasInternal.getBoundingClientRect();
            contextInternal.lineTo(e.clientX - rect.left, e.clientY - rect.top);
            contextInternal.stroke();
        }

        function stopDrawingInternal() {
            if (isImageUploadedInternal) return;
            isDrawingInternal = false;
            updateSignatureInputInternal();
        }

        canvasEksternal.addEventListener('mousedown', startDrawingEksternal);
        canvasEksternal.addEventListener('mousemove', drawEksternal);
        canvasEksternal.addEventListener('mouseup', stopDrawingEksternal);
        canvasEksternal.addEventListener('mouseleave', stopDrawingEksternal);

        canvasInternal.addEventListener('mousedown', startDrawingInternal);
        canvasInternal.addEventListener('mousemove', drawInternal);
        canvasInternal.addEventListener('mouseup', stopDrawingInternal);
        canvasInternal.addEventListener('mouseleave', stopDrawingInternal);

        function resizeCanvasEksternal() {
            const dataURL = inputHiddenEksternal.value;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvasEksternal.width = canvasEksternal.offsetWidth * ratio;
            canvasEksternal.height = canvasEksternal.offsetHeight * ratio;
            contextEksternal.scale(ratio, ratio);
            contextEksternal.strokeStyle = '#000';
            contextEksternal.lineWidth = 2;
            contextEksternal.lineCap = 'round';
            contextEksternal.clearRect(0, 0, canvasEksternal.width, canvasEksternal.height);
            if (dataURL) {
                const img = new Image();
                img.onload = function () {
                    contextEksternal.drawImage(img, 0, 0, canvasEksternal.width, canvasEksternal.height);
                };
                img.src = dataURL;
            }
        }

        function resizeCanvasInternal() {
            const dataURL = inputHiddenInternal.value;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvasInternal.width = canvasInternal.offsetWidth * ratio;
            canvasInternal.height = canvasInternal.offsetHeight * ratio;
            contextInternal.scale(ratio, ratio);
            contextInternal.strokeStyle = '#000';
            contextInternal.lineWidth = 2;
            contextInternal.lineCap = 'round';
            contextInternal.clearRect(0, 0, canvasInternal.width, canvasInternal.height);
            if (dataURL) {
                const img = new Image();
                img.onload = function () {
                    contextInternal.drawImage(img, 0, 0, canvasInternal.width, canvasInternal.height);
                };
                img.src = dataURL;
            }
        }

        window.addEventListener('resize', () => {
            resizeCanvasEksternal();
            resizeCanvasInternal();
        });

        resizeCanvasEksternal();
        resizeCanvasInternal();
    </script>

@endsection