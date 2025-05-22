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
                            ($dcaf->status == 'diterima' ? '#28a745' :
                                ($dcaf->status == 'ditolak' ? '#dc3545' : '#ffc107')) }};">
                                                    </span>
                                                    {{ ucfirst($dcaf->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $dcaf->catatan ?? '-' }}</td>
                                            <td>
                                                <a href="{{ asset($dcaf->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat
                                                    File</a>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    onclick="openModal('pengawasModal{{ $dcaf->id }}')">Terima</button>

                                                <button type="button" class="btn btn-delete btn-sm" style="font-size: 14px;"
                                                    onclick="konfirmasiTolak({{ $dcaf->id }})">Tolak</button>

                                                <form id="form-tolak-{{ $dcaf->id }}" action="{{ route('dcaf.update', $dcaf->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="ditolak">
                                                </form>
                                            </td>
                                        </tr>

                                        <div id="pengawasModal{{ $dcaf->id }}" class="modal">
                                            <div class="modal-content">
                                                <span class="close" onclick="closeModal('pengawasModal{{ $dcaf->id }}')">&times;</span>
                                                <h5>Pilih Pengawas</h5>
                                                <form action="{{ route('dcaf.update', $dcaf->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="diterima">
                                                    <div class="mb-3">
                                                        <label>Pengawas</label>
                                                        <select name="pengawas" class="form-control" required>
                                                            <option value="">Pilih Pengawas</option>
                                                            @foreach($users as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Terima</button>
                                                </form>
                                            </div>
                                        </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada dokumen DCAF yang perlu diverifikasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="modal fade" id="modalSetuju" tabindex="-1" aria-labelledby="modalSetujuLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="form-terima" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="diterima">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Pilih Pengawas</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label for="pengawas">Pengawas</label>
                                <select class="form-control select2" name="user_id" id="selectPengawas" required>
                                    <option value="">Pilih Pengawas</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </div>
                        </div>
                    </form>
                </div>
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
                                                                                                                                                                                                                                                                                    {{ $dcaf->status == 'menunggu persetujuan' ? '#ffc107' :
                            ($dcaf->status == 'diterima' ? '#28a745' :
                                ($dcaf->status == 'ditolak' ? '#dc3545' : '#ffc107')) }};">
                                                    </span>
                                                    {{ ucfirst($dcaf->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $dcaf->catatan ?? '-' }}</td>
                                            <td>
                                                <a href="{{ asset($dcaf->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat
                                                    File</a>
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
                                                                                                                                                                                                                                                                                    {{ $dcaf->status == 'menunggu persetujuan' ? '#ffc107' :
                            ($dcaf->status == 'diterima' ? '#28a745' :
                                ($dcaf->status == 'ditolak' ? '#dc3545' : '#ffc107')) }};">
                                                    </span>
                                                    {{ ucfirst($dcaf->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $dcaf->catatan ?? '-' }}</td>
                                            <td>
                                                <a href="{{ asset('storage/' . $dcaf->file_path) }}" target="_blank"
                                                    class="btn btn-sm btn-info">Lihat File</a>
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
    <script>
        function konfirmasiTolak(id) {
            Swal.fire({
                title: 'Yakin mau tolak?',
                text: 'NDA ini akan ditolak.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-tolak-' + id).submit();
                }
            });
        }
    </script>
@endsection