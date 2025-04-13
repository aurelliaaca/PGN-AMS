@extends('layouts.app')

@section('title', 'Data Fasilitas')
@section('page_title', 'Data Fasilitas')

@section('content')
    <style>
        .table-responsive {
            margin-top: 20px;
            max-height: 78vh;
            overflow: auto;
            border-radius: 10px;
            border: 2px solid rgb(209, 210, 241);
            /* border warna biru dan ketebalan 2px */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: #f1f1fb;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .table th,
        .table td {
            text-align: center;
            padding: 12px;
            font-size: 14px;
            border-bottom: 1px solid #ddd;
        }

        .table thead th {
            color: #4f52ba;
            font-weight: bold;
        }

        .table thead th:first-child {
            border-top-left-radius: 10px;
        }

        .table thead th:last-child {
            border-top-right-radius: 10px;
        }

        .table tbody tr:hover {
            background-color: #f3f3ff;
        }

        .btn-primary {
            background-color: #4f52ba;
            border: none;
            padding: 10px;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            margin: 0px;
        }

        .btn-primary:hover {
            background-color: rgb(209, 210, 241);
            color: #4f52ba;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .action-buttons button {
            border: none;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #4f52ba;
            border: none;
            padding: 10px;
            border-radius: 5px;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            border: none;
            padding: 10px;
            border-radius: 5px;
            color: white;
        }

        .tables-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .table-column {
            flex: 1;
        }
    </style>

    <div class="main">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="tables-container">
            <!-- Tabel Jenis Fasilitas -->
            <div class="table-column">
                <div class="title" style="display: flex; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary mb-3" onclick="openModal('modalTambahJenis')">+ Tambah Jenis</button>
                    <h3>Data Jenis</h3>
                </div>
                <div class="table-responsive">
                    <table class="table">
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
                                            <button class="btn btn-edit mb-3"
                                                onclick="openModal('modalEditJenis{{ $item->kode_fasilitas }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('jenisfasilitas.destroy', $item->kode_fasilitas) }}"
                                                method="POST" onsubmit="return confirm('Yakin ingin hapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete mb-3">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal Edit Jenis -->
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
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="3" class="no-data">Belum ada data jenis fasilitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Brand -->
            <div class="table-column">
                <div class="title" style="display: flex; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary mb-3" onclick="openModal('modalTambahBrand')">+ Tambah Brand</button>
                    <h3>Data Brand</h3>
                </div>
                <div class="table-responsive">
                    <table class="table">
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
                                            <button class="btn btn-edit mb-3"
                                                onclick="openModal('modalEdit{{ $item->kode_brand }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('brandfasilitas.destroy', $item->kode_brand) }}"
                                                method="POST" onsubmit="return confirm('Yakin ingin hapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete mb-3">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal Edit -->
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
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="3" class="no-data">Belum ada data brand.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Jenis -->
    <div id="modalTambahJenis" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambahJenis')">&times;</span>
            <h5>Tambah Jenis Fasilitas</h5>
            <form action="{{ route('jenisfasilitas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nama Jenis</label>
                    <input type="text" name="nama_jenis" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Kode Jenis</label>
                    <input type="text" name="kode_jenis" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Brand -->
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
@endsection