@extends('layouts.app')

@section('title', 'Histori Alatukur')
@section('page_title', 'Histori Alatukur')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="main" style="padding-top: 10px">
        <div class="table-responsive">
            <table id="historiAlatukurTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Region</th>
                        <th>Alatukur</th>
                        <th>Alatukur ke</th>
                        <th>Brand</th>
                        <th>Type</th>
                        <th>Serial Number</th>
                        <th>Tahun Perolehan</th>
                        <th>Kondisi</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                        <th>Tanggal Perubahan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historialatukur as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->region->nama_region }}</td>
                            <td>{{ $item->jenisalatukur->nama_alatukur }}</td>
                            <td>{{ $item->alatukur_ke }}</td>
                            <td>{{ optional($item->brandalatukur)->nama_brand }}</td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->serialnumber }}</td>
                            <td>{{ $item->tahunperolehan }}</td>
                            <td>{{ $item->kondisi }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>{{ $item->histori }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_perubahan)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</td>
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
                $('#historiAlatukurTable').DataTable({
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
                    "pageLength": 10,
                    "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                    "columnDefs": [
                        {
                            "targets": [0, 8],
                            "orderable": false
                        }
                    ]
                });
            });
        </script>
    @endsection
@endsection