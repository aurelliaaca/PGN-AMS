@extends('layouts.app')

@section('title', 'Verifikasi DCAF')
@section('page_title', 'Verifikasi DCAF')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="main">
        <div class="title" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
            <h3>DCAF yang Perlu Diverifikasi</h3>
        </div>
        <div class="table-responsive" style="margin-top: 20px;">
            <table id="pendingTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Tanggal Upload</th>
                        <th>File NDA</th>
                        <th>File DCAF</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingDcafs as $index => $dcaf)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $dcaf->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($dcaf->created_at)->translatedFormat('j F Y H:i') }}</td>
                            <td>
                                <a href="{{ asset($dcaf->nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat
                                    NDA</a>
                            </td>
                            <td>
                                <a href="{{ asset($dcaf->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat
                                    DCAF</a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="openModal('pengawasModal{{ $dcaf->id }}')">Terima</button>

                                <button type="button" class="btn btn-delete btn-sm" style="font-size: 14px;"
                                    onclick="konfirmasiTolak({{ $dcaf->id }})">Tolak</button>

                                <form id="form-tolak-{{ $dcaf->id }}" action="{{ route('dcaf.update', $dcaf->id) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="ditolak">
                                </form>
                            </td>
                        </tr>

                        <div id="pengawasModal{{ $dcaf->id }}" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal('pengawasModal{{ $dcaf->id }}')">&times;</span>
                                <h5>Pilih Pengawas</h5>
                                <form action="{{ route('dcaf.update', $dcaf->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="diterima">
                                    <div class="mb-3">
                                        <label>Pengawas</label>
                                        <select name="pengawas" class="form-control" required>
                                            <option value="">Pilih Pengawas</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Terima</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="modalSetuju" tabindex="-1" aria-labelledby="modalSetujuLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="form-terima" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="diterima">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pilih Pengawas</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <label for="pengawas">Pengawas</label>
                            <select class="form-control select2" name="user_id" id="selectPengawas" required>
                                <option value="">Pilih Pengawas</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="title" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
            <h3>Data DCAF yang Berlaku</h3>
        </div>
        <div class="table-responsive" style="margin-top: 20px;">
            <table id="activeTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Tanggal Upload</th>
                        <th>Tanggal Verifikasi</th>
                        <th>Masa Berlaku</th>
                        <th>File NDA</th>
                        <th>File DCAF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeDcafs as $index => $dcaf)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $dcaf->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($dcaf->created_at)->translatedFormat('j F Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($dcaf->updated_at)->translatedFormat('j F Y H:i') }}</td>
                            <td>{{ $dcaf->masaberlaku ? \Carbon\Carbon::parse($dcaf->masaberlaku)->translatedFormat('j F Y H:i') : '-' }}
                            <td>
                                <a href="{{ asset($dcaf->nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat
                                    NDA</a>
                            </td>
                            <td>
                                <a href="{{ asset($dcaf->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat
                                    DCAF</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="title" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
            <h3>Riwayat DCAF yang Kadaluarsa</h3>
        </div>
        <div class="table-responsive" style="margin-top: 20px;">
            <table id="expiredTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Tanggal Upload</th>
                        <th>Tanggal Verifikasi</th>
                        <th>Masa Berlaku</th>
                        <th>File NDA</th>
                        <th>File DCAF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiredDcafs as $index => $dcaf)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $dcaf->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($dcaf->created_at)->translatedFormat('j F Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($dcaf->updated_at)->translatedFormat('j F Y H:i') }}</td>
                            <td>{{ $dcaf->masaberlaku ? \Carbon\Carbon::parse($dcaf->masaberlaku)->translatedFormat('j F Y H:i') : '-' }}
                            <td>
                                <a href="{{ asset($dcaf->nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat
                                    NDA</a>
                            </td>
                            <td>
                                <a href="{{ asset($dcaf->nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat
                                    DCAF</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#pendingTable').DataTable({
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
                        { targets: [4, 5], orderable: false }
                    ]
                });

                $('#activeTable').DataTable({
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
                        { targets: [5, 6], orderable: false }
                    ]
                });

                $('#expiredTable').DataTable({
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
                        { targets: [5, 6], orderable: false }
                    ]
                });
            });

            function konfirmasiSetuju(id) {
                Swal.fire({
                    title: 'Terima NDA?',
                    text: "NDA ini akan disetujui.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Terima',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-terima-' + id).submit();
                    }
                });
            }

            function konfirmasiTolak(id) {
                Swal.fire({
                    title: 'Yakin mau tolak?',
                    text: 'NDA ini akan ditolak.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-tolak-' + id).submit();
                    }
                });
            }
        </script>
    @endsection
@endsection