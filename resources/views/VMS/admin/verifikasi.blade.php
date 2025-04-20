@extends('layouts.app')
@section('title', 'Verifikasi Dokumen')
@section('page_title', 'Verifikasi Dokumen')
@section('content')
<div class="main">
<div class="container">

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Tanggal Upload</th>
                    <th>Status NDA</th>
                    <th>Masa Berlaku NDA</th>
                    <th>Status DCAF</th>
                    <th>Masa Berlaku DCAF</th>
                    <th>NDA</th>
                    <th>DCAF</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dokumen as $index => $d)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $d->user->name }}</td>
                    <td>{{ $d->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span style="display: inline-flex; align-items: center;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                background-color: 
                                    {{ $d->nda_status == 'pending' ? '#ffc107' : 
                                    ($d->nda_status == 'diterima' ? '#28a745' : '#dc3545') }};">
                            </span>
                            {{ ucfirst($d->nda_status) }}
                        </span>
                    </td>
                    <td>{{ $d->nda_masa_berlaku ? $d->nda_masa_berlaku->translatedFormat('d F Y H:i') : '' }}</td>
                    <td>
                        <span style="display: inline-flex; align-items: center;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                background-color: 
                                    {{ $d->dcaf_status == 'pending' ? '#ffc107' : 
                                    ($d->dcaf_status == 'diterima' ? '#28a745' : '#dc3545') }};">
                            </span>
                            {{ ucfirst($d->dcaf_status) }}
                        </span>
                    </td>
                    <td>{{ $d->dcaf_masa_berlaku ? $d->dcaf_masa_berlaku->translatedFormat('d F Y H:i') : '' }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $d->nda_file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat NDA</a>
                    </td>
                    <td>
                        <a href="{{ asset('storage/' . $d->dcaf_file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat DCAF</a>
                    </td>
                    <td>
                        <div class="action-buttons">
                        @if($d->nda_status == 'pending')
                            <form action="{{ route('verifikasi.approve.nda', $d->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Apakah Anda yakin ingin menerima dokumen NDA ini?')">
                                    <i class="fas fa-check"></i> Terima NDA
                                </button>
                            </form>
                            <form action="{{ route('verifikasi.reject.nda', $d->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Apakah Anda yakin ingin menolak dokumen NDA ini?')">
                                    <i class="fas fa-times"></i> Tolak NDA
                                </button>
                            </form>
                        @endif
                        @if($d->dcaf_status == 'pending')
                            <form action="{{ route('verifikasi.approve.dcaf', $d->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Apakah Anda yakin ingin menerima dokumen DCAF ini?')">
                                    <i class="fas fa-check"></i> Terima DCAF
                                </button>
                            </form>
                            <form action="{{ route('verifikasi.reject.dcaf', $d->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Apakah Anda yakin ingin menolak dokumen DCAF ini?')">
                                    <i class="fas fa-times"></i> Tolak DCAF
                                </button>
                            </form>
                        @endif
                        @if($d->nda_status != 'pending' && $d->dcaf_status != 'pending')
                            <span class="text-muted">Sudah diverifikasi</span>
                        @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada dokumen yang perlu diverifikasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Modal Tanda Tangan -->
<div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">Tanda Tangan Digital</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="signatureForm" method="POST">
                    @csrf
                    <input type="hidden" name="dokumen_id" id="dokumen_id">
                    <div class="form-group">
                        <label>Tanda Tangan:</label>
                        <div class="border rounded p-2">
                            <canvas id="signaturePad" width="400" height="200" style="border:1px solid #000000;"></canvas>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature()">Clear</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status Verifikasi:</label>
                        <select name="status" class="form-control" required>
                            <option value="diterima">Terima</option>
                            <option value="ditolak">Tolak</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveSignature()">Simpan</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    let signaturePad;
    let currentDokumenId;

    function openSignatureModal(dokumenId) {
        currentDokumenId = dokumenId;
        document.getElementById('dokumen_id').value = dokumenId;
        
        const canvas = document.getElementById('signaturePad');
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });
        
        $('#signatureModal').modal('show');
    }

    function clearSignature() {
        signaturePad.clear();
    }

    function saveSignature() {
        if (signaturePad.isEmpty()) {
            alert('Harap tanda tangan terlebih dahulu');
            return;
        }

        const signatureData = signaturePad.toDataURL();
        const form = document.getElementById('signatureForm');
        const formData = new FormData(form);
        formData.append('signature', signatureData);

        fetch(`/verifikasi/sign/${currentDokumenId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Terjadi kesalahan saat menyimpan tanda tangan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan tanda tangan');
        });
    }
</script>
@endpush
