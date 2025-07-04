@extends('layouts.app')

@section('title', 'NDA')
@section('page_title', 'NDA')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

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
                    <div class="title"
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: -9px;">
                        <div class="button-wrapper" style="margin-bottom: 20px;">
                            @if(auth()->user()->role == 4)
                                <button class="btn btn-primary mb-3" onclick="openModal('modalTambahNdaInternal')">Ajukan
                                    NDA</button>
                            @elseif(auth()->user()->role == 5)
                                <button class="btn btn-primary mb-3" onclick="openModal('modalTambahNdaEksternal')">Ajukan
                                    NDA</button>
                            @endif
                        </div>
                        <h3>Riwayat NDA</h3>
                    </div>
                    <div class="table-responsive">
                        <table id="ajukanTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal Disetujui</th>
                                    <th>Masa Berlaku</th>
                                    <th>Catatan</th>
                                    <th>File</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($pendingNdas as $index => $nda)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($nda->created_at)->translatedFormat('j F Y H:i') }}</td>
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
                                        <td>
                                            <span style="display: inline-flex; align-items: center;">
                                                <span
                                                    style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px; background-color: #f6b26b;">
                                                </span>
                                                Menunggu persetujuan
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach($rejectedNdas as $index => $nda)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($nda->created_at)->translatedFormat('j F Y H:i') }}</td>
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
                                        <td>
                                            <span style="display: inline-flex; align-items: center;">
                                                <span
                                                    style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px; background-color: #dc3545;">
                                                </span>
                                                Ditolak
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach($activeNdas as $index => $nda)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($nda->created_at)->translatedFormat('j F Y H:i') }}</td>
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
                                        <td>
                                            <span style="display: inline-flex; align-items: center;">
                                                <span
                                                    style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px; background-color: #28a745;">
                                                </span>
                                                Aktif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach($expiredNdas as $index => $nda)
                                    <tr>
                                        <td>{{ $no++ }}</td>
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
                                        <td>
                                            <span style="display: inline-flex; align-items: center;">
                                                <span
                                                    style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px; background-color: #bcbcbc;">
                                                </span>
                                                Kadaluarsa
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            $(document).ready(function () {
                $('#buatTable').DataTable({
                    "language": {
                        "search": "Cari",
                        "lengthMenu": "_MENU_",
                        "zeroRecords": "Tidak ada data yang ditemukan",
                        "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                        "infoEmpty": "Tidak ada data yang tersedia",
                        "infoFiltered": "(difilter dari _MAX_ total data)",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "<i class='fas fa-arrow-right'></i>",
                            "previous": "<i class='fas fa-arrow-left'></i>"
                        }
                    },
                    pageLength: 10,
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                    order: [],
                    columnDefs: [
                        { targets: [], orderable: false }
                    ]
                });

                $('#ajukanTable').DataTable({
                    "language": {
                        "search": "Cari",
                        "lengthMenu": "_MENU_",
                        "zeroRecords": "Tidak ada data yang ditemukan",
                        "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                        "infoEmpty": "Tidak ada data yang tersedia",
                        "infoFiltered": "(difilter dari _MAX_ total data)",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "<i class='fas fa-arrow-right'></i>",
                            "previous": "<i class='fas fa-arrow-left'></i>"
                        }
                    },
                    pageLength: 10,
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                    order: [],
                    columnDefs: [
                        { targets: [5], orderable: false }
                    ]
                });
            });

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

            document.addEventListener('DOMContentLoaded', function () {
                function initSignaturePad(canvasId, inputId, uploadId, clearId) {
                    const canvas = document.getElementById(canvasId);
                    if (!canvas) return;

                    const context = canvas.getContext('2d');
                    const inputHidden = document.getElementById(inputId);
                    const uploadInput = document.getElementById(uploadId);
                    const clearButton = document.getElementById(clearId);
                    let isImageUploaded = false;

                    canvas.width = canvas.offsetWidth;
                    canvas.height = canvas.offsetHeight;

                    context.strokeStyle = '#000';
                    context.lineWidth = 2;
                    context.lineCap = 'round';

                    if (inputHidden && inputHidden.value) {
                        const img = new Image();
                        img.onload = function () {
                            context.drawImage(img, 0, 0, canvas.width, canvas.height);
                            isImageUploaded = true;
                            canvas.style.cursor = 'default';
                        };
                        img.src = inputHidden.value;
                    }

                    if (clearButton) {
                        clearButton.addEventListener('click', () => {
                            context.clearRect(0, 0, canvas.width, canvas.height);
                            if (inputHidden) inputHidden.value = '';
                            isImageUploaded = false;
                            canvas.style.cursor = 'crosshair';
                        });
                    }

                    if (uploadInput) {
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
                                    if (inputHidden) inputHidden.value = canvas.toDataURL('image/png');
                                    isImageUploaded = true;
                                    canvas.style.cursor = 'default';
                                };
                                img.src = event.target.result;
                            };
                            reader.readAsDataURL(file);
                        });
                    }

                    let isDrawing = false;
                    let lastX = 0;
                    let lastY = 0;

                    function startDrawing(e) {
                        if (isImageUploaded) return;
                        isDrawing = true;
                        const rect = canvas.getBoundingClientRect();
                        lastX = e.clientX - rect.left;
                        lastY = e.clientY - rect.top;
                        context.beginPath();
                        context.moveTo(lastX, lastY);
                    }

                    function draw(e) {
                        if (!isDrawing || isImageUploaded) return;
                        const rect = canvas.getBoundingClientRect();
                        const currentX = e.clientX - rect.left;
                        const currentY = e.clientY - rect.top;

                        context.beginPath();
                        context.moveTo(lastX, lastY);
                        context.lineTo(currentX, currentY);
                        context.stroke();

                        lastX = currentX;
                        lastY = currentY;

                        if (inputHidden) {
                            inputHidden.value = canvas.toDataURL('image/png');
                        }
                    }

                    function stopDrawing() {
                        if (isImageUploaded) return;
                        isDrawing = false;
                    }

                    canvas.addEventListener('mousedown', startDrawing);
                    canvas.addEventListener('mousemove', draw);
                    canvas.addEventListener('mouseup', stopDrawing);
                    canvas.addEventListener('mouseleave', stopDrawing);

                    canvas.addEventListener('touchstart', function (e) {
                        e.preventDefault();
                        const touch = e.touches[0];
                        const mouseEvent = new MouseEvent('mousedown', {
                            clientX: touch.clientX,
                            clientY: touch.clientY
                        });
                        canvas.dispatchEvent(mouseEvent);
                    });

                    canvas.addEventListener('touchmove', function (e) {
                        e.preventDefault();
                        const touch = e.touches[0];
                        const mouseEvent = new MouseEvent('mousemove', {
                            clientX: touch.clientX,
                            clientY: touch.clientY
                        });
                        canvas.dispatchEvent(mouseEvent);
                    });

                    canvas.addEventListener('touchend', function (e) {
                        e.preventDefault();
                        const mouseEvent = new MouseEvent('mouseup', {});
                        canvas.dispatchEvent(mouseEvent);
                    });
                }

                function initModalSignaturePads(modalId) {
                    const modal = document.getElementById(modalId);
                    if (!modal) return;

                    const observer = new MutationObserver(function (mutations) {
                        mutations.forEach(function (mutation) {
                            if (mutation.attributeName === 'style') {
                                const display = window.getComputedStyle(modal).display;
                                if (display === 'block') {
                                    if (modalId === 'modalTambahNdaInternal') {
                                        initSignaturePad('signature-pad-internal', 'signature-internal', 'upload-signature-internal', 'clear-signature-internal');
                                    } else if (modalId === 'modalTambahNdaEksternal') {
                                        initSignaturePad('signature-pad-eksternal', 'signature-eksternal', 'upload-signature-eksternal', 'clear-signature-eksternal');
                                    }
                                }
                            }
                        });
                    });

                    observer.observe(modal, { attributes: true });
                }

                initModalSignaturePads('modalTambahNdaInternal');
                initModalSignaturePads('modalTambahNdaEksternal');
            });
        </script>
    @endsection
@endsection