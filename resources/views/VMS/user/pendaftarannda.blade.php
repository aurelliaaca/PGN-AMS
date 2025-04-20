@extends('layouts.app')
@section('title', 'Portal VMS')
@section('page_title', 'Portal VMS')
@section('content')
<div class="main">
    <div class="container">
        <!-- Tombol untuk membuka modal -->
        <button class="btn btn-primary mb-3" onclick="openModal('modalAjukanNDA')">Ajukan Verifikasi NDA</button>

        <!-- Modal Upload NDA -->
        <div class="modal" id="modalAjukanNDA">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalAjukanNDA')">&times;</span>
                <h5>Ajukan Verifikasi NDA</h5>
                <form action="{{ route('verifikasi.nda.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>File NDA (PDF atau DOCX)</label>
                        <input type="file" name="file_path" id="file_path" class="form-control" accept=".pdf,.doc,.docx" required>
                        <small class="text-muted">Maksimum ukuran file: 10MB</small>
                    </div>
                    <div class="mb-3">
                        <label>Catatan (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Riwayat NDA -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Upload</th>
                        <th>Status</th>
                        <th>Masa Berlaku</th>
                        <th>Catatan</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ndas as $index => $nda)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $nda->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span style="display: inline-flex; align-items: center;">
                                <span style="width: 10px; height: 10px; border-radius: 3px; margin-right: 8px;
                                    background-color: 
                                        {{ $nda->status == 'pending' ? '#ffc107' : 
                                        ($nda->status == 'diterima' ? '#28a745' : '#dc3545') }};">
                                </span>
                                {{ ucfirst($nda->status) }}
                            </span>
                        </td>
                        <td>{{ $nda->masa_berlaku ? $nda->masa_berlaku->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $nda->catatan ?? '-' }}</td>
                        <td>
                            <a href="{{ asset('storage/' . $nda->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat File</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada riwayat NDA</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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

// Close modal when clicking outside of it
window.onclick = function(event) {
    if (event.target.className === 'modal') {
        event.target.style.display = "none";
    }
}
</script>
@endsection 