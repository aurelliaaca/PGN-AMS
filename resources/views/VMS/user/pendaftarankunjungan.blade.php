@extends('layouts.app')
@section('title', 'Pendaftaran Kunjungan')
@section('page_title', 'Pendaftaran Kunjungan')
@section('content')
<div class="main">
<div class="container">
    <!-- Tombol untuk membuka modal -->
    <button class="btn btn-primary mb-3" onclick="window.location.href='{{ route('pendaftarandcaf') }}'">Buat DCAF</button>
    <button class="btn btn-primary mb-3" onclick="openModal('modalAjukanDCS')">Ajukan Permohonan Visit Data Center</button>
    <!-- Modal Upload Dokumen -->
        <div class="modal" id="modalAjukanDCS">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalAjukanDCS')">&times;</span>
                <h5>Ajukan Visit DCS</h5>
                <form action="{{ route('dokumen.store') }}" method="POST" id="formAjukanDCS" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>Catatan</label>
                        <input type="text" name="catatan" class="form-control" id="catatan" value="">
                    </div>
                    <div class="mb-3">
                        <label>Pilih NDA Aktif</label>
                        <select name="verifikasi_nda_id" id="verifikasi_nda_id" class="form-control" required>
                            <option value="">-- Pilih NDA --</option>
                            @foreach($activeNdas as $nda)
                                <option value="{{ $nda->id }}">NDA berlaku sampai {{ $nda->masa_berlaku->format('d F Y') }}</option>
                            @endforeach
                        </select>
                        @if(count($activeNdas) == 0)
                            <small class="text-danger">Anda tidak memiliki NDA yang aktif. Silahkan ajukan verifikasi NDA terlebih dahulu.</small>
                        @endif
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

    <div class="table-responsive">
        <table class="table">
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
                @forelse($dcafs as $index => $dcaf)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dcaf->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span style="display: inline-flex; align-items: center;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                background-color: 
                                    {{ $dcaf->nda->status == 'pending' ? '#ffc107' : 
                                    ($dcaf->nda->status == 'diterima' ? '#28a745' : '#dc3545') }};">
                            </span>
                            {{ ucfirst($dcaf->nda->status) }}
                        </span>
                    </td>
                    <td>{{ $dcaf->nda->masa_berlaku ? $dcaf->nda->masa_berlaku->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        <span style="display: inline-flex; align-items: center;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                background-color: 
                                    {{ $dcaf->status == 'pending' ? '#ffc107' : 
                                    ($dcaf->status == 'diterima' ? '#28a745' : '#dc3545') }};">
                            </span>
                            {{ ucfirst($dcaf->status) }}
                        </span>
                    </td>
                    <td>{{ $dcaf->masa_berlaku ? $dcaf->masa_berlaku->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $dcaf->nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat NDA</a>
                    </td>
                    <td>
                        <a href="{{ asset('storage/' . $dcaf->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat DCAF</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada riwayat permohonan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
    </div>
@endsection
