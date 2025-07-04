@extends('layouts.app')

@section('title', 'DCAF')
@section('page_title', 'DCAF')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="main">
        <div class="container">
            <button class="btn btn-primary" style="margin-top: 20px; margin-bottom: 10px;"
                onclick="window.location.href='{{ route('pendaftarandcaf') }}'">Ajukan DCAF
            </button>

            <div class="table-responsive">
                <table id="dcafTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Upload</th>
                            <th>Status NDA</th>
                            <th>Masa Berlaku NDA</th>
                            <th>Status DCAF</th>
                            <th>Masa Berlaku DCAF</th>
                            <th>NDA</th>
                            <th>DCAF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dcafs as $index => $dcaf)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $dcaf->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span style="display: inline-flex; align-items: center;">
                                    <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px; background-color: 
                                        {{ $dcaf->nda->status == 'pending' ? '#ffc107' :
                                        ($dcaf->nda->status == 'diterima' ? '#28a745' : '#dc3545') }};">
                                    </span>
                                        {{ ucfirst($dcaf->nda->status) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $dcaf->nda->masaberlaku 
                                        ? \Carbon\Carbon::parse($dcaf->nda->masaberlaku)->translatedFormat('j F Y H:i') 
                                        : '-' 
                                    }}
                                </td>
                                <td>
                                    <span style="display: inline-flex; align-items: center;">
                                    <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px; background-color: 
                                        {{ $dcaf->status == 'pending' ? '#ffc107' :
                                        ($dcaf->status == 'diterima' ? '#28a745' :
                                        ($dcaf->status == 'ditolak' ? '#dc3545' : '#ffc107')) }};">
                                    </span>
                                        {{ ucfirst($dcaf->status) }}
                                    </span>
                                </td>
                                <td>{{ $dcaf->masaberlaku 
                                        ? \Carbon\Carbon::parse($dcaf->masaberlaku)->translatedFormat('j F Y H:i') 
                                        : '-' 
                                    }}
                                </td>
                                <td>
                                    @if($dcaf->nda->status == 'diterima')
                                        <a href="{{ asset($dcaf->nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat NDA</a>
                                    @else
                                        <span class="text-muted">Belum dapat diakses</span>
                                    @endif
                                </td>
                                <td>
                                    @if($dcaf->status == 'diterima')
                                        <a href="{{ asset($dcaf->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat DCAF</a>
                                    @else
                                    <span class="text-muted">Belum dapat diakses</span> @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
    $(document).ready(function () {
        $('#dcafTable').DataTable({
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
                { targets: [6, 7], orderable: false }
            ]
        });
    });
</script>

@endsection