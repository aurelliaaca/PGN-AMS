@extends('layouts.app')

@section('title', 'Data Fasilitas')
@section('page_title', 'Data Fasilitas')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="main">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="tables-container dua" style="margin-top: 20px;">
            <div class="table-column">
                <div class="title" style="display: flex; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary" style="margin-bottom: 10px;" onclick="openModal('modalTambahJenis')">+ Tambah Jenis</button>
                    <h3>Data Jenis</h3>
                </div>
                <div class="table-responsive">
                    <table id="jenisTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama Fasilitas</th>
                                <th>Kode Fasilitas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jenisfasilitas as $item)
                                <tr>
                                    <td>{{ $item->nama_fasilitas }}</td>
                                    <td>{{ $item->kode_fasilitas }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-edit btn-sm mb-1"
                                                onclick="openModal('modalEditJenis{{ $item->kode_fasilitas }}')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-delete btn-sm"
                                                onclick="confirmDelete('{{ $item->kode_fasilitas }}')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>

                                            <form id="delete-form-{{ $item->kode_fasilitas }}"
                                                action="{{ route('jenisfasilitas.destroy', $item->kode_fasilitas) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <div id="modalEditJenis{{ $item->kode_fasilitas }}" class="modal">
                                    <div class="modal-content">
                                        <span class="close"
                                            onclick="closeModal('modalEditJenis{{ $item->kode_fasilitas }}')">&times;</span>
                                        <h5>Edit Jenis Fasilitas</h5>
                                        <form action="{{ route('jenisfasilitas.update', $item->kode_fasilitas) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label>Nama Fasilitas</label>
                                                <input type="text" name="nama_fasilitas" class="form-control"
                                                    value="{{ $item->nama_fasilitas }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Kode Fasilitas</label>
                                                <input type="text" name="kode_fasilitas" class="form-control"
                                                    value="{{ $item->kode_fasilitas }}" readonly>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data jenis fasilitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-column">
                <div class="title" style="display: flex; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary" style="margin-bottom: 10px;" onclick="openModal('modalTambahBrand')">+ Tambah Brand</button>
                    <h3>Data Brand</h3>
                </div>
                <div class="table-responsive">
                    <table id="brandTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama Brand</th>
                                <th>Kode Brand</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($brandfasilitas as $item)
                                <tr>
                                    <td>{{ $item->nama_brand }}</td>
                                    <td>{{ $item->kode_brand }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-edit btn-sm mb-1"
                                                onclick="openModal('modalEdit{{ $item->kode_brand }}')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-delete btn-sm"
                                                onclick="confirmDelete('{{ $item->kode_brand }}')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>

                                            <form id="delete-form-{{ $item->kode_brand }}"
                                                action="{{ route('brandperangkat.destroy', $item->kode_brand) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <div id="modalEdit{{ $item->kode_brand }}" class="modal">
                                    <div class="modal-content">
                                        <span class="close"
                                            onclick="closeModal('modalEdit{{ $item->kode_brand }}')">&times;</span>
                                        <h5>Edit Brand Fasilitas</h5>
                                        <form action="{{ route('brandfasilitas.update', $item->kode_brand) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label>Nama Brand</label>
                                                <input type="text" name="nama_brand" class="form-control"
                                                    value="{{ $item->nama_brand }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Kode Brand</label>
                                                <input type="text" name="kode_brand" class="form-control"
                                                    value="{{ $item->kode_brand }}" readonly>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data brand.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modalTambahJenis" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambahJenis')">&times;</span>
            <h5>Tambah Jenis Fasilitas</h5>
            <form action="{{ route('jenisfasilitas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nama Jenis</label>
                    <input type="text" name="nama_fasilitas" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Kode Jenis</label>
                    <input type="text" name="kode_fasilitas" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>

    <div id="modalTambahBrand" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambahBrand')">&times;</span>
            <h5>Tambah Brand Fasilitas</h5>
            <form action="{{ route('brandfasilitas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nama Brand</label>
                    <input type="text" name="nama_brand" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Kode Brand</label>
                    <input type="text" name="kode_brand" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>

    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#jenisTable').DataTable({
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
                            "targets": 2, 
                            "orderable": false 
                        }
                    ]
                });

                $('#brandTable').DataTable({
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
                            "targets": 2, 
                            "orderable": false 
                        }
                    ]
                });
            });

            function openModal(modalId) {
                document.getElementById(modalId).style.display = 'block';
            }

            function closeModal(modalId) {
                document.getElementById(modalId).style.display = 'none';
            }
        </script>
    @endsection
@endsection