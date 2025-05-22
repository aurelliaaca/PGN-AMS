@extends('layouts.app')
@section('title', 'Pendaftaran Kunjungan')
@section('page_title', 'Pendaftaran Kunjungan')
@section('content')
<div class="main">
<div class="container">
    <!-- Tombol untuk membuka modal -->
    <button class="btn btn-primary mb-3" style="margin-top: 20px; margin-bottom: 10px;" onclick="window.location.href='{{ route('pendaftarandcaf') }}'">Buat DCAF</button>

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
                                    ($dcaf->status == 'diterima' ? '#28a745' : 
                                    ($dcaf->status == 'ditolak' ? '#dc3545' : '#ffc107')) }};">
                            </span>
                            {{ ucfirst($dcaf->status) }}
                        </span>
                    </td>
                    <td>{{ $dcaf->masa_berlaku ? $dcaf->masa_berlaku->format('d/m/Y H:i') : '-' }}</td>
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
                            <span class="text-muted">Belum dapat diakses</span>
                        @endif
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
