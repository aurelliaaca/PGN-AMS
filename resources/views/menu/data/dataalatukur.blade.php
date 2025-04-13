<div>
    <!-- Order your soul. Reduce your wants. - Augustine -->
</div>
@extends('layouts.app')

@section('title', 'Data Alatukur')
@section('page_title', 'Data Alatukur')

@section('content')
    <div class="main">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="tables-container">
            <!-- Tabel Jenis Alatukur -->
            <div class="table-column">
                <div class="title" style="display: flex; justify-content: space-between; align-items: center;">
                    <button class="btn btn-primary mb-3" onclick="openModal('modalTambahJenis')">+ Tambah Jenis</button>
                    <h3>Data Jenis</h3>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Alatukur</th>
                                <th>Kode Alatukur</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jenisalatukur as $item)
                                <tr>
                                    <td>{{ $item->nama_alatukur }}</td>
                                    <td>{{ $item->kode_alatukur }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-edit mb-3"
                                                onclick="openModal('modalEditJenis{{ $item->kode_alatukur }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('jenisalatukur.destroy', $item->kode_alatukur) }}"
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
                                <div id="modalEditJenis{{ $item->kode_alatukur }}" class="modal">
                                    <div class="modal-content">
                                        <span class="close"
                                            onclick="closeModal('modalEditJenis{{ $item->kode_alatukur }}')">&times;</span>
                                        <h5>Edit Jenis Alatukur</h5>
                                        <form action="{{ route('jenisalatukur.update', $item->kode_alatukur) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label>Nama Alatukur</label>
                                                <input type="text" name="nama_alatukur" class="form-control"
                                                    value="{{ $item->nama_alatukur }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Kode Alatukur</label>
                                                <input type="text" name="kode_alatukur" class="form-control"
                                                    value="{{ $item->kode_alatukur }}" readonly>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="3" class="no-data">Belum ada data jenis alatukur.</td>
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
                            @forelse ($brandalatukur as $item)
                                <tr>
                                    <td>{{ $item->nama_brand }}</td>
                                    <td>{{ $item->kode_brand }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-edit mb-3"
                                                onclick="openModal('modalEdit{{ $item->kode_brand }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('brandalatukur.destroy', $item->kode_brand) }}"
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
                                        <h5>Edit Brand Alatukur</h5>
                                        <form action="{{ route('brandalatukur.update', $item->kode_brand) }}" method="POST">
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
            <h5>Tambah Jenis Alatukur</h5>
            <form action="{{ route('jenisalatukur.store') }}" method="POST">
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
            <h5>Tambah Brand Alatukur</h5>
            <form action="{{ route('brandalatukur.store') }}" method="POST">
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