@extends('layouts.app')

@section('title', 'Verifikasi NDA')
@section('page_title', 'Verifikasi NDA')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="main">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="title" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                    <h3>Data NDA Active</h3>
                </div>
                <div class="table-responsive" style="margin-top: 20px;">
                    <table id="pendingTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Tanggal Upload</th>
                                <th>Tanggal Verifikasi</th>
                                <th>Masa Berlaku</th>
                                <th>Catatan</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingNdas as $index => $nda)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $nda->user->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($nda->created_at)->translatedFormat('j F Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($nda->updated_at)->translatedFormat('j F Y H:i') }}</td>
                                <td>{{ $nda->masa_berlaku ? \Carbon\Carbon::parse($nda->masa_berlaku)->translatedFormat('j F Y H:i') : '-' }}</td>
                                <td>{{ $nda->catatan ?? '-' }}</td>
                                <td>
                                    <a href="{{ asset($nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat File</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada NDA yang masih berlaku</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

        <div class="tables-container dua">
            <div class="table-column">
                <div class="title" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                    <h3>Data NDA Active</h3>
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
                                <th>Catatan</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeNdas as $index => $nda)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $nda->user->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($nda->created_at)->translatedFormat('j F Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($nda->updated_at)->translatedFormat('j F Y H:i') }}</td>
                                <td>{{ $nda->masa_berlaku ? \Carbon\Carbon::parse($nda->masa_berlaku)->translatedFormat('j F Y H:i') : '-' }}</td>
                                <td>{{ $nda->catatan ?? '-' }}</td>
                                <td>
                                    <a href="{{ asset('pdf/' . $nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat File</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada NDA yang masih berlaku</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-column">
                <div class="title" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                    <h3>Data NDA Kadaluarsa</h3>
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
                                <th>Catatan</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expiredNdas as $index => $nda)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $nda->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($nda->created_at)->translatedFormat('j F Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($nda->updated_at)->translatedFormat('j F Y H:i') }}</td>
                    <td>{{ $nda->masa_berlaku ? \Carbon\Carbon::parse($nda->masa_berlaku)->translatedFormat('j F Y H:i') : '-' }}</td>                        <td>{{ $nda->catatan ?? '-' }}</td>
                    <td>
                        <a href="{{ asset( $nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat File</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada riwayat NDA yang tidak berlaku</td>
                </tr>
                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
                        { targets: [5, 6], orderable: false }
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
        </script>
    @endsection
@endsection