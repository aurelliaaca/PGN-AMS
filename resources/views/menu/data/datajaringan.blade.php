@extends('layouts.app')
@section('title', 'Data Jaringan')
@section('page_title', 'Data Jaringan')

@section('content')
<div class="main">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
        <button class="btn btn-primary" onclick="openModal('modalTambahTipeJaringan')">+ Tambah Tipe Jaringan</button>
        <h3 style="margin: 0;">Data Tipe Jaringan</h3>
    </div>
               
    <div class="table-responsive" style="margin-top: 20px;">
        <table id="tipeJaringanTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Tipe Jaringan</th>
                    <th>Nama Tipe Jaringan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tipeJaringan as $tj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tj->kode_tipejaringan }}</td>
                    <td>{{ $tj->nama_tipejaringan }}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-edit btn-sm mb-1"
                                onclick="openModal('modalEditTipeJaringan{{ $tj->kode_tipejaringan }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-delete btn-sm"
                                onclick="confirmDelete('{{ $tj->kode_tipejaringan }}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>

                            <form id="delete-form-{{ $tj->kode_tipejaringan }}" 
                                  action="{{ route('tipejaringan.destroy', $tj->kode_tipejaringan) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>

                <div id="modalEditTipeJaringan{{ $tj->kode_tipejaringan }}" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('modalEditTipeJaringan{{ $tj->kode_tipejaringan }}')">&times;</span>
                        <h5>Edit Tipe Jaringan</h5>
                        <form action="{{ route('tipejaringan.update', $tj->kode_tipejaringan) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <label>Kode Tipe Jaringan</label>
                            <input type="text" name="kode_tipejaringan" value="{{ old('kode_tipejaringan', $tj->kode_tipejaringan) }}" class="form-control" required>

                            <label>Nama Tipe Jaringan</label>
                            <input type="text" name="nama_tipejaringan" value="{{ old('nama_tipejaringan', $tj->nama_tipejaringan) }}" class="form-control" required>

                            <button type="submit" class="btn btn-primary mt-3">Perbarui</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="modalTambahTipeJaringan" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('modalTambahTipeJaringan')">&times;</span>
            <h5>Tambah Tipe Jaringan</h5>
            <form action="{{ route('tipejaringan.store') }}" method="POST">
                @csrf
                <label>Kode Tipe Jaringan</label>
                <input type="text" name="kode_tipejaringan" class="form-control" required>

                <label>Nama Tipe Jaringan</label>
                <input type="text" name="nama_tipejaringan" class="form-control" required>

                <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            </form>
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

    function confirmDelete(kode) {
        if (confirm('Apakah Anda yakin ingin menghapus tipe jaringan ini?')) {
            document.getElementById('delete-form-' + kode).submit();
        }
    }

    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = "none";
        }
    }
</script>
@endsection
