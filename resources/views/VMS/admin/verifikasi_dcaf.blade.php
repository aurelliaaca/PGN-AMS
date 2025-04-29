@extends('layouts.app')
@section('title', 'Verifikasi DCAF')
@section('page_title', 'Verifikasi DCAF')
@section('content')
<div class="main">
<div class="container">
    <h4 class="mb-4">Dokumen yang Perlu Diverifikasi</h4>
    <div class="table-responsive mb-5">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Tanggal Upload</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingDcafs as $index => $dcaf)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dcaf->user->name }}</td>
                    <td>{{ $dcaf->created_at->format('d/m/Y H:i') }}</td>
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
                    <td>{{ $dcaf->catatan ?? '-' }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $dcaf->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat File</a>
                    </td>
                    <td>
                        @if($dcaf->status == 'pending')
                            <form action="{{ route('verifikasi.approve.dcaf', $dcaf->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Apakah Anda yakin ingin menerima dokumen DCAF ini?')">
                                    <i class="fas fa-check"></i> Terima
                                </button>
                            </form>
                            <form action="{{ route('verifikasi.reject.dcaf', $dcaf->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Apakah Anda yakin ingin menolak dokumen DCAF ini?')">
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
                    <td colspan="7" class="text-center">Tidak ada dokumen DCAF yang perlu diverifikasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h4 class="mb-4">DCAF yang Masih Berlaku</h4>
    <div class="table-responsive mb-5">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Tanggal Upload</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>File</th>
                    <th>Tanggal Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeDcafs as $index => $dcaf)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dcaf->user->name }}</td>
                    <td>{{ $dcaf->created_at->format('d/m/Y H:i') }}</td>
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
                    <td>{{ $dcaf->catatan ?? '-' }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $dcaf->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat File</a>
                    </td>
                    <td>{{ $dcaf->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada DCAF yang masih berlaku</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h4 class="mb-4">Riwayat DCAF yang Tidak Berlaku</h4>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Tanggal Upload</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>File</th>
                    <th>Tanggal Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expiredDcafs as $index => $dcaf)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dcaf->user->name }}</td>
                    <td>{{ $dcaf->created_at->format('d/m/Y H:i') }}</td>
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
                    <td>{{ $dcaf->catatan ?? '-' }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $dcaf->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat File</a>
                    </td>
                    <td>{{ $dcaf->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada riwayat DCAF yang tidak berlaku</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection 