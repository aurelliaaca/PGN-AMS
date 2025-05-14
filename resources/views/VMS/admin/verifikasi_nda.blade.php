@extends('layouts.app')
@section('title', 'Verifikasi NDA')
@section('page_title', 'Verifikasi NDA')
@section('content')
<div class="main">
<div class="container">
    <h4 class="mb-4">NDA yang Perlu Diverifikasi</h4>
    <div class="table-responsive mb-5">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Tanggal Upload</th>
                    <th>Status</th>
                    <th>Masa Berlaku</th>
                    <th>Catatan</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingNdas as $index => $nda)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $nda->user->name }}</td>
                    <td>{{ $nda->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span style="display: inline-flex; align-items: center;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                background-color: 
                                    {{ $nda->status == 'menunggu persetujuan' ? '#ffc107' : 
                                    ($nda->status == 'diterima' ? '#28a745' : '#dc3545') }};">
                            </span>
                            {{ ucfirst($nda->status) }}
                        </span>
                    </td>
                    <td>{{ $nda->masa_berlaku ? $nda->masa_berlaku->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $nda->catatan ?? '-' }}</td>
                    <td>
                        <a href="{{ asset('pdf/' . $nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat File</a>
                    </td>
                    <td>
                        @if($nda->status == 'menunggu persetujuan')
                            <form action="{{ route('verifikasi.approve.nda', $nda->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Apakah Anda yakin ingin menerima dokumen NDA ini?')">
                                    <i class="fas fa-check"></i> Terima
                                </button>
                            </form>
                            <form action="{{ route('verifikasi.reject.nda', $nda->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Apakah Anda yakin ingin menolak dokumen NDA ini?')">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </form>
                        @else
                            <span class="text-muted">Sudah diverifikasi</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada dokumen NDA yang perlu diverifikasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h4 class="mb-4">NDA yang Masih Berlaku</h4>
    <div class="table-responsive mb-5">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Tanggal Upload</th>
                    <th>Status</th>
                    <th>Masa Berlaku</th>
                    <th>Catatan</th>
                    <th>File</th>
                    <th>Tanggal Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeNdas as $index => $nda)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $nda->user->name }}</td>
                    <td>{{ $nda->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span style="display: inline-flex; align-items: center;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                background-color: 
                                    {{ $nda->status == 'menunggu persetujuan' ? '#ffc107' : 
                                    ($nda->status == 'diterima' ? '#28a745' : '#dc3545') }};">
                            </span>
                            {{ ucfirst($nda->status) }}
                        </span>
                    </td>
                    <td>{{ $nda->masa_berlaku ? $nda->masa_berlaku->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $nda->catatan ?? '-' }}</td>
                    <td>
                        <a href="{{ asset('pdf/' . $nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat File</a>
                    </td>
                    <td>{{ $nda->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada NDA yang masih berlaku</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h4 class="mb-4">Riwayat NDA yang Tidak Berlaku</h4>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Tanggal Upload</th>
                    <th>Status</th>
                    <th>Masa Berlaku</th>
                    <th>Catatan</th>
                    <th>File</th>
                    <th>Tanggal Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expiredNdas as $index => $nda)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $nda->user->name }}</td>
                    <td>{{ $nda->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span style="display: inline-flex; align-items: center;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                background-color: 
                                    {{ $nda->status == 'menunggu persetujuan' ? '#ffc107' : 
                                    ($nda->status == 'diterima' ? '#28a745' : '#dc3545') }};">
                            </span>
                            {{ ucfirst($nda->status) }}
                        </span>
                    </td>
                    <td>{{ $nda->masa_berlaku ? $nda->masa_berlaku->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $nda->catatan ?? '-' }}</td>
                    <td>
                        <a href="{{ asset('pdf/' . $nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat File</a>
                    </td>
                    <td>{{ $nda->updated_at->format('d/m/Y H:i') }}</td>
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
@endsection 