@extends('layouts.app')

@section('title', 'Aset Jaringan')
@section('page_title', 'Aset Jaringan')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="main">
        <div class="button-wrapper">
            @if(auth()->user()->role == '1')
                <button class="btn btn-primary mb-3" onclick="openModal('modalTambahJaringan')">+ Tambah Jaringan</button>
                <button type="button" class="btn btn-primary mb-3" onclick="openModal('importModal')">Impor Data
                    Jaringan</button>
                <button type="button" class="btn btn-primary mb-3" onclick="openModal('exportModal')">Export Data
                    Jaringan</button>
            @endif
        </div>

        <div class="table-responsive">
            <table id="jaringanTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Region</th>
                        <th>Tipe Jaringan</th>
                        <th>Segmen</th>
                        <th>Jartatup/Jartaplok</th>
                        <th>Mainlink/Backuplink</th>
                        <th>Panjang</th>
                        <th>Panjang Drawing</th>
                        <th>Jumlah Core</th>
                        <th>Jenis Kabel</th>
                        <th>Tipe Kabel</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jaringan as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->region ? $data->region->nama_region : 'Region Tidak Ditemukan' }}</td>
                            <td>{{ $data->tipe ? $data->tipe->nama_tipe : 'Tipe Tidak Ditemukan' }}</td>
                            <td>{{ $data->segmen }}</td>
                            <td>{{ $data->jartatup_jartaplok }}</td>
                            <td>{{ $data->mainlink_backuplink }}</td>
                            <td>{{ $data->panjang }}</td>
                            <td>{{ $data->panjang_drawing }}</td>
                            <td>{{ $data->jumlah_core }}</td>
                            <td>{{ $data->jenis_kabel }}</td>
                            <td>{{ $data->tipe_kabel }}</td>
                            <td>{{ $data->status }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-eye btn-sm mb-1"
                                        onclick="openModal('modalViewJaringan{{ $data->id_jaringan }}')">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    <button class="btn btn-edit btn-sm mb-1"
                                        onclick="openModal('modalEditJaringan{{ $data->id_jaringan }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-delete btn-sm"
                                        onclick="confirmDelete({{ $data->id_jaringan }})">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>

                                    <form id="delete-form-{{ $data->id_jaringan }}"
                                        action="" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalViewJaringan{{ $data->id_jaringan }}" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalViewJaringan{{ $data->id_jaringan }}')">&times;</span>
        <h5>Detail Jaringan</h5>

        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
            <div style="width: 48%;">
                <label>Region</label>
                <input type="text" value="{{ $data->RO }}" readonly class="form-control">
               
                <label>Segmen</label>
                <input type="text" value="{{ $data->segmen }}" readonly class="form-control">

                <label>Jartatup Jartaplok</label>
                <input type="text" value="{{ $data->jartatup_jartaplok }}" readonly class="form-control">

                <label>Panjang</label>
                <input type="text" value="{{ $data->panjang }}" readonly class="form-control">

                <label>Jumlah Core</label>
                <input type="text" value="{{ $data->jumlah_core }}" readonly class="form-control">

                <label>Tipe Kabel</label>
                <input type="text" value="{{ $data->tipe_kabel }}" readonly class="form-control">

                <label>Ket</label>
                <input type="text" value="{{ $data->ket }}" readonly class="form-control">

                <label>Kode Site Insan</label>
                <input type="text" value="{{ $data->kode_site_insan }}" readonly class="form-control">

                <label>Route</label>
                <input type="text" value="{{ $data->route }}" readonly class="form-control">
            </div>

            <div style="width: 48%;">
                <label>Tipe Jaringan</label>
                <input type="text" value="{{ $data->tipe_jaringan }}" readonly class="form-control">

              
                <label>Panjang Drawing</label>
                <input type="text" value="{{ $data->panjang_drawing }}" readonly class="form-control">

                <label>Jenis Kabel</label>
                <input type="text" value="{{ $data->jenis_kabel }}" readonly class="form-control">

                <label>status</label>
                <input type="text" value="{{ $data->status }}" readonly class="form-control">

                <label>Ket 2</label>
                <input type="text" value="{{ $data->ket2 }}" readonly class="form-control">

                <label>Update</label>
                <input type="text" value="{{ $data->update }}" readonly class="form-control">

                <label>Dci Eqx</label>
                <input type="text" value="{{ $data->dci_eqx }}" readonly class="form-control">
            </div>
        </div>
    </div>
    </div>
@endsection
