@extends('layouts.app')
@section('title', 'Portal VMS')
@section('page_title', 'Portal VMS')
@section('content')
<div class="main">
<div class="container">
    <!-- Tombol untuk membuka modal -->
    <button class="btn btn-primary mb-3" onclick="openModal('modalAjukanDCS')">Ajukan Permohonan Visit Data Center</button>
    <!-- Modal Upload Dokumen -->
        <div class="modal" id="modalAjukanDCS">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalAjukanDCS')">&times;</span>
                <h5>Ajukan Visit DCS</h5>
                <form action="{{ route('dokumen.store') }}" method="POST" id="formAjukanDCS" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Dokumen</label>
                        <input type="text" name="nama_dokumen" class="form-control" id="nama_dokumen" required value="">
                    </div>
                    <div class="mb-3">
                        <label>Catatan</label>
                        <input type="text" name="catatan" class="form-control" id="catatan" value="">
                    </div>
                    <div class="mb-3">
                        <label>File Dokumen (PDF atau DOCX)</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".pdf,.doc,.docx" required>
                        <small class="text-muted">Maksimum ukuran file: 10MB</small>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

    <div class="table-responsive {{ Route::currentRouteName() == 'upload.index' ? 'table-responsive-aset' : '' }}">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dokumen</th>
                    <th>Tanggal Upload</th>
                    <th>Status</th>
                    <th>Masa Berlaku</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dokumen as $index => $d)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $d->nama_dokumen }}</td>
                    <td>{{ $d->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span style="display: inline-flex; align-items: center;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                background-color: 
                                    {{ $d->status == 'pending' ? '#ffc107' : 
                                    ($d->status == 'diterima' ? '#28a745' : '#dc3545') }};">
                            </span>
                            {{ ucfirst($d->status) }}
                        </span>
                    </td>
                    <td>{{ $d->masa_berlaku ? $d->masa_berlaku->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $d->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                        @if($d->status == 'diterima' && $d->signed_at)
                            <div class="mt-1">
                                <small class="text-muted">
                                    <i class="fas fa-signature"></i> Ditandatangani oleh: {{ $d->signed_by }}<br>
                                    <i class="fas fa-clock"></i> Pada: {{ $d->signed_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada riwayat permohonan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
    </div>
@endsection
